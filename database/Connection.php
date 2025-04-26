<?php
    class Connection{
        private $conn;

        public function __construct($db){
            $this -> conn = $db;
        }

        public function checkConnection(){
            if($this -> conn){
                return ["status" => "success", "message" => "Database connected successfully"];
            }
            else{
                return ["status" => "error", "message" => "failed to connect to database"];
            }
        }
    }
?>