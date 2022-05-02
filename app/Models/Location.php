<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_name',
        'lat',
        'lon',
    ];

    public function weatherForecasts(){
        return $this->hasMany('App\WeatherForecast');
    }
}
