<?php
    require_once("../database/Database.php");
    require_once("../database/Connection.php");

    class UserRegistration{
        private $conn;
        private $userTable = "users";
        private $driverTable = "drivers";
        private $adminTable = "admin";

        public function __construct($database){
            $this->conn = $database;
        }

        // USER CREATE ACCOUNT
        public function createUser($firstname, $lastname, $email, $password){
            $query = "INSERT INTO " . $this->userTable . " (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)";
            $stmt = $this->conn->prepare($query);
            $bind_params = [':firstname' => $firstname, ':lastname' => $lastname, ':email' => $email, ':password' => $password];

            return $stmt->execute($bind_params);
        }

        // USER LOGIN
        public function loginUser($email){
            $query = "SELECT * FROM " . $this->userTable . " WHERE email = :email LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $bind_params = [':email' => $email];

            $stmt->execute($bind_params);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
            
        }

        public function getAllUsers(){
            $query = "SELECT * FROM " . $this->userTable;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getUserById($id) {
            $query = "SELECT * FROM " . $this->userTable . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function updateUser($id, $firstname, $lastname, $email, $user_profile) {
            $query = "UPDATE " . $this->userTable . " SET firstname = :firstname, lastname = :lastname, email = :email, user_profile = :user_profile WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $params = [':id' => $id, ':firstname' => $firstname, ':lastname' => $lastname, ':email' => $email, ':user_profile' => $user_profile];
            return $stmt->execute($params);
        }
                
        public function deleteUser($id) {
            $query = "DELETE FROM " . $this->userTable . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$id]);
        }
        

        // CREATE DRIVER
        public function createDriver($firstname, $lastname, $email, $password, $contact_number, $license_number, $driver_notes, $ratings, $driver_profile, $car_seats){
            $query = "INSERT INTO " . $this->driverTable . " (firstname, lastname, email, password, contact_no, license_number, driver_notes, ratings, driver_profile, car_seats) VALUES (:firstname, :lastname, :email, :password, :contact_no, :license_number, :driver_notes, :ratings, :driver_profile, :car_seats)";
            $stmt = $this->conn->prepare($query);
            $bind_params = [':firstname' => $firstname, ':lastname' => $lastname, ':email' => $email, ':password' => $password, ':contact_no' => $contact_number, ':license_number' => $license_number, ':driver_notes' => $driver_notes, ':ratings' => $ratings, ':driver_profile' => $driver_profile, ':car_seats' => $car_seats];

            return $stmt->execute($bind_params);
        }

        // DRIVER LOGIN
        public function loginDriver($email){
            $query = "SELECT * FROM " . $this->driverTable . " WHERE email = :email LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $bind_params = [':email' => $email];

            $stmt->execute($bind_params);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        }

        public function getAllDrivers(){
            $query = "SELECT * FROM " . $this->driverTable;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getDriverById($id) {
            $query = "SELECT * FROM " . $this->driverTable . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        public function updateDriver($id, $firstname, $lastname, $driver_profile){
            $query = "UPDATE " . $this->driverTable . " SET firstname = :firstname, lastname = :lastname, driver_profile = :driver_profile WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $params = ['id' => $id, ':firstname' => $firstname, ':lastname' => $lastname, 'driver_profile' => $driver_profile];
            return $stmt->execute($params);
        }



        public function deleteDriver($id) {
            $query = "DELETE FROM " . $this->driverTable . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$id]);
        }

        // ADMIN CREATE ACCOUNT
        public function adminCreateAccount($firstname, $lastname, $email, $password){
            $query = "INSERT INTO " . $this->adminTable . " (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)";
            $stmt = $this->conn->prepare($query);
            $bind_params = [':firstname' => $firstname, ':lastname' => $lastname, ':email' => $email, ':password' => $password];

            return $stmt->execute($bind_params);
        }

        // ADMIN LOGIN
        public function adminLogin($email){
            $query = "SELECT * FROM " . $this->adminTable . " WHERE email = :email LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $bind_params = [':email' => $email];

            $stmt->execute($bind_params);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result;
            
        }

        public function updateDriverByAdmin($id, $firstname, $lastname, $email, $contact_no, $license_number, $driver_notes, $ratings, $driver_profile, $car_seats) {
            $query = "UPDATE " . $this->driverTable . "SET firstname = :firstname, lastname = :lastname, email = :email, contact_no = :contact_no, license_number = :license_number, driver_notes = :driver_notes, ratings = :ratings, driver_profile = :driver_profile, car_seats = :car_seats WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contact_no', $contact_no);
            $stmt->bindParam(':license_number', $license_number);
            $stmt->bindParam(':driver_notes', $driver_notes);
            $stmt->bindParam(':ratings', $ratings);
            $stmt->bindParam(':driver_profile', $driver_profile);
            $stmt->bindParam(':car_seats', $car_seats);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        }
        

    }
    
    $database = new Database();
    $conn = $database->getConnection();
    $user = new UserRegistration($conn);

    $database = new Database();
    $conn = $database->getConnection();
    $driver = new UserRegistration($conn);

    
    $database = new Database();
    $conn = $database->getConnection();
    $admin = new UserRegistration($conn);
?>