<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>







<form  action= "index.php" method="post">

<fieldset class="form-sign_up">
    <legend> 
        <h2>
            Please, sign up first...
        </h2>   
    </legend>




    <div class="form-sign_up-first_name">
        <label for="first_name" >
            First Name:
        </label>
        <input id="first_name" type="text" value=""  placeholder="Enter Your First Name">
    </div>
    



    <div class="form-sign_up-second_name">
        <label for="second_name" >
            Second Name:
        </label>
        <input id = "second_name" type="text"  value=""  placeholder="Enter Your Second Name">

    </div>





    <div class="form-sign_up-email">
        <label for="email" >
                Email:        
        </label>
        <input id="email" type="text" name="email" value=""  placeholder="Enter Your E_mail">
    </div>





    <div class="form-sign_up-password">
        <label for="password" >
            Password:
        </label>
        <input id="password" type="password"  name="password" value=""  placeholder="Enter Your Password">
    </div>

    

    <div class="form-sign_up-confirm_password">
        <label for="confirm_password" >
            Confirm Password:
        </label>
        <input id="confirm_password" type="password"  name="password" value="" placeholder="Confirm Your Password">
    </div>



    <div class="form-sign_up-not_robot">
        <label for="not_robot" >
            I'm not robot.
        </label>
        <input id="not_robot" type="checkbox">
    </div>





    <div class="form-sign_up-terms_of_conditions">
        <label for="terms_of_conditions" >
            <span>I agree with all terms of conditions.</span>
        </label>
        <input id="terms_of_conditions" type="checkbox">
    </div>


    

    <div class="form-sign_up-submit">
        <input type="submit" class="btn" value="sign up"/>
    </div>  


</fieldset>
</form>


<?php
    //4. release the returned data
    mysqli_free_result($result);     
?>

<?php include("../includes/layout/footer.php")?>

<?php
    //5. close database connection
    mysqli_close($connection);
?>