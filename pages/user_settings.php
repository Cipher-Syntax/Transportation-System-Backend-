<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");
    require_once("../database/UserRegistration.php");

    $database = new Database();
    $conn = $database->getConnection();
    $user_data = checkUserLogin($conn);

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(isset($_POST['delete'])){
            $user_id = $user_data['id'];
            

            try{
                if(!empty($user_id)){
                    $result = $user->deleteUser($user_id);

                    if($result){
                        echo "<script>alert('Account deleted successfully');</script>";
                    }
                    else{
                        echo "<script>alert('Failed to delete account');</script>";
                    }
                }
                else{
                    echo "<script>alert('Empty Id.');</script>";
                }
            }
            catch(PDOException $e){
                echo "Error: Something went wrong" . $e->getMessage();
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../assets/css/user_settings.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css" />

    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' >

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>
    <?php include('../includes/user_header.php'); ?>


    <form action="" method="POST">
        <div class="settings-container">
            <h2> Settings </h2>
            <div class="settings-option">
                <button id="auto-update"><i class="fa fa-refresh"></i> Auto-Update Settings </button>
                <button id="data-usage"><i class="fa fa-signal"></i> Data Usage Settings </button>
                <button id="data-usage" name="delete" onclick=" return confirmDelete()"><i class='bx bx-trash'></i> Delete Account </button>
            </div>

            <h2> Help and Support </h2>
            <div class="settings-option">
                <button id="faq"><i class="fa fa-question-circle"></i> FAQs </button>
                <button id="contact-support"><i class="fa fa-envelope"></i> Contact Support </button>
                <button id="report"><i class="fa fa-exclamation-triangle"></i> Report a Problem </button>
            </div>
        </div>
    </form>

    <footer>
        <p>&copy; 2025 GoMove</p>
        <a href="../pages/about_us.php" class="about">About Us</a>
    </footer>

    <script src="../assets/js/user_settings.js"></script>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this account?");
        }
    </script>
</body>
</html>