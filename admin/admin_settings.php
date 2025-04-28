<?php
    session_start();
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    $database = new Database();
    $conn = $database->getConnection();
    $driver_data = adminCheckLogin($conn);

    $query = "SELECT *FROM settings";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(isset($_POST['save_pricing_payment'])){
            $basefare = $_POST['base_fare'];
            $per_km_rate = $_POST['per_km_rate'];
            $driver_commission = $_POST['driver_commission'];
            $driver_quota = $_POST['driver_quota'];
            $settings_id = $_POST['settings_id'];

            try{
                $updateQuery = "UPDATE settings SET base_fare = :base_fare, per_km_rate = :per_km_rate, driver_commission = :driver_commission, driver_quota = :driver_quota WHERE settings_id = :settings_id";
                $stmt = $conn->prepare($updateQuery);
                
                $stmt->bindParam(':base_fare', $basefare);
                $stmt->bindParam(':per_km_rate', $per_km_rate);
                $stmt->bindParam(':driver_commission', $driver_commission);
                $stmt->bindParam(':driver_quota', $driver_quota);
                $stmt->bindParam(':settings_id', $settings_id);
                
                $stmt->execute();
            
                if ($stmt->rowCount() > 0) {
                    echo "<script>
                            alert('Updated Successfully!');
                            window.location.reload();
                         </script>";
                    
                }
                // else{
                //     echo "<script>
                //             alert('No Changes!');
                //          </script>";
                // }
            }
            catch (PDOException $e) {
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
    <title>Admin Settings</title>
    <link rel="stylesheet" href="../assets/css/admin_settings.css">

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
                <h1 class="dashboard-text">Settings</h1>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="settings_id" value="<?php echo $result['settings_id']; ?>">
                <div class="settings-card">
                    <div class="card-header">
                        <h2>General Settings</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="company-name">Company Name</label>
                            <input type="text" id="company-name" class="form-control" value="GOMOVE Transport">
                        </div>

                        <div class="form-group">
                            <label for="contact-email">Support Email</label>
                            <input type="email" id="contact-email" class="form-control" value="support@gomove.com">
                        </div>

                        <div class="form-group">
                            <label for="contact-phone">Support Phone</label>
                            <input type="tel" id="contact-phone" class="form-control" value="+1 (555) 123-4567">
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </div>

                <!-- Pricing & Payment -->
                <div class="settings-card">
                    <div class="card-header">
                        <h2>Pricing, Payment & Driver Quota</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="base-fare">Base Fare (₱)</label>
                            <input type="number" name="base_fare" id="base-fare" class="form-control" value="<?php echo $result['base_fare']; ?>" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="per-mile">Per Km Rate (₱)</label>
                            <input type="number" name="per_km_rate" id="per-mile" class="form-control" value="<?php echo $result['per_km_rate']; ?>" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="driver-commission">Driver Commission (%)</label>
                            <input type="number" name="driver_commission" id="driver-commission" class="form-control" value="<?php echo $result['driver_commission']; ?>" max="100" min="0">
                        </div>
                        <div class="form-group">
                            <label for="quota">Driver Quota</label>
                            <input type="number" name="driver_quota" id="quota" class="form-control" value="<?php echo $result['driver_quota']; ?>" step="1">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="save_pricing_payment" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>