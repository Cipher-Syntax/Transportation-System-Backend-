<?php
    class Database {
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $db_name = "transportation_system";
        public $conn;
        
        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=" . $this -> servername . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $exception) {
                http_response_code(500);
                echo json_encode(["message" => "Database connection error.", "error" => $exception->getMessage()]);
                exit;
            }
            return $this->conn;
        }
    }
?>