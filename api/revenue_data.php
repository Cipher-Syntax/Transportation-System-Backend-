<?php
    require_once("../database/Connection.php");
    require_once("../database/Database.php");
    require_once("../database/UserRegistration.php");

    $database = new Database();
    $conn = $database->getConnection();
    $driver = new UserRegistration($conn);

    $query = "SELECT DATE(completed_at) AS booking_day, SUM(amount) AS total_revenue 
              FROM rides 
              GROUP BY completed_at 
              ORDER BY completed_at ASC";

    // Prepare the statement and execute it
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];

    foreach ($result as $row) {
        $data[] = [
            "day" => $row['booking_day'],
            "revenue" => $row['total_revenue']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>
