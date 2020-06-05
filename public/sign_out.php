<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>


<?php

    $_SESSION["user_id"] = null ;
    $_SESSION["first_name"] = null ;

    redirect_to("sign_in.php");

?>