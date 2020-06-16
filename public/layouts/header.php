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
            <?php
                if(($_SERVER["PHP_SELF"] != "/sign_up.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php")){
                    if( $_SESSION["background_image"] != null){
                        $image_name = $_SESSION["background_image"];
                        $src="../uploads/".$image_name;
                        //$src= LIB_PATH.DS.'uploads'.DS.$image_name;
                        $out_put = "<img  src=$src  class=\"bg-video__content\"alt=\"$image_name\">" ;
                    }else{

                        $out_put = "<video class=\"bg-video__content\" autoplay muted loop>"; 
                        $out_put .= "<source src=\"images/video.mp4\" type=\"video/mp4\">";
                        $out_put .= "<source src=\"images/video.webm\" type=\"video/webm\">";
                        $out_put .= "Your browser is not supported!" ;
                        $out_put .= "</video>" ;
                    }
                }else{
                 
                    $out_put = "<video class=\"bg-video__content\" autoplay muted loop>"; 
                    $out_put .= "<source src=\"images/video.mp4\" type=\"video/mp4\">";
                    $out_put .= "<source src=\"images/video.webm\" type=\"video/webm\">";
                    $out_put .= "Your browser is not supported!" ;
                    $out_put .= "</video>" ;
                }
                echo $out_put ;
            ?>

        </div>

        <header>
            <?php

                include_layout_template("logo.php");
                if(($_SERVER["PHP_SELF"] != "/sign_up.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php") ){
                    include_layout_template("search_box.php");                    
                    include_layout_template("navigation.php");

                }
                
                //echo "<pre>" ;print_r($_SERVER["PHP_SELF"]);

                echo session::session_message();            
            ?>    
            
        </header>