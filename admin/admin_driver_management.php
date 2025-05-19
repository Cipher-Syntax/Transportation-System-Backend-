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


    $start_page = 0;
    $rows_per_page = 5;

    $records = "SELECT *FROM drivers";
    $stmt = $conn->prepare($records);
    $stmt->execute();
    $num_of_rows = $stmt->fetchAll();
    $rows = COUNT($num_of_rows);
    $pages = ceil($rows / $rows_per_page);

    if(isset($_GET['page-num-row'])){
        $page = $_GET['page-num-row'] - 1;
        $start_page = $page * $rows_per_page;
    }
    
    $query = "SELECT * FROM drivers LIMIT $start_page, $rows_per_page";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_GET['page-num-row'])){
        $page_id = $_GET['page-num-row'];
    }
    else{
        $page_id = 1;
    }

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
<body id="<?php echo $page_id ?>">
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
                <div class="pagination">
                    <?php
                        if(!isset($_GET['page-num-row'])){
                            $page = 1;
                        }
                        else{
                            $page = $_GET['page-num-row'];
                        }
                    ?>
                    <p>Showing <?php echo $page?> to <?php echo $pages?> pages</p>
                </div>
                <div class="page-number">
                    <!-- FIRST BUTTON -->
                    <a href="?page-num-row=1">First</a>

                    <!-- PREVIOUS BUTTON -->
                    <?php
                        if(isset($_GET['page-num-row']) && $_GET['page-num-row'] > 1){
                            ?> <a href="?page-num-row=<?php echo $_GET['page-num-row'] - 1; ?>">Previous</a> <?php
                        }
                        else{
                            ?> <a href="">Previous</a> <?php
                        }
                    ?>
                    
                    <!-- PAGE NUMBERS -->
                    <?php

                        $current_page = isset($_GET['page-num-row']) ? (int)$_GET['page-num-row'] : 1;
                        $max_links = 5;
                        $start = floor(($current_page - 1) / $max_links) * $max_links + 1;
                        $end = min($start + $max_links - 1, $pages);

                        for($counter = $start; $counter <= $end; $counter++){
                            ?>
                                <a href="?page-num-row=<?php echo $counter ?>" class="page-num"><?php echo $counter ?></a>
                            <?php
                        }
                    ?> 

                    <!-- <a href="" class="page-num">1</a>
                    <a href="" class="page-num">2</a>
                    <a href="" class="page-num">3</a>
                    <a href="" class="page-num">4</a>
                    <a href="" class="page-num">5</a> -->

                    <!-- NEXT BUTTON -->
                    <?php
                        if(!isset($_GET['page-num-row'])){
                            ?> <a href="?page-num-row=2" class="page-num">Next</a> <?php
                        }
                        else{
                            if($_GET['page-num-row'] >= $pages){
                                ?> <a href="">Next</a> <?php
                            }
                            else{
                                ?> <a href="?page-num-row=<?php echo $_GET['page-num-row'] + 1 ?>">Next</a> <?php
                            }
                        }
                    ?>

                    <!-- LAST BUTTON -->
                    <a href="?page-num-row= <?php echo $pages?>">Last</a>
                </div>        
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let links = document.querySelectorAll('.page-num');
            let bodyId = parseInt(document.body.id) - 1;
            links[bodyId].classList.add('active');
        });
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