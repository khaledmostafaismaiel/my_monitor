<?php 
    require_once("../includes/initialize.php");

    if(isset($_POST['submit_sign_up'])){
        $user = new User();

        $user->first_name = $_POST['first_name'] ;
        $user->second_name = $_POST['second_name'] ;
        $user->user_name = $_POST['email'] ;
        $user->password = $_POST['password'] ;
        $user->confirm_password = $_POST['confirm_password'] ;
        $user->not_robot = $_POST['not_robot'] ;
        $user->terms_of_conditions = $_POST['terms_of_conditions'] ;


        if($user->check_before_sign_up() && $user->save() ){
            
            //Success
            $_SESSION["message"] = "Success";

            $message = $user->full_name() ;
            try_to_send_mail($message);
            
            Helper::redirect_to("sign_in.php?");
        }else{
            //fail
            $_SESSION["message"] = "Try Again";
            Helper::redirect_to("sign_up.php") ;
        }

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


<?php Helper::include_layout_template("footer.php")?>