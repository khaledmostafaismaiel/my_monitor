<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php confirm_sign_in()?>
<?php include("../includes/layout/header.php")?>


<div class="money_spent">
    <p class="money_spent-first_line">
        Hi,<span class = "money_spent-first_line-user_name">
                <?php 
                    echo $_SESSION["first_name"];
                ?>
            </span> you spent 
    </p>

    <p class="money_spent-second_line">
        <?php echo get_all_month_prices()?> E£
    </p>
    
    <p class="money_spent-third_line">
        <?php echo date("1/n/Y")?><span class = "money_spent-first_line-user_name"> TO </span><?php echo date("j/n/Y",strtotime("today")) ?>
    </p>                
</div>




<?php include("../includes/layout/footer.php")?>