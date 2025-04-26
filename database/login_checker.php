<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    function checkUserLogin($conn) {
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data) {
                return $user_data;
            }
        }
        header("Location: login.php");
        exit;
    }

    function checkDriverLogin($conn) {
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            $query = "SELECT * FROM drivers WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $driver_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($driver_data) {
                return $driver_data;
            }
        }
        header("Location: driver_login.php");
        exit;
    }

    function adminCheckLogin($conn) {
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            $query = "SELECT * FROM admin WHERE email = :email LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $driver_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($driver_data) {
                return $driver_data;
            }
        }
        header("Location: admin_login.php");
        exit;
    }
?>
