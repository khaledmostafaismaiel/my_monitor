<?php 

    require_once("database.php");


    class Pagination{

        public $current_page ;
        public $per_page ;
        public $total_set ;
        public $number_of_numbers_per_side = 1;
        public $max_limit;
        public $min_limit;

        public function __construct($current_page = 1 ,$per_page = 6 ,$total_set = 0){
            $this->current_page = (int)$current_page ;
            $this->per_page = (int)$per_page ;
            $this->total_set = (int)$total_set ;

            $this->limits();
        }

        public function total_pages(){
            return ceil($this->total_set / $this->per_page);
        }


        public function current_page(){
            return $this->current_page;
        }

        public function prev_page(){
            return $this->current_page - 1;
        }

        public function next_page(){
            return $this->current_page + 1;
        }

        public function has_prev_page(){
            return $this->prev_page() >= 1 ? true : false;
        }

        public function has_next_page(){
            return $this->next_page() <= $this->total_pages() ? true : false;
        }

        public function offset(){
            return ($this->current_page - 1) * $this->per_page ;
        }

        private function limits(){
            if($this->current_page() <= $this->number_of_numbers_per_side){
                $this->min_limit = 1 ;
            }else{
                $this->min_limit = $this->current_page() - $this->number_of_numbers_per_side ;
            }

            if(( $this->total_pages() - $this->number_of_numbers_per_side ) < $this->current_page()){
                $this->max_limit = $this->total_pages();
            }else{
                $this->max_limit = $this->current_page() + $this->number_of_numbers_per_side;
            }
        }

    }