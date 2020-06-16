<?php 
    require_once("../includes/initialize.php");

    if(isset($_POST['submit_sign_up'])){
        user::check_before_sign_up("first_name","second_name",
            "email","password","confirm_password","not_robot","terms_of_conditions");
        redirect_to("sign_in.php");
    }else{
        //this is probably $_GET request
        //i will check if user is active or not

    }
?>




<form  method="post">

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
            <input name="first_name" type="text" value=""  placeholder="Enter Your First Name">
        </div>
        

        <div class="form-sign_up-second_name">
            <label for="second_name" >
                Second Name:
            </label>
            <input name = "second_name" type="text"  value=""  placeholder="Enter Your Second Name">

        </div>


        <div class="form-sign_up-email">
            <label for="email" >
                    Email:        
            </label>
            <input name="email" type="text" value=""   placeholder="Enter Your E_mail">
        </div>


        <div class="form-sign_up-password">
            <label for="password" >
                Password:
            </label>
            <input name="password"  type="password"   value=""  placeholder="Enter Your Password">
        </div>

        
        <div class="form-sign_up-confirm_password">
            <label for="confirm_password" >
                Confirm Password:
            </label>
            <input name="confirm_password" type="password"   value="" placeholder="Confirm Your Password">
        </div>


        <div class="form-sign_up-not_robot">
            <label for="not_robot" >
                I'm not robot.
            </label>
            <input name="not_robot" value="1" type="checkbox">
        </div>


        <div class="form-sign_up-terms_of_conditions">
            <label for="terms_of_conditions" >
                <span>I agree with all terms of conditions.</span>
            </label>
            <input name="terms_of_conditions" value= "1"  type="checkbox">
        </div>


        <div class="form-sign_up-submit">
            <input name="submit_sign_up" type="submit" class="btn" value="sign up"/>
        </div>  


    </fieldset>
</form>


<?php include(LAYOUTS_PATH.DS."footer.php")?>
<?php /* include_layout_template("footer.php")*/ ?>