<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'weather',
        'weather_icon',
        'temperature',
        'humidity',
        'forecast_date',
    ];

    public function location() {
        return $this->belongsTo('App\Location');
    }
}
