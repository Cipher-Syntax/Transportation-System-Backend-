<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);
    $ride_id = $data['ride_id'] ?? '';
    $action = $data['action'] ?? '';

    if (!$ride_id || !$action) {
        echo json_encode(['status' => 'error', 'message' => 'Missing ride ID or action']);
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();
    $driver = checkDriverLogin($conn);
    $driver_id = $driver['id'];

    // Check quota for accept action
    if ($action === 'accept') {
        $quota_query = "SELECT COUNT(*) FROM rides WHERE driver = :driver_id AND status = 'Completed' AND DATE(completed_at) = CURDATE()";
        $stmt = $conn->prepare($quota_query);
        $stmt->execute([':driver_id' => $driver_id]);
        $today_completed_rides = $stmt->fetchColumn();
        $quota_limit = 10;

        if ($today_completed_rides >= $quota_limit) {
            echo json_encode(['status' => 'error', 'message' => "Today's quota has already been reached. You cannot accept more rides."]);
            exit;
        }
    }

    switch ($action) {
        case 'accept':
            $check = $conn->prepare("SELECT COUNT(*) FROM rides WHERE driver = ? AND status = 'Active'");
            $check->execute([$driver_id]);
            if ($check->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'You already have an active ride.']);
                exit;
            }
            $stmt = $conn->prepare("UPDATE rides SET status = 'Active' WHERE ride_id = ? AND driver = ?");
            $result = $stmt->execute([$ride_id, $driver_id]);
            
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Ride accepted.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update ride status.']);
            }
            break;

        case 'decline':
            $stmt = $conn->prepare("UPDATE rides SET status = 'Cancelled' WHERE ride_id = ? AND driver = ?");
            $result = $stmt->execute([$ride_id, $driver_id]);
            
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Ride declined.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update ride status.']);
            }
            break;

        case 'confirm_payment':
            $stmt = $conn->prepare("UPDATE rides SET status = 'Completed', completed_at = NOW() WHERE ride_id = ? AND driver = ? AND status = 'Active'");
            $result = $stmt->execute([$ride_id, $driver_id]);
            
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Payment confirmed.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to confirm payment.']);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
    
?>