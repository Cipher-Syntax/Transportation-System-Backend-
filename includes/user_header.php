<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    $database = new Database();
    $conn = $database->getConnection();
    $user_data = checkUserLogin($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="../assets/css/user_header.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <div class="hamburger" onclick="toggleSidebar()">
            <i class='bx bx-menu' ></i>
        </div>

        <div class="logo">
            <h1>GoMove</h1>
        </div>
        
        <div class="user-information">
            <div class="notif-bell">
                <div class="notif-value" id="notif-value"></div>
                <i class='bx bx-bell'></i>
            </div>
            <img src="<?php echo $user_data['user_profile']; ?>" class="user-pic" onclick="toggleMenu()">
        </div>
    </header>

    <div class="sub-menu-wrapper" id="js-sub-menu">
        <div class="sub-menu">
            <div class="user-info">
                <img src="<?php echo $user_data['user_profile']; ?>" class="user-pic" >
                <h2><?php echo $user_data['firstname'] . ' ' .$user_data['lastname'] ; ?></h2>
            </div>
            <hr>

            <a href="../pages/user_profile.php" class="sub-menu-links edit-profile">
                <i class='bx bx-user'></i>
                <p>Edit Profile</p>
                <!-- <span> > </span> -->
            </a>

            
            <a href="../pages/user_settings.php" class="sub-menu-links settings-and-privacy">
                <i class='bx bx-cog'></i>
                <p>Settings & Privacy</p>
                <!-- <span> > </span> -->
            </a>

            <a href="../pages/user_history.php" class="sub-menu-links history">
                <i class='bx bx-history'></i>
                <p>History</p>
                <!-- <span> > </span> -->
            </a>

            <a href="../pages/logout.php" class="sub-menu-links logout" id="logout">
                <i class='bx bx-log-out'></i>
                <p>Logout</p>
                <!-- <span> > </span> -->
            </a>
        </div>
    </div>

    
    <div class="user-dashboard">
        <nav class="sidebar-navigation">
            <div class="sidebar-hamburger" onclick="closeSidebar()">
                <i class='bx bx-menu' ></i>
            </div>
            
            <hr id="separator">

            <div class="home-field">
                <i class='bx bx-home'></i>
                <a href="../pages/user_homepage.php">Home</a>
            </div>

            <div class="profile-field">
                <i class='bx bx-user' ></i>
                <a href="../pages/user_profile.php">Profile</a>
            </div>

            <div class="history-field">
                <i class='bx bx-history' ></i>
                <a href="../pages/user_history.php">History</a>
            </div>

            <div class="settings-field">
                <i class='bx bx-cog' ></i>
                <a href="../pages/user_settings.php">Settings</a>
            </div>

            <div class="logout-field">
                <i class='bx bx-log-out' ></i>
                <a href="../pages/logout.php">Logout</a>
                <img src="<?php echo $user_data['user_profile'] ?>" alt="user-pic">
            </div>
            
        </nav>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-navigation');
            sidebar.classList.toggle('active');
        }

        function closeSidebar() {
            const sidebar = document.querySelector('.sidebar-navigation');
            sidebar.classList.remove('active');
        }

        let subMenu = document.getElementById("js-sub-menu");

        function toggleMenu(){
            subMenu.classList.toggle("js-open-menu");
        }
    </script>
