<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        

        <link rel="shortcut icon" type="image/png" href="images/favicon.png">


        <title>Test</title>


        <style>
            body{
                background-color = red ;   
            }
        </style>
    
    </head>


    <body>




            <!-- 
            <?php 
            
                if(isset($_GET["subject"])){
                    $selected_subject_id = $_GET["subject"] ;
                    $selected_page_id = null ;

                }else if(isset($_GET["page"])){
                    $selected_page_id = $_GET["page"] ;
                    $selected_subject_id = null ;

                }else{
                    $selected_subject_id = null ;
                    $selected_page_id = null ;

                }
            
            ?>
             -->














        <?php
            //1 .create database connection
            $db_host = "localhost" ;
            $db_user = "root" ;
            $db_pass = "12345678" ;
            $db_name = "my_monitor" ;

            $connection = mysqli_connect($db_host,$db_user,$db_pass,$db_name);

            if(mysqli_connect_errno()){
                die("Database connection failed: "
                    .mysqli_connect_error()
                    ."(" .mysqli_connect_errno() .")"
            
                );
            }
        ?>



        <?php 


            //2. perform database query
            $query = "SELECT * " ;
            $query .="FROM admins " ;
            $query .="ORDER BY id ASC" ;


            $result= mysqli_query($connection , $query) ;

            //test if there was a query error
            if(!$result){
                die("Database query failed.");
            }
        ?>

        <ul>
            <?php
                //3. use returned data (if any)
                while($category = mysqli_fectch_assoc($result)){
                    //output data from each row
            ?>

            <li>
                <?php 
                    echo $category["category_name"]
                ?>
            </li>

            <?php
                }
            ?>
        </ul>

        <?php
            //4. release the returned data
            mysqli_free_result($result);
            
        ?>

        






        <?php
                    //5. close database connection
                    mysqli_close($connection);
                    
        ?>

    </body>

</html>