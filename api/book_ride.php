<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");

    $database = new Database();
    $conn = $database->getConnection();

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        $location = $data['location'] ?? '';
        $destination = $data['destination'] ?? '';
        $contact = $data['contact'] ?? '';
        $pickup = $data['pickup'] ?? '';
        $price = $data['price'] ?? 0;

        $_SESSION['location'] = $location;
        $_SESSION['destination'] = $destination;
        $_SESSION['contact'] = $contact;
        $_SESSION['pickup'] = $pickup;
        $_SESSION['price'] = $price;

        echo json_encode(["status" => "success", "redirect" => "../pages/choose_rider.php"]);
        exit;
    }
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
?>