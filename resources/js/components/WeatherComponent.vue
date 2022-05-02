<template>
    <!-- Choose date -->
    <div v-if="showForecast == false" class="select-date-container">
        <input type="date" v-model="selectedDate" />
        <div class="error-message mb-2" v-if="errorMessage != ''">
            {{errorMessage}}
        </div>
        <button @click="getForecast()">Get Forecast</button>
    </div>

    <!-- Show Weather Forecast -->
    <div v-else class="weather-container">
        <div class="forecast-date">{{ selectedDate }}</div>
        <div class="forecast-container">
            <div class="city-forecast" v-for="forecast in forecastData" :key="forecast.location">
                <div class="city-container">
                    <div class="city">{{ forecast.location }}</div>
                </div>
                <div class="other-infos-container">
                    <div class="image-container">
                        <img class="card-image" :src="'images/' + forecast.weatherIcon + '.png'" :alt="forecast.weather">
                    </div>
                    <div class="details-container">
                        <div class="weather">{{ forecast.weather }}</div>
                        <div class="temperature">Temperature: {{ forecast.temperature }} K</div>
                        <div class="humidity">Humidity: {{ forecast.humidity }} %</div>
                    </div>
                </div>
            </div>
        </div>
        <button class="back-btn" @click="goBack()">Go Back</button>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                forecastData: [],
                selectedDate: '',
                errorMessage: '',
                showForecast: false
            }
        }, 
        methods: {
            getForecast: function() {
                if(this.selectedDate != '') {
                    axios
                    .get('/api/weather/' + this.selectedDate)
                    .then((response) => {
                        for (let i = 0; i < response.data.length; i++) {
                            let forecast = {};
                            forecast.location = response.data[i].location_name;
                            forecast.weather = response.data[i].weather;
                            forecast.weatherIcon = response.data[i].weather_icon;
                            forecast.temperature = response.data[i].temperature;
                            forecast.humidity = response.data[i].humidity;
                            forecast.date = response.data[i].forecast_date;

                            this.forecastData.push(forecast);
                        }
                        this.showForecast = true;
                    })
                    .catch(error => {
                        this.errorMessage = error.response.data.message;
                    });
                }
                else {
                    this.errorMessage = "Select a valid date";
                }
            },
            goBack: function() {
                this.showForecast = false;
                this.forecastData = [];
                this.selectedDate = '';
                this.errorMessage = '';
            }
        }
    }
</script>