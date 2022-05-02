<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\WeatherForecast;

class WeatherController extends Controller
{
    public function getWeather($date) {
        if($date != '') {
            // Check if weather forecast for given date exist in DB
            if(DB::table('weather_forecasts')->where('forecast_date', $date)->exists()) {
                // If exist return weather forecast
                return $this->returnWeather($date);
            } else {
                // If does not exist, store weather forecast in DB if weather forecast is available, then return. Else return an error
                $forecastLimit = Carbon::now()->addDays(16);
                $inputDate = Carbon::parse($date);
                if($inputDate->gt($forecastLimit) || $inputDate->isPast()) {
                    return response()->json(['message' => 'Weather for this date is not available. Please select another day'], 404);
                } else {
                    return $this->storeWeather($date);
                }
            }
        } else {
            return response()->json(['message' => 'Select a valid date'], 404);
        }
    }

    public function storeWeather($date) {

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

            $inputDate = Carbon::parse($date);
            $forecastDay = $inputDate->diffInDays(Carbon::now());
            $weatherForecast = $weatherResponse->list[$forecastDay + 1];

            $forecast = WeatherForecast::create([
                'location_id' => $location->id,
                'weather' => $weatherForecast->weather[0]->description,
                'weather_icon' => $weatherForecast->weather[0]->icon,
                'temperature'=> $weatherForecast->temp->day,
                'humidity'=> $weatherForecast->humidity,
                'forecast_date'=> $date
            ]);
        }
        return $this->returnWeather($date);
    }

    public function returnWeather($date) {
        $getForecast = DB::table('weather_forecasts')->where('forecast_date', $date)->get();
        foreach ($getForecast as $forecast) {
            $location = DB::table('locations')->where('id', $forecast->location_id)->first();
            $forecast->location_name = $location->city_name;
        }
        return json_encode($getForecast);
    }
}
