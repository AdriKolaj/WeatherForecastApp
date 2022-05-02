<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>Weather App</title>

      <!-- Styles -->
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      
  </head>
  <body>
      <header>
        <div class="navbar-custom">
            <h1>Weather App</h1>
        </div>
      </header>
      <main>
        <div id="app" class="main-container">
          <weather-component></weather-component>
        </div>
      </main>
      <footer>

      </footer>

      {{-- Import JS --}}
      <script defer src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
