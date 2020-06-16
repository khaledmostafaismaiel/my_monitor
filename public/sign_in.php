<?php 
    require_once("../includes/initialize.php");

    $user_name = "" ;
    
    if(isset($_POST['submit_sign_in'])){
        //prcess the form
        //escape all strings to prevent sql injection with escaped_value
        $user_name = strtolower($database->escaped_value($_POST["user_name"])) ;
        $password = $database->escaped_value($_POST["password"]) ;
        $remember_me = $_POST["remember_me"] ;

        
        if(user::check_before_sign_in("user_name","password","remember_me")){
            //success
            redirect_to("index.php?");
        }else{
            //failed
             redirect_to("sign_in.php?");
        }

    }else{
        //this is probably $_GET request
        //i will check if user is active or not

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
                <input type="text" class="form-sign_in-user_name" name="user_name" value="<?php echo $user_name?>" placeholder="Your E_mail">

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
                <a href="sign_up.php?" class="btn">
                    sign up
                </a>
            </div>
            
        </fieldset>
    </form>

</div>

<?php include(LAYOUTS_PATH.DS."footer.php")?>
<?php /* include_layout_template("footer.php") */?>