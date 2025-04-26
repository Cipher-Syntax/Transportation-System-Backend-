<?php
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");
    require_once("../database/UserRegistration.php");

    $database = new Database();
    $conn = $database->getConnection();
    $driver_data = checkDriverLogin($conn);

    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['save'])) {
            $driver_id = $driver_data['id'];
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);

            $driver_profile = $_FILES['userProfile']['name'];
            $driver_profile_tmp = $_FILES['userProfile']['tmp_name'];
            $driver_profile_path = "../assets/images/" . $driver_profile;
    
            if (!empty($driver_profile)) {
                move_uploaded_file($driver_profile_tmp, $driver_profile_path);
            } 
            else {
                $driver_profile_path = $driver_data['driver_profile'];
            }

            $result = $user->updateDriver($driver_id, $firstname, $lastname, $driver_profile_path);

            if($result){
                echo "<script>alert('Updated successfully')</script>";
            }
            else{
                echo "<script>alert('Failed to update')</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Profile</title>
    <link rel="stylesheet" href="../assets/css/driver_profile.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' >
</head>
<body>
    <?php include("../includes/driver_header.php");?>
    <div class="container">
            
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="profile-container">
                <div class="profile-header"></div>
                <div class="profile-picture">
                    <input type="file" name="userProfile" id="fileInput" accept="image/*" style="display: none;">
                    
                    <label for="fileInput">
                        <img id="profileImage" src="<?php echo $driver_data['driver_profile']; ?>" onerror="this.onerror=null; this.src='../assets/images/user.png';" alt="Profile Picture">
                    </label>

                    <p id="click-to-change">Click the image to change</p>
                </div>

                <script>
                    document.getElementById("fileInput").addEventListener("change", function(event) {
                        const file = event.target.files[0];

                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById("profileImage").src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>
                
                <div class="profile-info">
                    <div class="info-box">
                        <input type="text" name="firstname" class="name-input" value="<?php echo $driver_data['firstname']; ?>">
                        <span class="label" id="firstname-label" >Firstname</span>
                    </div>
                    <div class="info-box">
                        <input type="text" name="lastname" class="name-input" value="<?php echo $driver_data['lastname']; ?>">
                        <span class="label" id="lastname-label">Lastname</span>
                    </div>
                </div>

                <div class="save-button">
                    <button type="submit" name="save" id="save-button">Save Changes</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let subMenu = document.getElementById("js-sub-menu");

        function toggleMenu(){
            subMenu.classList.toggle("js-open-menu");
        }
    </script>
</body>
</html>