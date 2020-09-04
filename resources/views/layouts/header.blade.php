<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="stylesheet" href= "/css/style.css" media="screen and (min-width:600px)" >
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

        <link rel="shortcut icon" type="image/png" href="/images/favicon.png">


        <title>My Monitor | <?php /*echo Helper::get_script_name() */?></title>

        <!-- <script>alert("Welcome!");</script> -->

    </head>

    <body>
        <div class="bg-video">

        @if(($_SERVER["PHP_SELF"] != "/index.php/users/create") && ($_SERVER["PHP_SELF"] != "/index.php/login") && ($_SERVER["PHP_SELF"] != "/index.php/users/process_sign_out") )
                @if(auth()->user()->background_image != null)
                    @include('layouts.background_image')
                @else
                    @include('layouts.background_video')
                @endif
            @else
                @include('layouts.background_video')
            @endif
        </div>

        <header>
            @include('layouts/logo')
                @if(($_SERVER["PHP_SELF"] != "/index.php/users/create") && ($_SERVER["PHP_SELF"] != "/index.php/login") && ($_SERVER["PHP_SELF"] != "/index.php/users/process_sign_out") )
                    @include('layouts/search_box')
                    @include('layouts/navigation')
                @endif
            @include('layouts/session_messages')
        </header>
