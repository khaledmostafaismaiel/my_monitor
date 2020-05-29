<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
         
        <link rel="stylesheet"  href= "stylesheets/css/style.css"  media="screen and (min-width:600px)" > 


        <link rel="shortcut icon" type="image/png" href="images/favicon.png">


        <title>My Monitor</title>

        <!-- <script>alert("Welcome!");</script> -->
    
    </head>


    <body>

        <div class="bg-video">
            <video class="bg-video__content" autoplay muted loop>
                <source src="images/video.mp4" type="video/mp4">
                <source src="images/video.webm" type="video/webm">
                Your browser is not supported!
            </video>
        </div>


        <header>
            <a href="index.php" class="">
                <img src="images/favicon.png" class="header-image" alt="faveicon">
            </a>
            

            <?php include("navigation.php")?>


        </header>