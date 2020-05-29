<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>








<h1 class="welcome_message">
    Welcome to My Monitor
</h1>


<div class="form">

    
    <form action= "index.php"  method="post">


        <fieldset class="form-sign_in">
            <legend> 
                <h2>
                    Please, Sign in ...
                </h2>   
            </legend>
            
            <p>

                User Name:
                <input type="text" class="form-sign_in-user_name" name="user name" value="" placeholder="Your E_mail">

            </p>
            
            <br />

            <p>
                Password: &nbsp;
                <input type="password" class="form-sign_in-password" name="password" value="" placeholder="Your Password">
            </p>

            <br />
            
            <input type="checkbox" class="form-sign_in-checkbox" id="navi-toggle">
            <label for="navi-toggle" class="form-sign_in-button">
                <span class="form-sign_in-icon">Remember Me</span>
            </label>

            <br />
            <br />


            <div class="from-sign_in_btn">

                <input type="submit" class="btn" value="sign in"/>

            </div>

            <br />


            <div class="form-sign_up_btn">
                <a href="sign_up.php" class="btn">
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