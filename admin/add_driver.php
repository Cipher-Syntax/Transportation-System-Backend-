<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");
    require_once("../database/UserRegistration.php");

    $database = new Database();
    $conn = $database->getConnection();
    $driver_data = adminCheckLogin($conn);

    if($_SERVER['REQUEST_METHOD']){
        if(isset($_POST['add'])){
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $contactNo = $_POST['contact'];
            $licenseNumber = $_POST['license-number'];
            $driverNotes = $_POST['driver-note'];
            $driverRating = $_POST['driver-rating'];
            $driverProfile = "";
            $carSeats = 4;
            
            
            try{
                if(!empty($firstname) || !empty($lastname) || !empty($email) || !empty($password) || !empty($contactNo) || !empty($licenseNumber) || !empty($driverNotes) || !empty($driverRating)){
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                    $result = $driver->createDriver($firstname, $lastname, $email, $hashed_password, $contactNo, $licenseNumber, $driverNotes, $driverRating . "★", $driverProfile, $carSeats);

                    if($result){
                        echo "<script>alert('Successfully added a driver');</script>";
                    }
                    else{
                        echo "<script>alert('Failed to add driver');</script>";
                    }

                }
                else{
                    echo "<script>alert('Please fill in all fields');</script>";
                }
            }
            catch(PDOException $e){
                echo "Error: Something went wrong " . $e->getMessage();
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Management</title>
    <link rel="stylesheet" href="../assets/css/add_driver.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h1 class="logo">Gomove Admin</h1>

            <div class="items">
                <i class='bx bxs-dashboard'></i>
                <a href="../admin/admin_dashboard.php">Dashboard</a>
            </div>

            <div class="items">
                <i class='bx bxs-car-wash'></i>
                <a href="../admin/admin_rides.php">Rides</a>
            </div>

            <div class="items">
                <i class='bx bxs-car' ></i>
                <a href="../admin/admin_driver_management.php">Drivers</a>
            </div>

            <div class="items">
                <i class='bx bx-cog' ></i>
                <a href="../admin/admin_settings.php">Settings</a>
            </div>
            
        </div>


        <div class="dashboard-fields">
            <div class="dashboard-header">
                <h1 class="dashboard-text">Add Driver</h1>
            </div>

            <form action="" method="POST">
                <div class="driver-details">
                    <div class="information">
                        <div class="personal-information">
                            <h4 class="information-logo">Personal Information</h4>

                            <div class="firstname">
                                <label for="firstname">Firstname</label>
                                <input type="text" name="firstname" value="" id="firstname" >
                            </div>

                            <div class="lastname">
                                <label for="lastname">Lastname</label>
                                <input type="text" name="lastname" value="" id="lastname" >
                            </div>

                            <div class="email">
                                <label for="email">Email</label>
                                <input type="text" name="email" value="" id="email" >
                            </div>

                            <div class="password">
                                <label for="password">Password</label>
                                <input type="text" name="password" value="" id="password" >
                            </div>
                        </div>

                        <div class="driver-information">
                            <h4 class="information-logo">Driver Information</h4>

                            <div class="driver-license">
                                <label for="license-number">Driver License</label>
                                <input type="text" name="license-number" value="" id="license-number" >
                            </div>

                            <div class="contact">
                                <label for="contact">Contact</label>
                                <input type="text" name="contact" value="" id="contact" >
                            </div>

                            <div class="driver-note">
                                <label for="driver-note">Driver Note</label>
                                <input type="text" name="driver-note" value="" id="driver-note" >
                            </div>

                            <div class="driver-rating">
                                <label for="driver-rating">Driver Rating</label>
                                <input type="text" name="driver-rating" value="" id="driver-rating" >
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="add" name="add">Add Driver</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.getElementById('back').addEventListener('click', () => {
            window.location.href = "../admin/admin_driver_management.php";
            
        });

        document.getElementById('edit').addEventListener('click', () => {
            let popup = document.getElementById('popup-edit-modal');
            popup.style.display = "block";
            
        });

        document.getElementById('cancel').addEventListener('click', () => {
            let cancel = document.getElementById('popup-edit-modal');

            cancel.style.display = "none";
        });
    </script>

    <script>
        document.getElementById("fileInput").addEventListener("change", function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("driver-profile").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>
</html>