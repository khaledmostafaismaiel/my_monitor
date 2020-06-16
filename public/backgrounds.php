<?php 
    require_once("../includes/initialize.php");
    
    global $database;

    $background_set = Background::find_all();
    $number_of_backgrounds = $database->num_rows($background_set)  ;
    $number_of_backgrounds_per_page = 3 ;
    $number_of_pages= ceil((float)$number_of_backgrounds/(float)$number_of_backgrounds_per_page);

    $page_number = Helper::get_from_url("pagenumber") ;
    if(($page_number > $number_of_pages) || ($page_number < 1)){
        if($number_of_pages != 0){ 
            Helper::redirect_to("not_available.php");
        }
    }

    $pagination = new Pagination($page_number,$number_of_backgrounds_per_page,$number_of_backgrounds);
?>

<div>
    <table class="table-expenses table table-striped table-hover table-responsive-sm">
        
        <thead>
            <tr>
                <th>Image</th>
                <th>Filename</th>
                <th>Caption</th>
                <th>Size</th>
                <th>Type</th>
                <th>Options</th>
            </tr>
        </thead>

        <tbody>



            <?php
                
                $iteration_number = 0 ;
                $iteration_number_to_escape = 0 ;

                if($background_set != null){ 
                    foreach( $background_set as $background ):

                    if($iteration_number_to_escape < ( ($page_number - 1) *$number_of_backgrounds_per_page)){
                        ++$iteration_number_to_escape ;
                        continue ;
                    }
                    if($iteration_number == $number_of_backgrounds_per_page){
                        break ;
                    }else{
                        ++$iteration_number ;
                    }
                        
            ?>

                    <tr class="table-expenses-body-raw">

                        <td><img src="<?php echo "../uploads/".$background->file_name /*$background->get_uploads_path()*/?>" width="100" alt="<?php echo$background->file_name?>"></td>
                        <td><?php echo $background->file_name?></td>
                        <td><?php echo $background->caption?></td>
                        <td><?php echo $background->get_size_text()?></td>
                        <td><?php echo $background->type?></td>
                        
                        <td>
                            <div class="btn-action">
                                    <a class= "btn-action-edit" href="set_background.php?id=<?php echo $background->id?>"  value="set">
                                            <img src="images/set.png" class="btn-action-edit-image" alt="set"></a>
                                    <a class= "btn-action-delete" href="delete_background.php?id=<?php echo $background->id?>"  value="delete" onclick="return confirm('Are you sure?');">
                                        <img src="images/delete.png" class="btn-action-delete-image" alt="delete"></a>
                                    </a>
                            </div>
                        </td>
                    </tr>

                <?php 
                    endforeach ;
                }
                ?>
            
        </tbody>
    </table>
</div>


<div class="btn-list">

    <?php
        echo "<a";
        if($pagination->has_prev_page()){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
            echo  "{$pagination->prev_page()}"  ;
            echo "\"" ; 
        }else{
            echo " href=\"?pagenumber={$pagination->current_page()}\"" ;
        }
        echo "class=\"btn-list-back btn\"" ;
        echo ">";
        echo "Back";
        echo "</a> " ;
    ?>

    <span class="btn-list-page_number">
        <?php 
            for($i=1;$i <= $pagination->total_pages();$i++){ 
                if($i == $pagination->current_page()){
                    echo "<span class=\"btn-list-page_number-selected\">{$i}</span>" ;
                }else{
                    echo "<a href=\"?pagenumber={$i}\"  class=\"btn-list-page_number-link\">{$i}</a>" ;
                }
            }
        ?>
    </span>
    
    
    <?php
        echo "<a";
        if($pagination->has_next_page()){
            echo " href=\"" ;
            echo " ?pagenumber=" ;
            echo  "{$pagination->next_page()}"  ;
            echo "\"" ; 
        }else{
            echo " href=\"?pagenumber={$pagination->current_page()}\"" ;
        }
        echo "class=\"btn-list-next btn\"" ;
        echo ">";
        echo "Next";
        echo "</a> " ;
    ?>

</div>

<?php include(LAYOUTS_PATH.DS."footer.php")?>
<?php /* include_layout_template("footer.php")*/ ?>