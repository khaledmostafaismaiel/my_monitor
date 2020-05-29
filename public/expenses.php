<?php require_once("../includes/db_connection.php")?>
<?php require_once("../includes/functions.php")?>
<?php include("../includes/layout/header.php")?>








<div>
    <table class="table-expenses table table-striped table-hover table-responsive-sm">
        
        <thead>
            <tr>
                <th>Price</th>
                <th>Category</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Options</th>
            </tr>
        </thead>

        <tbody>
            <tr class="table-expenses-body-raw">
                <td>5.00</td>
                <td>Drink</td>
                <td>pepsi</td>
                <td>28-May-2020</td>
                <td>
                    <div class="row">
                        <div class="btn-action">
                            <a href="" class="">
                                <i class="" aria-hidden="true"></i>
                            </a>
                        </div>
                        
                        <div class="btn-action">
                            <form action="" method="POST">
                                <input type="checkbox" name="_method" value="DELETE">
                                <input type="checkbox" name="expenseId"value="3">
                                <button type="submit" class="btn-sm btn-danger delete-expense" title="Delete">
                                <i class="" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="table-expenses-body-raw">
                <td>5.00</td>
                <td>Drink</td>
                <td>pepsi</td>
                <td>28-May-2020</td>
                <td>
                    <div class="row">
                        <div class="btn-action">
                            <a href="" class="btn-sm btn-warning">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="btn-action">
                            <form action="" method="POST">
                                <input type="checkbox" name="_method" value="DELETE">
                                <input type="checkbox" name="expenseId"value="3">
                                <button type="submit" class="btn-sm btn-danger delete-expense" title="Delete">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>

            <tr class="table-expenses-body-raw">
                <td>5.00</td>
                <td>Drink</td>
                <td>pepsi</td>
                <td>28-May-2020</td>
                <td>
                    <div class="row">
                        <div class="btn-action">
                            <a href="" class="btn-sm btn-warning">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="btn-action">
                            <form action="" method="POST">
                                <input type="checkbox" name="_method" value="DELETE">
                                <input type="checkbox" name="expenseId"value="3">
                                <button type="submit" class="btn-sm btn-danger delete-expense" title="Delete">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>


            <tr class="table-expenses-body-raw">
                <td>5.00</td>
                <td>Drink</td>
                <td>pepsi</td>
                <td>28-May-2020</td>
                <td>
                    <div class="row">
                        <div class="btn-action">
                            <a href="" class="btn-sm btn-warning">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="btn-action">
                            <form action="" method="POST">
                                <input type="checkbox" name="_method" value="DELETE">
                                <input type="checkbox" name="expenseId"value="3">
                                <button type="submit" class="btn-sm btn-danger delete-expense" title="Delete">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>


            <tr class="table-expenses-body-raw">
                <td>5.00</td>
                <td>Drink</td>
                <td>pepsi</td>
                <td>28-May-2020</td>
                <td>
                    <div class="row">
                        <div class="btn-action">
                            <a href="" class="btn-sm btn-warning">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="btn-action">
                            <form action="" method="POST">
                                <input type="checkbox" name="_method" value="DELETE">
                                <input type="checkbox" name="expenseId"value="3">
                                <button type="submit" class="btn-sm btn-danger delete-expense" title="Delete">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>


            
        </tbody>
    </table>
</div>



<div class="btn-list">
    <?php 
                
        if(isset($_GET["pagenumber"])){
            $page_number = $_GET["pagenumber"] ;
        }else{
            $page_number = 1 ;
        }
    ?>

    <?php 
        $number_of_expenses = 13 ;
        $number_of_expenses_per_page = 5 ;
        if($number_of_expenses <= 5){
            $number_of_pages = 1 ;
        }else if(($number_of_expenses % $number_of_expenses_per_page) == 0){
            $number_of_pages = $number_of_expenses / $number_of_expenses_per_page ;
        }else{
            $number_of_pages = $number_of_expenses / $number_of_expenses_per_page ;
            $number_of_pages += 1 ;
        }
    ?>
    
    <?php
        // if($_GET["pagenumber"] > $number_of_pages){

        //     redirect to expenses.php ;
        // }
    ?>

    <?php
        echo "<a";

        if($page_number > 1){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
            echo  $page_number-1  ;
            echo "\"" ; 
        }else{

            echo " href=\"\"" ;
        }

        echo "class=\"btn-list-back btn\"" ;
        echo ">";
        echo "Back";
        echo "</a> " ;
    ?>

     
        
    

    <span class="btn-list-span"> <?php echo $page_number?> </span>
    
    <?php
        echo "<a";

        if($page_number < ($number_of_pages - 1 )){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
            echo  $page_number+1  ;
            echo "\"" ; 
        }else{

            echo " href=\"\"" ;
        }

        echo "class=\"btn-list-next btn\"" ;
        echo ">";
        echo "Next";
        echo "</a> " ;
    ?>
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