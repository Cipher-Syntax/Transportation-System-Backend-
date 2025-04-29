<?php
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/UserRegistration.php");


    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $password = trim($_POST['password']);

        try{
            if (!empty($email) && !empty($password)) {
                $result = $user->loginUser($email, $password);
    
                if($result){
                    if(password_verify($password, $result['password'])){
                        $_SESSION['email'] = $result['email'];
                        $_SESSION['id'] = $result['id'];
    
                        header("Location: user_homepage.php");
                        exit;
                    }
                    else{
                        echo "<script>alert('Invalid email or password');</script>";
                    }
                }
                else{
                    echo "<script>alert('Email not found');</script>";
                }
    
            } 
            else {
                echo "<script>alert('Please fill in all required fields!');</script>";
            }
        }
        catch(PDOException $e){
            echo "Error: Something went wrong " . $e->getMessage(); 
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="../assets/css/login.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="background-container">
        <div class="catchphrase">
            <h1 id="logo">GoMove</h1>
            <h2>We’ve got your back, no matter where you are! </h2>
        </div>

        <div class="login-container">
            <div class="login-container-choice">
                <h2 id="login-container-text">Login</h2>
            </div>

            <div class="login-container-input">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                    <input type="email" name="email" id="email" placeholder="Enter email">
                    <input type="password" name="password" id="password" placeholder="Enter password">

                    <button type="submit" name="login-btn" id="login-btn">Login</button>
                    <div class="links">
                        <p>Don't have an account?</p>
                            <a href="../pages/create_account.php">Create Account</a>
                    </div>

                    <!-- <i class='bx bx-key' id="driver_login"></i> -->
                </form>
            </div>
        </div>
    </div>

    <!-- <script>
        document.getElementById('driver_login').addEventListener('click', () => {
            window.location.href = "../pages/driver_login.php";
        })
    </script> -->
</body>
</html>