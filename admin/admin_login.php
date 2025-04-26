<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/UserRegistration.php");


    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $password = trim($_POST['password']);

        if (!empty($email) && !empty($password)) {
            $result = $admin->adminLogin($email, $password);

            if($result){
                if(password_verify($password, $result['password'])){
                    $_SESSION['email'] = $result['email'];
                    $_SESSION['id'] = $result['id'];

                    header("Location: admin_dashboard.php");
                    exit;
                }
                else{
                    echo "<script>alert('Invalid email or password');</script>";
                }
            }
            else{
                echo "<script>alert('Something went wrong');</script>";
            }

        } 
        else {
            echo "<script>alert('Please fill in all required fields!');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="stylesheet" href="../assets/css/admin_login.css">

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
                <h2 id="login-container-text">Welcome Admin</h2>
            </div>

            <div class="login-container-input">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                    <input type="email" name="email" id="email" placeholder="Enter email">
                    <input type="password" name="password" id="password" placeholder="Enter password">

                    <button type="submit" name="login-btn" id="login-btn">Access</button>

                </form>
            </div>
        </div>
    </div>

</body>
</html>