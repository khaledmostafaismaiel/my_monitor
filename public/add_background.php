<?php 
    require_once("../includes/initialize.php") ;

	$max_file_size = 1048576;   // expressed in bytes
	                            //     10240 =  10 KB
	                            //    102400 = 100 KB
	                            //   1048576 =   1 MB
	                            //  10485760 =  10 MB
	$message = "";
	if(isset($_POST['submit'])) {
		$photo = new Background();
        $photo->caption = $_POST['caption'];
        $photo->user_id = $_SESSION['user_id'];
		if($photo->attach_file($_FILES['file_upload'])){
            if($photo->save()) {
                // Success
                Log::write_in_log("{$_SESSION['user_id']} add background ".date("d-m-Y")." ".date("h:i:sa")."\n");

                $_SESSION["message"] = "uploaded success.";
                redirect_to("index.php?");
            }
        }
        // Failure
        $_SESSION["message"] = "uploaded didn't success.";
        redirect_to("add_background.php?");
	}
	
?>

    <form action="" enctype="multipart/form-data" method="POST">
        <fieldset class="form-add_back_ground">
            <legend> 
                <h2>
                    Photo Upload...
                </h2>   
            </legend>

            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
            
            <p class="form-add_back_ground-choose_file">
                <input type="file" name="file_upload" />
            </p>

            <p  class="form-add_back_ground-caption">
                Caption:<textarea id="" cols="20" name="caption" rows="3" placeholder="Like,place..."></textarea>
            </p>

            <div class="form-add_back_ground-cancel_btn">
                <a href="index.php?" class="btn">
                    cancel
                </a>
            </div>
            <input type="submit" name="submit" value="+ add" class="form-add_back_ground-add_btn btn" />
        
        </fieldset>
    </form>
  
<?php include(LAYOUTS_PATH.DS."footer.php")?>
<?php /* include_layout_template("footer.php")*/ ?>