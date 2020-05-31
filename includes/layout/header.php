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
            <?php
                if(isset($_GET["currentpage"])){
                    $current_page_1 = $_GET["currentpage"] ;
                }else{
                    $current_page_1 = null ;
                }

                if(($current_page_1 != "signup") && ($current_page_1 != "signin")){
                    include("logo.php") ;
                    include("search_box.php") ;
                    include("navigation.php") ;
                }
            ?>

            <?php
                echo session_message();            
            ?>    
            
        </header>