<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>





<div class="money_spent">
    <p class="money_spent-first_line">
        Hi,<span class = "money_spent-first_line-user_name">
                <?php 
                    $admin_data = get_admin_data_by_id(1);
                    echo $admin_data["first_name"];
                ?>
            </span> you spent 
    </p>

    <p class="money_spent-second_line">
        100 E£
    </p>
    
    <p class="money_spent-third_line">
        1-1-2020 &nbsp; TO &nbsp; 1-2-2020
    </p>                
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