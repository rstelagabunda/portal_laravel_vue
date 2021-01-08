<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="images/logo-rstb.png"/>
        <link rel="manifest" crossorigin="use-credentials" href="{{asset('manifest.json')}}" />
        <title>R S Telaga Bunda - Portal</title>
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" > -->

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
      integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
      crossorigin="anonymous"/> 
       
    </head>
    <body>
        <main>
        <div id="app">
       <main-app/>
       </div>
       </main>
       <footer class="pt-2 #ba68c8 purple lighten-2">
          <br>
          <div class="footer-copyright #ba68c8 purple lighten-2">
            <div class="container white-text">
            <i class="fa fa-copyright"></i> RS Telaga Bunda
            <a class="grey-text text-lighten-4 right " href="https://rstelagabunda.com"> <i class="fa fa-globe-asia"></i> Rstelagabunda.com</a>
            </div>
          </div>
        </footer>
        <!-- <script src="{{ asset('js/pdf.js')}}"></script> -->
        
        <!-- Styles -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.3.200/pdf.js"></script>
       
       <script src="{{ asset('js/app.js')}}"></script>
       <!-- <script src="{{ asset('js/all.js')}}"></script> -->
        </body>
        <style>
         body {
     display: flex;
     min-height: 100vh;
     flex-direction: column;
 }
 main {
     flex: 1 0 auto;
 }
 ::-webkit-scrollbar {
  width: 5px;
  height:5px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 3px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #ba68c8; 
  border-radius: 3px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #b30000; 
}
 
 </style>
</html>
