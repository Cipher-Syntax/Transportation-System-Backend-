<?php
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");

    require_once("../database/Connection.php");
    require_once("../database/Database.php");
    require_once("../database/UserRegistration.php");

    $database = new Database();
    $conn = $database->getConnection();
    $driver = new UserRegistration($conn);

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        // GET: Get all users
        case 'GET':

            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $driverData = $driver->getDriverById($id);
                if ($driverData) {
                    echo json_encode([
                        "status" => "success",
                        "drivers" => $driverData
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Driver not found"
                    ]);
                }
            } else {
                $drivers = $driver->getAllDrivers();
                echo json_encode([
                    "status" => "success",
                    "drivers" => $drivers
                ]);
            }
            break;

        // POST: Create new user
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Create User
            if (isset($data['firstname'], $data['lastname'], $data['email'], $data['password'], $data['contact_number'], $data['license_number'], $data['driver_notes'], $data['ratings'], $data['driver_profile'], $data['car_seats'])) {
                $result = $driver->createDriver($data['firstname'], $data['lastname'], $data['email'], $data['password'], $data['contact_number'], $data['license_number'], $data['driver_notes'], $data['ratings'], $data['driver_profile'], 4);
                
                if ($result) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "Driver created successfully"
                    ]);
                } 
                else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Driver creation failed"
                    ]);
                }
            } 
            else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Missing required fields"
                ]);
            }
            break;

        // PUT: Update user
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'], $data['firstname'], $data['lastname'], $data['email'],  $data['contact_number'], $data['license_number'], $data['driver_notes'], $data['ratings'], $data['driver_profile'], $data['car_seats'])) {
                $result = $driver->updateDriverByAdmin($data['id'], $data['firstname'], $data['lastname'], $data['email'], $data['contact_number'], $data['license_number'], $data['driver_notes'], $data['ratings'], $data['driver_profile'], 4);
                echo json_encode([
                    "status" => $result ? "success" : "error",
                    "message" => $result ? "Driver updated successfully" : "Failed to update user"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Missing required fields"
                ]);
            }
            break;

        // DELETE: Delete user
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) {
                $result = $driver->deleteDriver($data['id']);
                echo json_encode([
                    "status" => $result ? "success" : "error",
                    "message" => $result ? "Driver deleted successfully" : "Failed to delete user"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Driver ID is required"
                ]);
            }
            break;

        default:
            // Method Not Allowed
            http_response_code(405);
            echo json_encode([
                "status" => "error", 
                "message" => "Method Not Allowed"
            ]);
            break;
    }
?>
