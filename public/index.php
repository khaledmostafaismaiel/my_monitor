<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>





<div class="money_spent">
    
          


    <p class="money_spent-first_line">
        Hi,<span class = "money_spent-first_line-user_name">
  

        <?php
            echo "khaled" ;
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