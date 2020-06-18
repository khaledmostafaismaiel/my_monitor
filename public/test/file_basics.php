<?php

    echo __FILE__ ; //path to this file 
    echo "<br />" ;

    echo __LINE__ ; //num of this line
    echo "<br />" ;

    echo dirname(__FILE__); //path to parent folder
    echo "<br />" ;

    echo __DIR__ ; //path to parent folder
    echo "<br />" ;

    echo file_exists(__FILE__) ? "Yes" : "No" ; //path to this file existance
    echo "<br />" ;

    echo file_exists(dirname(__FILE__)) ? "Yes" : "No" ; //path to parent folder existance
    echo "<br />" ;

    echo file_exists(dirname(__FILE__)."/anything.php") ? "Yes" : "No" ; //path to parent folder existance
    echo "<br />" ;

    echo "<br />" ;

    echo is_file(__FILE__) ? "Yes" : "No" ; //is path to file
    echo "<br />" ;

    echo is_file(dirname(__FILE__)."/anything.php") ? "Yes" : "No" ; //is path to file
    echo "<br />" ;

    echo is_dir(__FILE__) ? "Yes" : "No" ; //is path to dir
    echo "<br />" ;

    echo is_dir(dirname(__FILE__)."/anything.php") ? "Yes" : "No" ; //is path to dir
    echo "<br />" ;

    echo is_dir("..") ? "Yes" : "No" ; //is path to dir
    echo "<br />" ;

    $owner_id = fileowner('user.php') ;
    $owner_array = posix_getpwuid($owner_id);
    echo $owner_array["name"]."<br />" ;

    chown('user.php','amr');//it works only with super user


    echo decoct(fileperms('user.php'));
    chmod('user.php',0777);
    echo decoct(fileperms('user.php'));

    echo is_readable('user.php') ?  "Yes" :  "No" ;
    echo is_writable('user.php') ?  "Yes" :  "No" ;



    if($handel = fopen('khaled.txt',w)){
        fwrite($handel,"khaled try to change his career,sooo sad "); //it retun the number of byts if success
        fclose($handel);
    }else{
        echo "couldn't open file";
        
    }


    $content = "123456789";
    if($size = file_put_contents('khaled.txt',$content)){
        fwrite($handel,"khaled try to change his career,sooo sad "); //it retun the number of byts if success
    }else{
        echo "couldn't open file";
        
    }

    $pos = ftell($handel);
    fseek($handel , $pos - 6);
    fwrite($handel,"khaled"); //it retun the number of byts if success

    rewind($handel);//go to the first of the file but take care it will overwrite the content
    fwrite($handel,"amr"); //it retun the number of byts if success

    // unlink('khaled.txt');
    // a and a+ will stop the pointer at the end of the file and will prevent string manipulation



    if($handel = fopen('khaled.txt',r)){
        echo nl2br(fread($handel,5)); //it retun the number of byts if success
        fclose($handel);
    }else{
        echo "couldn't open file";
        
    }

    if($handel = fopen('khaled.txt',r)){
        echo nl2br(fread($handel,filesize('khaled.txt'))); 
        fclose($handel);
    }else{
        echo "couldn't open file";
        
    }


    if($handel = fopen('khaled.txt',r)){
        echo nl2br(file_get_contents('khaled.txt')); 
        fclose($handel);
    }else{
        echo "couldn't open file";
        
    }

    if($handel = fopen('khaled.txt',r)){
        echo nl2br(fgets($handel)); //read 1 line
        fclose($handel);
    }else{
        echo "couldn't open file";
        
    }

    if($handel = fopen('khaled.txt',r)){
        while(!feof($handel)){
            echo nl2br(fgets($handel)); //read 1 line
        }
        fclose($handel);
    }else{
        echo "couldn't open file";
        
    }


    echo "<br />" ;
    echo strtotime("%m/%d/%Y %H:%M" ,filemtime('khaled.txt')); //content changed
    echo "<br />" ;
    echo strtotime("%m/%d/%Y %H:%M" ,filectime('khaled.txt')); //file content change or metadata
    echo "<br />" ;
    echo strtotime("%m/%d/%Y %H:%M" ,fileatime('khaled.txt')); //file accessed to read or any change
    echo "<br />" ;


    $array = pathinfo(__FILE__);

    echo "<br />" ;

    echo $array["dirname"] ;
    echo "<br />" ;

    echo $array["basename"] ;
    echo "<br />" ;

    echo $array["filename"] ;
    echo "<br />" ;

    echo $array["extension"] ;
    echo "<br />" ;



    echo getcwd(); //get current working dir

    mkdir("new_folder",0777);//0777 is the php default //0222 is the umask which will subtract from 0777

    mkdir("new_folder/new_sub_folder/new_sub_to_the_sub_folder",0777,true);

    // chdir("new_folder") ;

    echo getcwd();

    rmdir("new_sub_folder/new_sub_to_the_sub_folder") ; //remove dir read rmdir at php.net




    $dir = ".";
    if(is_dir($dir)){
        if($handel_dir = opendir($dir)){
            while($file_name = readdir($handel_dir)){
                echo $file_name ."<br />";
            }
            closedir($handel_dir);
        }
    }else{
        echo "couldn't open dir";

    }

    $$dir = ".";
    if(is_dir($dir)){
        $dir_array = scandir($dir);
        foreach($dir_array as $file):
            if(stripos($file,'.') > 0){
                echo $file ."<br />";
            }
        endforeach;
    }else{
        echo "couldn't open dir";

    }


?>