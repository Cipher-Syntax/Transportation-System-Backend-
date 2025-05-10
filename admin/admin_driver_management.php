<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    $database = new Database();
    $conn = $database->getConnection();
    $driver_data = adminCheckLogin($conn);
    $driver_id = $driver_data['id'];

    $count_query = "SELECT COUNT(*) as total FROM drivers";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->execute();
    $count_result = $count_stmt->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM drivers";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Management</title>
    <link rel="stylesheet" href="../assets/css/admin_driver_management.css">
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
                <h1 class="dashboard-text">Driver Management</h1>
                <button type="button" id="add-driver-btn">Add Driver</button>
            </div>

            <div class="recent-rides">
                <h3 class="recent-text">Drivers</h3>

                <div class="table-scroll">
                    <table>
                        <tr>
                            <th>Driver ID</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Ratings</th>
                            <th>Action</th>
                        </tr>

                        <tbody>
                            <?php foreach($result as $row): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= $row['firstname']; ?></td>
                                    <td><?= $row['lastname']; ?></td>
                                    <td><?= $row['ratings']; ?></td>
                                    <td>
                                        <div class="action">
                                            <a href="admin_action.php?driver_id=<?= $row['id']; ?>" class="edit">Edit</a>
                                            <a href="admin_action.php?driver_id=<?= $row['id']; ?>" class="view">View</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                            
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const page = document.querySelectorAll('.box');
            let selectedPage = null;

            page.forEach(card => {
                card.addEventListener('click', () => {
                    // If clicked again, unselect it
                    if (card.classList.contains('selected')) {
                        card.classList.remove('selected');
                        selectedPage = null;
                    } 
                    else {
                        page.forEach(c => c.classList.remove('selected'));
                        card.classList.add('selected');
                        selectedPage = card;
                    }
                });
            });
            
        });
        
        document.getElementById('add-driver-btn').addEventListener('click', () => {
            window.location.href = "../admin/add_driver.php";
        });

        document.getElementById('edit').addEventListener('click', () => {
            window.location.href = "../admin/admin_action.php";
        });

        document.getElementById('view').addEventListener('click', () => {
            window.location.href = "../admin/admin_action.php";
        });

    </script>
</body>
</html>