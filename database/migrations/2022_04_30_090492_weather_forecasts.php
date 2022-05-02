<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WeatherForecasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_forecasts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('location_id');

            $table->string('weather');
            $table->string('weather_icon', 20);
            $table->float('temperature', 5, 2);
            $table->integer('humidity');
            $table->date('forecast_date');
            $table->timestamps();

            $table->foreign('location_id')
            ->references('id')
            ->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_forecasts');
    }
}
