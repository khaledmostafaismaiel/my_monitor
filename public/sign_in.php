<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>


<?php
    if(isset($_POST['submit_sign_in'])){
        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep

        $user_name = mysqli_prep($_POST["user_name"]) ;
        $password = mysqli_prep($_POST["password"]) ;
        $remember_me = $_POST["remember_me"] ;

        if($user_name=="khaledmostafa297@gmail.com" AND $password="01143325016"){
            $result = true ;
        }else{
            $result = false ;
        }

        if($result){
            //success
            // $_SESSION["message"] = "add success" ;
            // redirect_to("index.php?currentpage=home");
        }else{
            //failed
            // $_SESSION["message"] = "add didn't success" ;
            // redirect_to("sign_up.php?currentpage=sign_up");
        }



    }else{
        //this is probably $_GET request
        //i will check if user is active or not
        // if(1){

        // }else{
        //     redirect_to("sign_up.php");
        // }

    }



?>




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
                <input type="text" class="form-sign_in-user_name" name="user_name" value="" placeholder="Your E_mail">

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
                <a href="sign_up.php?currentpage=signup" class="btn">
                    sign up
                </a>
            </div>
            
        </fieldset>
    </form>

</div>



<?php
    //4. release the returned data
    mysqli_free_result($result);          
?>

<?php include("../includes/layout/footer.php")?>

<?php
    //5. close database connection
    mysqli_close($connection);    
?>