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
    $user_data = checkUserLogin($conn);

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['save'])) {
            $user_id = $user_data['id'];
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    
            $user_profile = $_FILES['userProfile']['name'];
            $user_profile_tmp = $_FILES['userProfile']['tmp_name'];
            $user_profile_path = "../assets/images/" . $user_profile;
    
            if (!empty($user_profile)) {
                move_uploaded_file($user_profile_tmp, $user_profile_path);
            } 
            else {
                $user_profile_path = $user_data['user_profile'];
            }

            $result = $user->updateUser($user_id, $firstname, $lastname, $email, $user_profile_path);

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
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/user_profile.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' >
</head>
<body>
    <?php include("../includes/user_header.php"); ?>
    <div class="container">

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="profile-container">
                <div class="profile-header"></div>
                <div class="profile-picture">
                    <input type="file" name="userProfile" id="fileInput" accept="image/*" style="display: none;">
                    
                    <label for="fileInput">
                        <img id="profileImage" src="<?php echo $user_data['user_profile']; ?>" onerror="this.onerror=null; this.src='../assets/images/user.png'; " alt="Profile Picture">
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
                        <input type="text" name="firstname" class="name-input" value="<?php echo $user_data['firstname']; ?>">
                        <span class="label" id="firstname-label" >Firstname</span>
                    </div>
                    <div class="info-box">
                        <input type="text" name="lastname" class="name-input" value="<?php echo $user_data['lastname']; ?>">
                        <span class="label" id="lastname-label">Lastname</span>
                    </div>
                </div>

                <div class="info-box">
                    <input type="email" name="email" class="email-input" value="<?php echo $user_data['email']; ?>">
                    <span class="label" id="email-label">Email</span>
                </div>

                <div class="save-button">
                    <button type="submit" name="save" id="save-button">Save Changes</button>
                </div>
            </div>
        </div>
        
    </form>
 
    <script>
        let subMenu = document.getElementById("js-sub-menu");

        function toggleMenu(){
            subMenu.classList.toggle("js-open-menu");
        }
    </script>
</body>
</html>