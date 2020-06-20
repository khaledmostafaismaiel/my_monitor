<?php 

    require_once("database.php");
    require_once("pagination.php");
    require_once("helper.php");


class Expense extends Database_object{ 

    protected static $table_name = "expenses";
    protected static $db_fields = array("id","user_id","expense_name","price",
        "category","comment","created_at");

    public $id ;
    public $user_id ;
    public $expense_name ;
    public $price ;
    public $category ;
    public $comment ;
	public $created_at ;


 
    public static function get_all_month_expenses(){
        global $database;
        
        $month = date('Y-m-00');

        $query = "SELECT * FROM ".self::$table_name." WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']}  ORDER BY id DESC";
        $result_set = mysqli_query($database->get_connection() , $query);
    
        //test if there was a query error
        $database->confirm_query($result_set);
        
        return $result_set;
    }


    public static function get_all_month_prices(){

        global $database;


        $month = date('Y-m-00');
        $query = "SELECT SUM(`price`) AS 'total' FROM ".self::$table_name." WHERE `created_at` > '{$month}' AND user_id = {$_SESSION['user_id']} ";
        $result_set = mysqli_query($database->get_connection() , $query);
        
        //test if there was a query error
        $database->confirm_query($result_set);
        
        return $monthTotal = mysqli_fetch_object($result_set)->total;
    }

    
	/*<------------------------------------------------------------------------------>*/
	
	
	// Common Database Methods


		
	// public static function get_from_url($string){

	// 	if(isset($_GET[$string])){
	// 		$value = htmlentities($_GET[$string]) ;
	// 	}else{
	// 		$value = null;
	// 	}

	// 	return $value ;
	// }




    public function check_before_save(){
		global $database ;

		if($this->expense_name != null){
			$this->expense_name = strtolower($database->sql_sanitize($this->expense_name));

		}else{
			return false ;
		}

		if($this->price != null){
			$this->price = (float)$database->sql_sanitize($this->price);

		}else{
			return false ;

		}

		if($this->category != null){
			$this->category = $database->sql_sanitize($this->category);

		}else{
			return false ;

		}

		if($this->comment != null){
			$this->comment = strtolower($database->sql_sanitize($this->comment));
	
		}else{

		}
		
        if($this->created_at != null){
            $this->created_at = sql_sanitize($this->created_at) ;

        }else{
            $this->created_at = date("Y-m-d H:i:s") ;

		}

        return  true  ; 
    }

}
/*

	htmlentities()
	urlencode()  //
	escape_spitial_char()




*/