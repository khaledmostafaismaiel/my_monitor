<?php require_once("../includes/initialize.php")?>


<?php

    $_SESSION["user_id"] = null ;
    $_SESSION["first_name"] = null ;

    redirect_to("sign_in.php");

?>