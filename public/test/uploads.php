<?php
    $upload_errors = array(
	UPLOAD_ERR_OK => "NO errors" ,
	UPLOAD_ERR_INI_SIZE => "Larger than upload_max_filesize" ,
	UPLOAD_ERR_FORM_SIZE => "Larger than form MAX_FILE_SIZE" ,
	UPLOAD_ERR_PARTIAL => "Partial upload" ,
	UPLOAD_ERR_NO_FILE => "No file" ,
	UPLOAD_ERR_NO_TMP_DIR => "No temporary directory" ,
	UPLOAD_ERR_CANT_WRITE => "Cant't write to disk" ,
	UPLOAD_ERR_EXTENSION  => "File upload stopped by extention"
	);


	if(isset($_POST['submit'])){

		$temp_file = $_FILE['file_upload']['temp_name'];
		$target_file = basename($_FILE['file_upload']['name']);
		$upload_dir = "uploads" ;

        if(move_uploaded_file($temp_file , $upload_dir."/".$target_file)){
            $message = "File uploadded";
        }else{
            $error = $_FILES['file_upload']['error'] ;
            $message = $upload_errors[$error] ;
            
        }


        echo "<pre>" ;
        print_r($_FILES['file_upload']);
        echo "</pre>" ;



    }


?>


<html>


    <head>
        <title>file upload</title>
    </head>


    <body>


        <?php
            if(!empty($message)){
                echo $message ;
            }
        ?>

        <form action="uploads.php" method="POST" enctype="multipart/form-data">


           <input type="hidden" name="MAX_FILE_SIZE" value="1000000"> <!-- value 1000000 in bytes must be set before input_file=>file_upload and can't be larger than upload_max_filesize in php.ini -->

            <input type="file" name="file_upload">

            <input type="submit" name="submit"  value="upload">


        </form>






    </body>







</html>
