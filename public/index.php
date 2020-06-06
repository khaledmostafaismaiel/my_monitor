<?php require_once("../includes/session.php")?>
<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php confirm_sign_in()?>
<?php include("../includes/layout/header.php")?>

<?php
    global $connection ;
    $month = date('00-m-Y');
    $query = "SELECT SUM(`price`) AS 'total' FROM `expenses` WHERE `created_at` > '{$month}'";
    $sumQuery = mysqli_query($connection, $query);
    $monthTotal = mysqli_fetch_object($sumQuery)->total;
?>


<div class="money_spent">
    <p class="money_spent-first_line">
        Hi,<span class = "money_spent-first_line-user_name">
                <?php 
                    echo $_SESSION["first_name"];
                ?>
            </span> you spent 
    </p>

    <p class="money_spent-second_line">
        <?php echo $monthTotal?> E£
    </p>
    
    <p class="money_spent-third_line">
        <?php echo date("1/n/Y")?><span class = "money_spent-first_line-user_name"> TO </span><?php echo date("j/n/Y",strtotime("today")) ?>
    </p>                
</div>




<?php include("../includes/layout/footer.php")?>