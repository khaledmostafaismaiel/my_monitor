<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href= "../../css/style.css" media="screen and (min-width:600px)" >


    <link rel="shortcut icon" type="image/png" href="../../images/favicon.png">


    <title>My Monitor | <?php /*echo Helper::get_script_name() */?></title>

    <!-- <script>alert("Welcome!");</script> -->

</head>


<body>

<div class="bg-video">
    <?php
    //                if(($_SERVER["PHP_SELF"] != "/sign_up.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php")){
    //                    if( $_SESSION["background_image"] != null){
    //                        $image_name = $_SESSION["background_image"];
    //                        $src="../uploads/".$image_name;
    //                        //$src= LIB_PATH.DS.'uploads'.DS.$image_name;
    //                        $out_put = "<img  src=$src  class=\"bg-video__content\"alt=\"$image_name\">" ;
    //                    }else{
    //
    //                        $out_put = "<video class=\"bg-video__content\" autoplay muted loop>";
    //                        $out_put .= "<source src=\"images/video.mp4\" type=\"video/mp4\">";
    //                        $out_put .= "<source src=\"images/video.webm\" type=\"video/webm\">";
    //                        $out_put .= "Your browser is not supported!" ;
    //                        $out_put .= "</video>" ;
    //                    }
    //                }else{
    //
    //                    $out_put = "<video class=\"bg-video__content\" autoplay muted loop>";
    //                    $out_put .= "<source src=\"images/video.mp4\" type=\"video/mp4\">";
    //                    $out_put .= "<source src=\"images/video.webm\" type=\"video/webm\">";
    //                    $out_put .= "Your browser is not supported!" ;
    //                    $out_put .= "</video>" ;
    //                }
    //                echo $out_put ;
    ?>

</div>

<header>
    <?php
    //
    //                Helper::include_layout_template("logo.php");
    //                if(($_SERVER["PHP_SELF"] != "/sign_up.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php") && ($_SERVER["PHP_SELF"] != "/sign_in.php") ){
    //                    Helper::include_layout_template("search_box.php");
    //                    Helper::include_layout_template("navigation.php");
    //
    //                }
    //
    //                //echo "<pre>" ;print_r($_SERVER["PHP_SELF"]);
    //
    //                echo session::session_message();
    ?>

</header>


<a href="index.php?" class="">
    <img src="images/favicon.png" class="header-image" alt="logo">
</a>


<form action="search.php?pagenumber=1" method="post">

    <div class="search_box">
        <input class="search_box-text" type="text" name="search" placeholder="search">
        <img src="images/search.png"  class="search_box-img"></i>
        <input  class="search_box-btn"   type="submit" name="submit_search"  >
    </div>

</form>

<div class="navigation">

    <input type="checkbox" class="navigation__checkbox" id="navi-toggle">
    <label for="navi-toggle" class="navigation__button">
        <span class="navigation__icon">Menu</span>
    </label>


    <div class="navigation__background">
        &nbsp;
    </div>

    <nav class="navigation__nav">
        <ul class="navigation__list">
            <li class="navigation__item-1"><a href="add_expense.php" class="navigation__link">+ Add Expense</a></li>
            <li class="navigation__item-2"><a href="expenses.php?pagenumber=1" class="navigation__link">Expenses</a></li>
            <li class="navigation__item-3"><a href="add_background.php" class="navigation__link">+ Add Background</a></li>
            <li class="navigation__item-4"><a href="backgrounds.php?pagenumber=1" class="navigation__link">Backgrounds</a></li>
            <li class="navigation__item-5"><a href="sign_out.php" class="navigation__link">Sign Out</a></li>
        </ul>
    </nav>

</div>


@yield('content')

<footer >

    <div class="row">
        <div class="col-1-of-2">
            <div class="footer__logo">

            </div>
        </div>
        <div class="col-1-of-2">
            <p class="footer__copyright">
                Built by <a href="MY_CV/my_cv.html" class="footer--link" target ="_blank">Khaled Mostafa</a> .
                Copyright &copy; 2020-<?php /*echo date("Y");*/?> by Khaled Mostafa . You are 100% allowed to use this webpage for both personal
                and commercial use, but NOT to claim it as your own design. A credit to the original author, Khaled
                Mostafa, is of course highly appreciated!
            </p>
        </div>
    </div>

</footer>


</body>

</html>
<?php
//    //5. close database connection
//    if(isset($database)){
//        $database->close_connection();
//    }
