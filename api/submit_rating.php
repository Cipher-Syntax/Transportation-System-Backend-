<?php
    require_once("../database/Database.php");
    require_once("../database/Connection.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $rating = $_POST["rating"];
        $passenger = $_POST["passenger_id"];
        $driver = $_POST["driver_id"];

        if (!empty($rating) && !empty($passenger) && !empty($driver)) {
            $db = new Database();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("INSERT INTO ratings (ratings, passenger, driver) VALUES (?, ?, ?)");
            $stmt->execute([$rating, $passenger, $driver]);
            
            
            echo "success";
        } else {
            echo "Missing fields.";
        }
    }
?>
