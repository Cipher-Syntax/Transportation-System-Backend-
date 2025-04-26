<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    include("../database/Database.php");
    include("../database/Connection.php");

    $database = new Database();
    $db = $database -> getConnection();

    $test = new Connection($db);

    $response = $test -> checkConnection();

    echo json_encode($response);
?>