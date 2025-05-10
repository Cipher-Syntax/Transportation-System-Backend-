<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");
    require_once("../database/UserRegistration.php");


    $driver_id = isset($_GET['driver_id']) ? (int)$_GET['driver_id'] : 0;

    if ($driver_id > 0) {
        $query = "SELECT * FROM drivers WHERE id = :driver_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':driver_id', $driver_id, PDO::PARAM_INT);
        $stmt->execute();
        $driver_details = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else {
        echo "Driver not found!";
        exit;
    }
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['update'])){
            $driver_id = $driver_details['id'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $contact = $_POST['contact'];
            $license_number = $_POST['license-number'];
            $driver_notes = $_POST['driver-note'];
            $driver_ratings = str_replace('★', '', $_POST['driver-rating']);
            $driver_profile = $driver_details['driver_profile'];
            $car_seats = 4;
    
            try {
                $updateQuery = "UPDATE drivers SET 
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    contact_no = :contact,
                    license_number = :license,
                    driver_notes = :notes,
                    ratings = :ratings,
                    driver_profile = :profile,
                    car_seats = :seats
                    WHERE id = :id";
                    
                $stmt = $conn->prepare($updateQuery);
                
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':contact', $contact);
                $stmt->bindParam(':license', $license_number);
                $stmt->bindParam(':notes', $driver_notes);
                $stmt->bindParam(':ratings', $driver_ratings);
                $stmt->bindParam(':profile', $driver_profile);
                $stmt->bindParam(':seats', $car_seats);
                $stmt->bindParam(':id', $driver_id);
                
                $stmt->execute();
            
                if ($stmt->rowCount() > 0) {
                    echo "<script>
                            alert('Updated Successfully!');
                            window.location.reload();
                         </script>";
                    
                }
            } catch (PDOException $e) {
                echo "<script>alert('Database error: " . htmlspecialchars($e->getMessage()) . "');</script>";
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
    <link rel="stylesheet" href="../assets/css/admin_action.css">
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

            <div class="items">
                <i class='bx bx-log-out'></i>
                <a href="../pages/logout.php?redirect=../admin/admin_login.php">Logout</a>
            </div>
        </div>


        <div class="dashboard-fields">
            <div class="dashboard-header">
                <h1 class="dashboard-text">Driver Details</h1>

                <div class="btn-links">
                    <button class="back" id="back">Back</button>
                    <button class="edit" id="edit">Edit</button>
                </div>
            </div>

            <div class="driver-details">
                <div class="driver-profile">
                    <img src="<?php echo $driver_details['driver_profile']; ?>" alt="driver-profile" id="driver-profile">
                    <div class="driver-info">
                        <p class="driver-name"><?php echo $driver_details['firstname'] . ' ' . $driver_details['lastname']; ?></p>
                        <div class="driver-ratings">
                            <p class="driver-id"><?php echo "Driver Id: " . $driver_details['id'] ?></p>
                            <p class="rating"><?php echo $driver_details['ratings']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="information">
                    <div class="personal-information">
                        <h4 class="information-logo">Personal Information</h4>

                        <div class="fullname">
                            <label for="fullname">Fullname</label>
                            <input type="text" name="fullname" value="<?php echo $driver_details['firstname'] . ' ' . $driver_details['lastname']; ?>" id="fullname" readonly>
                        </div>

                        <div class="email">
                            <label for="email">Email</label>
                            <input type="text" name="email" value="<?php echo $driver_details['email']; ?>" id="email" readonly>
                        </div>

                        <div class="contact">
                            <label for="email">Contact #</label>
                            <input type="text" name="contact" value="<?php echo $driver_details['contact_no']; ?>" id="contact" readonly>
                        </div>
                    </div>
                    <div class="driver-information">
                        <h4 class="information-logo">Driver Information</h4>

                        <div class="driver-license">
                            <label for="license-number">Driver License</label>
                            <input type="text" name="license-number" value="<?php echo $driver_details['license_number']; ?>" id="license-number" readonly>
                        </div>

                        <div class="driver-note">
                            <label for="driver-note">Driver Note</label>
                            <input type="text" name="driver-note" value="<?php echo $driver_details['driver_notes']; ?>" id="driver-note" readonly>
                        </div>

                        <div class="driver-rating">
                            <label for="driver-rating">Driver Rating</label>
                            <input type="text" name="driver-rating" value="<?php echo $driver_details['ratings']; ?>" id="driver-rating" readonly>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="popup-edit-modal" id="popup-edit-modal">
            <div class="popup-container">
                <div class="information">
                    <div class="personal-information">
                        <h4 class="information-logo">Personal Information</h4>

                        <div class="fullname">
                            <label for="fullname">Fullname</label>
                            <div class="name">
                                <input type="text" name="firstname" value="<?php echo $driver_details['firstname']; ?>" id="firstname">
                                <input type="text" name="lastname" value="<?php echo $driver_details['lastname']; ?>" id="lastname">
                            </div>
                        </div>

                        <div class="email">
                            <label for="email">Email</label>
                            <input type="text" name="email" value="<?php echo $driver_details['email']; ?>" id="email">
                        </div>

                        <div class="contact">
                            <label for="email">Contact #</label>
                            <input type="text" name="contact" value="<?php echo $driver_details['contact_no']; ?>" id="contact">
                        </div>
                    </div>
                    <div class="driver-information">
                        <h4 class="information-logo">Driver Information</h4>

                        <div class="driver-license">
                            <label for="license-number">Driver License</label>
                            <input type="text" name="license-number" value="<?php echo $driver_details['license_number']; ?>" id="license-number">
                        </div>

                        <div class="driver-note">
                            <label for="driver-note">Driver Note</label>
                            <input type="text" name="driver-note" value="<?php echo $driver_details['driver_notes']; ?>" id="driver-note">
                        </div>

                        <div class="driver-rating">
                            <label for="driver-rating">Driver Rating</label>
                            <input type="text" name="driver-rating" value="<?php echo $driver_details['ratings'] . "★"; ?>" id="driver-rating">
                        </div>
                    </div>
                </div>

                <div class="popup-btn">
                    <button id="cancel">Cancel</button>
                    <button type="submit" name="update" id="update">Update</button>
                </div>
            </div>
        </div>
    </form>

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
</body>
</html>