<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="stylesheet" href= "/css/style.css" media="screen and (min-width:600px)" >
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

        <link rel="shortcut icon" type="image/png" href="/images/favicon.png">


        <title>{{env("APP_NAME")}}</title>


    </head>

    <body>
        <div class="bg-video">
            @include('layouts.background_video')
        </div>

        <header>
            @include('layouts/logo')
            @if(auth()->user())
                @include('layouts/search_box')
                @include('layouts/navigation')
            @endif
            @include('layouts/session_messages')
        </header>
