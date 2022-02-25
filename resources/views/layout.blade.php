<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      <link href="{{ asset('css/custom_style.css') }}" rel="stylesheet">
      <title>Prueba desarrollo | YECID TOVAR</title>
      <div id="loadOverlay" style="background-color:#FFF; position:fixed; top:0px; left:0px; width:100%; height:100%; z-index:2000;">
         <div id="preview-area">
            <div class="sk-chase">
               <div class="sk-chase-dot"></div>
               <div class="sk-chase-dot"></div>
               <div class="sk-chase-dot"></div>
               <div class="sk-chase-dot"></div>
               <div class="sk-chase-dot"></div>
               <div class="sk-chase-dot"></div>
            </div>
         </div>
      </div>
   </head>
   <body >
      <div id="app">
         <v-app id="inspire">
            @yield('components')
         </v-app>
      </div>
      <script src="{{ asset('js/app.js') }}"></script>    
   </body>
</html>