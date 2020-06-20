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

    $sql = "SELECT * FROM backgrounds " ;
    $sql .= " WHERE user_id = {$_SESSION['user_id']}  ORDER BY id DESC";
    $sql .= " LIMIT ".$pagination->per_page ;
    $sql .= " OFFSET ".$pagination->offset() ;
    
    $background_set  = Background::find_by_sql($sql);

?>

<div>
    <table class="table-backgrounds table table-hover">
        
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

        <tbody class="table-backgrounds-body">



            <?php

                if($background_set != null){ 
                    foreach( $background_set as $background ):

            ?>

                    <tr class="table-backgrounds-body-raw">

                        <td class="table-backgrounds-td"><img src="<?php echo "../uploads/".$database->html_sanitize($background->file_name) /*$background->get_uploads_path()*/?>/jpg;base64" width="80" alt="<?php echo $database->html_sanitize($background->file_name)?>"></td>
                        <td class="table-backgrounds-td"><?php echo $database->html_sanitize($background->file_name)?></td>
                        <td class="table-backgrounds-td"><?php echo $database->html_sanitize($background->caption)?></td>
                        <td class="table-backgrounds-td"><?php echo $database->html_sanitize($background->get_size_text())?></td>
                        <td class="table-backgrounds-td"><?php echo $database->html_sanitize($background->type)?></td>
                        
                        <td class="table-backgrounds-td">
                            <div class="btn-action">
                                    <a class= "btn-action-edit" href="set_background.php?id=<?php echo $database->encode_url($background->id)?>"  value="set">
                                            <img src="images/set.png" class="btn-action-edit-image" alt="set"></a>
                                    <a class= "btn-action-delete" href="delete_background.php?id=<?php echo $database->encode_url($background->id)?>"  value="delete" onclick="return confirm('Are you sure?');">
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

<!-- Pagination -->
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

<?php Helper::include_layout_template("footer.php")?>