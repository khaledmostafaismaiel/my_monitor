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


        <!-- <form action="#" name="register" method="POST" >

            <label>User name</label>
            <input type="text" name="username" value="khaled" placeholder="enter your name">

            <label>Password</label>
            <input type="password" name="username" value="khaled" placeholder="enter your password">

            <label>check box</label>
            <input type="checkbox" name="checkbox">

            <input type="submit" value="submit">

            <input type="button" value="say hello"  value="say hello" onclick="">

            <input type="reset" value="restor default">

            <label>upload your cv</label>
            <input type="file">

            <label>image</label>
            <input type="image" src="http://placehold.it//100/100" alt="imag">

            <input type="hidden" value="edit/delete">

            <input type="radio" name="browser" value="chrome">
            <input type="radio" name="browser" value="mozzila">
            <input type="radio" name="browser" value="edge">

            <fieldset>

                <legend>    
                    this is legend
                </legend>

                <label>textarea</label>
                <textarea name="" id="" cols="20" rows="3" readonly>readonly</textarea>

                <label>textarea</label>
                <textarea name="" id="" cols="20" rows="3" >this is our text area</textarea>

            </fieldset>


            <select name="" id=""  size="5" multiple >
                <optgroup label="the first 3 names">
                    <option value="1" disabled>khaled</option>
                    <option value="2" selected>mostafa</option>
                    <option value="3">ismaiel</option>
                </optgroup>

                <option value="4">ahmed</option>
                <option value="5">mohamed</option>
            </select>
        </form> -->


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
            $query .="FROM categories " ;
            $query .="WHERE category_id = 1 " ;


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

<?php
//3. use returned data (if any)
    //output data from each row
    echo $result["first_name"] ;

?>


<?php
    //4. release the returned data
    mysqli_free_result($result);
    
?>

















    </body>

</html>