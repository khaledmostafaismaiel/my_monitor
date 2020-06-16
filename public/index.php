<?php require_once("../includes/initialize.php")?>



<div class="money_spent">
    <p class="money_spent-first_line">
        Hi,<span class = "money_spent-first_line-user_name">
                <?php
                    echo $_SESSION["first_name"];
                ?>
            </span> you spent 
    </p>

    <p class="money_spent-second_line">
        <?php echo Expense::get_all_month_prices()? :0 ?> E£
    </p>
    
    <p class="money_spent-third_line">
        <?php echo date("1/n/Y")?><span class = "money_spent-first_line-user_name"> TO </span><?php echo date("j/n/Y",strtotime("today")) ?>
    </p>                
</div>




<?php include(LAYOUTS_PATH.DS."footer.php")?>
<?php /* include_layout_template("footer.php")*/ ?>