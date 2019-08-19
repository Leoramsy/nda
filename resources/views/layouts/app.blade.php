<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <!-- Styles -->        
        @include('partials.styles')        
        <script>
            window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
            ]) !!}
            ;
        </script>    
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-default navbar-static-top" style="background-color: black; color: white;">
                <div class="container">

                    <div class="navbar-header">
                        <!-- Collapsed Hamburger -->

                        <div style="display: inline-grid;display: -ms-inline-grid">
                            <a class="navbar-brand" href="{{ url('/') }}" >
                                National Debt Advisors                         
                            </a>

                        </div>
                        <!-- Branding Image -->

                    </div>                    
                    <div class="collapse navbar-collapse show" id="app-navbar-collapse" >                                                              
                        <ul class="nav navbar-nav">  
                            <li class=""><a href="{{route('contacts.index')}}">Contacts</a></li>
                        </ul>
                    </div>  
                </div>
            </nav>            
            @yield('content')
            <!-- Bootstrap core JavaScript
           ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->
            <!-- <script src="{{ asset('packages/datatables.min.js') }}"></script> -->
            @include('partials.scripts')     
        </div>
    </body>
</html>
