<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="stylesheet" href= "/css/style.css" media="screen and (min-width:600px)" >


        <link rel="shortcut icon" type="image/png" href="/images/favicon.png">


        <title>My Monitor | <?php /*echo Helper::get_script_name() */?></title>

        <!-- <script>alert("Welcome!");</script> -->

    </head>

    <body>

        <div class="bg-video">

                @if(($_SERVER["PHP_SELF"] != "/index.php/users/create") && ($_SERVER["PHP_SELF"] != "/index.php/sign_in"))
                    @if(\App\User::first()->background_image != null)
                        @include('layouts.background_image')
                    @else
                        @include('layouts.background_vedio')
                    @endif
                @else
                    @include('layouts.background_vedio')
            @endif
        </div>

        <header>
            @include('layouts/logo')
                @if(($_SERVER["PHP_SELF"] != "/index.php/users/create") && ($_SERVER["PHP_SELF"] != "/index.php/sign_in"))
                    @include('layouts/search_box')
                    @include('layouts/navigation')
                @endif
            @include('layouts/session_messages')

        </header>
