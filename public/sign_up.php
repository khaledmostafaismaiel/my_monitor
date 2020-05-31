<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>


<?php
    if(isset($_POST['submit_sign_up'])){
        //prcess the form
        //escape all strings to prevent sql injection with mysqli_prep
        $first_name = mysqli_prep($_POST["first_name"]) ;
        $second_name = mysqli_prep($_POST["second_name"]) ;
        $email = mysqli_prep($_POST["email"]) ;
        $password = mysqli_prep($_POST["password"]) ;
        $confirm_password = mysqli_prep($_POST["confirm_password"]) ;
        $not_robot = $_POST["not_robot"] ;
        $terms_of_conditions = $_POST["terms_of_conditions"] ;


        $query = "INSERT INTO admins (";
        $query .= " first_name,second_name,user_name,hashed_password";
        $query .= ") VALUES (" ;
        $query .= " '{$first_name}','{$second_name}','{$email}','{$password}'";
        // $query .= ",'{$created_at}','{$updated_at}'" ;
        $query .= ")";

        $result = mysqli_query($connection,$query);

        if($result){
            //success
            // $message = "add success" ;
            // redirect_to("index.php?currentpage=home");
        }else{
            //failed
            // $message = "add didn't success" ;
            // redirect_to("sign_up.php?currentpage=sign_up");
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
        <input name="email" type="text"   placeholder="Enter Your E_mail">
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
        <input name="not_robot" type="checkbox">
    </div>





    <div class="form-sign_up-terms_of_conditions">
        <label for="terms_of_conditions" >
            <span>I agree with all terms of conditions.</span>
        </label>
        <input name="terms_of_conditions" type="checkbox">
    </div>


    

    <div class="form-sign_up-submit">
        <input name="submit_sign_up" type="submit" class="btn" value="sign up"/>
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