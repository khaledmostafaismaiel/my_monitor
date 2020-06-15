<?php
    require_once("database.php");

    class Database_object
    {


        //common methods for classes


        public function save(){

            return isset($this->id) ? $this->update() : $this->create() ;
        }


    }
