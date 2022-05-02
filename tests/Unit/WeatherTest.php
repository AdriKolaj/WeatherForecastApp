<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;

class WeatherTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_today_forecast_api()
    {
        $date = Carbon::now()->toDateString();
        $response = $this->get('/api/weather' . '/' . $date);
        $response->assertStatus(200);
    }
    public function test_oneweek_forecast_api()
    {
        $date = Carbon::now()->addDays(7)->toDateString();
        $response = $this->get('/api/weather' . '/' . $date);
        $response->assertStatus(200);
    }
    public function test_overdate_forecast_api()
    {
        $date = Carbon::now()->addDays(17)->toDateString();
        $response = $this->get('/api/weather' . '/' . $date);
        $response->assertStatus(404);
    }
    public function test_past_forecast_api()
    {
        $date = Carbon::now()->subDays(4)->toDateString();
        $response = $this->get('/api/weather' . '/' . $date);
        $response->assertStatus(404);
    }
}
