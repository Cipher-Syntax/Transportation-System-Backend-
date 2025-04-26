<?php
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/UserRegistration.php");

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['create-account-btn'])){
        
        $firstname = trim(htmlspecialchars($_POST['firstname']));
        $lastname = trim(htmlspecialchars($_POST['lastname']));
        $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $password = trim($_POST['password']);
    
        try{
            if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
                if($user->createUser($firstname, $lastname, $email, $hashed_password)){
                    header("Location: login.php");
                    die();
                }
                else{
                    echo "<script>alert('Failed to create account.');</script>";
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
    <title>Create Account</title>

    <link rel="stylesheet" href="../assets/css/create_account.css">
</head>
<body>
    <div class="background-container">
        <div class="catchphrase">
            <h1 id="logo">GoMove</h1>
            <h2>We’ve got your back, no matter where you are! </h2>
        </div>

        <div class="create-account-container">
            <div class="create-account-choice">
                <h2 id="create-account-text">Create Account</h2>
            </div>

            <div class="create-account-input">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="name-input">
                        <input type="text" name="firstname" placeholder="Firstname" id="firstname">
                        <input type="text" name="lastname" placeholder="Lastname" id="lastname">
                    </div>

                    <input type="email" name="email" id="email" placeholder="Enter email">
                    <input type="password" name="password" id="password" placeholder="Enter password">

                    <button type="submit" name="create-account-btn" id="create-account-btn">Create Account</button>
                    <div class="links">
                        <p>Already have an account?</p>
                        <a href="../pages/login.php">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>