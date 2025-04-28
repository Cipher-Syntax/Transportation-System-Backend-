<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    $database = new Database();
    $conn = $database->getConnection();
    $driver_data = adminCheckLogin($conn);

    $driver_id = $driver_data['id'];

    $query = "SELECT 
        rides.ride_id, 
        users.firstname AS user_firstname, 
        users.lastname AS user_lastname, 
        drivers.firstname AS driver_firstname, 
        drivers.lastname AS driver_lastname,  
        rides.location, 
        rides.destination, 
        rides.amount, 
        rides.status 
        FROM rides 
        JOIN users ON users.id = rides.passenger 
        JOIN drivers ON drivers.id = rides.driver LIMIT 5
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // TOTAL USERS
    $totalUsersQuery = "SELECT COUNT(passenger) as total_users FROM rides ";
    $stmt = $conn->prepare($totalUsersQuery);
    $stmt->execute();
    $totalUsersQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);

    // TOTAL DRIVERS
    $totalDriversQuery = "SELECT COUNT(driver) as total_drivers FROM rides ";
    $stmt = $conn->prepare($totalDriversQuery);
    $stmt->execute();
    $totalDriversQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);

    // COMPLETED RIDES
    $completedQuery = "SELECT COUNT(ride_id) as completed_rides FROM rides WHERE status = 'Completed' ";
    $stmt = $conn->prepare($completedQuery);
    $stmt->execute();
    $completedQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);

    // REVENUE TODAY
    $revenueQuery = "SELECT SUM(amount) as revenue_today FROM rides WHERE status = 'Completed' ";
    $stmt = $conn->prepare($revenueQuery);
    $stmt->execute();
    $revenueQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <h1 class="dashboard-text">Dashboard</h1>

            </div>

            <div class="first-flex">
                <div class="box">
                    <h3>Total Users</h3>
                    <p><?php echo $totalUsersQueryResult['total_users']; ?></p>
                </div>

                <div class="box">
                    <h3>Active Drivers</h3>
                    <p><?php echo $totalDriversQueryResult['total_drivers']; ?></p>
                </div>

                <div class="box">
                    <h3>Completed Rides</h3>
                    <p><?php echo $completedQueryResult['completed_rides']; ?></p>
                </div>

                <div class="box">
                    <h3>Revenue</h3>
                    <p><?php echo $revenueQueryResult['revenue_today']; ?></p>
                </div>
            </div>
            

            <div class="second-flex">
                <div class="line-graph">
                    <div style="width: 520px; margin-left: auto; margin-right: auto;">
                        <canvas id="revenueLineChart"></canvas>
                    </div>

                </div>

                <div class="pie-graph">
                    <div style="width: 250px; height: 250px; margin-right: auto; margin-left: auto; margin-top: 10px;">
                        <canvas id="revenuePieChart" width="250" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="recent-rides">
                <h3 class="recent-text">Recent Rides</h3>

                <table>
                    <tr>
                        <th>Ride ID</th>
                        <th>User</th>
                        <th>Driver</th>
                        <th>Location</th>
                        <th>Destination</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>

                    <tbody>
                    <tbody>
                        <?php foreach($result as $row): ?>
                            <tr>
                                <td><?= $row['ride_id']; ?></td>
                                <td><?= $row['user_firstname'] . ' ' . $row['user_lastname']; ?></td>
                                <td><?= $row['driver_firstname'] . ' ' . $row['driver_lastname']; ?></td>
                                <td><?= $row['location']; ?></td>
                                <td><?= $row['destination']; ?></td>
                                <td><?= $row['amount']; ?></td>
                                <td><?= $row['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                        <!-- <tr>
                            <td>1</td>
                            <td>Justine</td>
                            <td>Yuu</td>
                            <td>Ayala</td>
                            <td>Pueblo</td>
                            <td>200</td>
                            <td>Pending</td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>Justine</td>
                            <td>Yuu</td>
                            <td>Ayala</td>
                            <td>Pueblo</td>
                            <td>200</td>
                            <td>Pending</td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Justine</td>
                            <td>Yuu</td>
                            <td>Ayala</td>
                            <td>Pueblo</td>
                            <td>200</td>
                            <td>Pending</td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>Justine</td>
                            <td>Yuu</td>
                            <td>Ayala</td>
                            <td>Pueblo</td>
                            <td>200</td>
                            <td>Pending</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("../api/revenue_data.php")
                .then((res) => res.json())
                .then((data) => {
                    const labels = data.map(item => item.day);
                    const revenues = data.map(item => item.revenue);

                    // Line Chart
                    new Chart(document.getElementById("revenueLineChart"), {
                        type: "bar",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Daily Revenue (₱)",
                                data: revenues,
                                borderColor: "rgb(0, 119, 182)",
                                backgroundColor: "rgba(0, 119, 182, 0.2)",
                                tension: 0.3,
                                fill: true,
                                pointRadius: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });

                    // Pie Chart (Sum of revenue per day)
                    new Chart(document.getElementById("revenuePieChart"), {
                        type: "pie",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Revenue Share",
                                data: revenues,
                                backgroundColor: [
                                    "rgba(0, 119, 182, 0.7)",
                                    "rgba(0, 200, 83, 0.7)",
                                    "rgba(255, 193, 7, 0.7)",
                                    "rgba(244, 67, 54, 0.7)",
                                    "rgba(156, 39, 176, 0.7)",
                                    "rgba(255, 87, 34, 0.7)"
                                ],
                                borderColor: "#fff",
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "bottom"
                                }
                            }
                        }
                    });
                })
                .catch(err => console.error("Error loading chart data:", err));
        });
    </script>
</body>
</html>