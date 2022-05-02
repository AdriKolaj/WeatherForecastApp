<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\Models\WeatherForecast;

class ForecastCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDate = Carbon::now()->toDateString();
        
        // If a today forecast already exists update it, else store
        if (DB::table('weather_forecasts')->where('forecast_date', $currentDate)->exists()) {
            $locations = DB::table('locations')->get();
            foreach ($locations as $location) {
                $weatherCurl = curl_init();
                curl_setopt_array($weatherCurl, array(
                    CURLOPT_URL => 'http://api.openweathermap.org/data/2.5/forecast/daily?lat='. $location->lat .'&lon='. $location->lon .'&cnt=16&appid=1966832e557ca5ed0b69375daa8f0efa',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $weatherResponse = curl_exec($weatherCurl);
                curl_close($weatherCurl);
                $weatherResponse = json_decode($weatherResponse);

                $weatherForecast = $weatherResponse->list[0];
                $forecast =  WeatherForecast::where([['forecast_date', '=' ,$currentDate], ['location_id', '=', $location->id]])->first();
                $newForecast["weather"] = $weatherForecast->weather[0]->description;
                $newForecast["weather_icon"] = $weatherForecast->weather[0]->icon;
                $newForecast["temperature"] = $weatherForecast->temp->day;
                $newForecast["humidity"] = $weatherForecast->humidity;
                $newForecast["forecast_date"] = $currentDate;
                $forecast->update($newForecast);
            }
            
            return 'Weather Forecast Updated';
        } else {
            $locations = DB::table('locations')->get();

            foreach ($locations as $location) {
                $weatherCurl = curl_init();
                curl_setopt_array($weatherCurl, array(
                    CURLOPT_URL => 'http://api.openweathermap.org/data/2.5/forecast/daily?lat='. $location->lat .'&lon='. $location->lon .'&cnt=16&appid=1966832e557ca5ed0b69375daa8f0efa',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                $weatherResponse = curl_exec($weatherCurl);
                curl_close($weatherCurl);
                $weatherResponse = json_decode($weatherResponse);
    
                $weatherForecast = $weatherResponse->list[0];
    
                $forecast = WeatherForecast::create([
                    'location_id' => $location->id,
                    'weather' => $weatherForecast->weather[0]->description,
                    'weather_icon' => $weatherForecast->weather[0]->icon,
                    'temperature'=> $weatherForecast->temp->day,
                    'humidity'=> $weatherForecast->humidity,
                    'forecast_date'=> $currentDate
                ]);
            }
            return 'Weather Forecast Stored';
        }
    }
}
