<?php
//    require_once("../includes/initialize.php");
//
//    $user_name = "" ;
//
//    if(isset($_POST['submit_sign_in'])){
//        //prcess the form
//        //escape all strings to prevent sql injection with sql_sanitize
//        $user = new User();
//
//        $user->user_name = $_POST["user_name"] ;
//        $user->password = $_POST["password"];
//        $user->remember_me = $_POST["remember_me"] ;
//
//        if($user->check_before_sign_in()){
//            //success
//            Log::write_in_log("{$_SESSION['user_id']} signed in ".date("d-m-Y")." ".date("h:i:sa")."\n");
//            Helper::redirect_to("index.php?");
//        }else{
//            //failed
//             Helper::redirect_to("sign_in.php?");
//        }
//
//    }else{
//        //this is probably $_GET request
//        //i will check if user is active or not
//
//    }
?>
@extends('layouts.master_layout')
@section('content')
<h1 class="message-welcome">
    Welcome to My Monitor
</h1>

<div class="form">

    <form  name="submit"  method="post" >


        <fieldset class="form-sign_in">
            <legend>
                <h2>
                    Please, Sign in ...
                </h2>
            </legend>

            <p>

                User Name:
                <input type="text" class="form-sign_in-user_name" name="user_name" value="<?php /*echo $user_name*/?>" placeholder="Your E_mail">

            </p>

            <br />

            <p>
                Password: &nbsp;
                <input type="password" class="form-sign_in-password" name="password" value="" placeholder="Your Password">
            </p>

            <br />

            <input name="remember_me" type="checkbox" class="form-sign_in-checkbox" id="navi-toggle">
            <label for="navi-toggle" class="form-sign_in-button">
                <span class="form-sign_in-icon">Remember Me</span>
            </label>

            <br />
            <br />


            <div class="from-sign_in_btn">

                <input name="submit_sign_in" type="submit" class="btn" value="sign in"/>

            </div>

            <br />


            <div class="form-sign_up_btn">
                <a href="sign_up" class="btn">
                    sign up
                </a>
            </div>

        </fieldset>
    </form>

</div>

@endsection
