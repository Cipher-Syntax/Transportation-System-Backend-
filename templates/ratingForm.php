<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    require_once("../database/Database.php");
    require_once("../database/Connection.php");
    require_once("../database/login_checker.php");

    require_once("../includes/user_homepage_query.php");

    $passenger_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $driver_id = isset($_SESSION['driver_id']) ? $_SESSION['driver_id'] : null;

    $query = "SELECT rides.passenger AS user, 
        rides.driver AS driver, 
        CONCAT(drivers.firstname, ' ', drivers.lastname) AS fullname, 
        drivers.driver_profile
        FROM rides
        JOIN users ON users.id = rides.passenger
        JOIN drivers ON drivers.id = rides.driver
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../templates/ratingForm.css">
</head>
<body>
    <form action="../api/submit_rating.php" method="POST" id="ratingForm">
        <div class="rating-container">
            <p>Rate This Driver</p>

            <div class="image">
                <img src="<?= htmlspecialchars($result['driver_profile']) ?>" alt="Driver">
            </div>

            <div class="stars" id="starRating">
                <span class="star" data-rating="1">&#9733;</span>
                <span class="star" data-rating="2">&#9733;</span>
                <span class="star" data-rating="3">&#9733;</span>
                <span class="star" data-rating="4">&#9733;</span>
                <span class="star" data-rating="5">&#9733;</span>
            </div>

            <input type="hidden" name="rating" id="ratingInput">
            <input type="hidden" name="passenger_id" value="<?= $passenger_id ?? $result['user']; ?>">
            <input type="hidden" name="driver_id" value="<?= $driver_id ?? $result['driver']; ?>">

            <p id="rating-value"></p>
            <p><?= $result['fullname']?></p>
            <button type="submit" class="btn-ok">OK</button>
        </div>
    </form>

    
    <script>
        const stars = document.querySelectorAll(".star");
        const ratingValue = document.getElementById("rating-value");
        let selectedRating = 0;

        stars.forEach((star) => {
            star.addEventListener("click", () => {
                selectedRating = star.getAttribute("data-rating");
                ratingValue.textContent = `You rated: ${selectedRating} star(s)`;
                highlightStars(selectedRating);
                document.getElementById("ratingInput").value = selectedRating; // sets hidden input value
            });

            star.addEventListener("mouseover", () => {
                highlightStars(star.getAttribute("data-rating"));
            });

            star.addEventListener("mouseout", () => {
                highlightStars(selectedRating);
            });
        });

        function highlightStars(rating) {
            stars.forEach((star) => {
            if (star.getAttribute("data-rating") <= rating) {
                star.classList.add("highlight");
            } else {
                star.classList.remove("highlight");
            }
            });
        }

        const form = document.getElementById("ratingForm");
        const ratingContainer = document.querySelector(".rating-container");

        form.addEventListener("submit", function (e) {
            e.preventDefault(); // prevent the normal form submit

            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                console.log("Rating submitted:", result);
                ratingContainer.style.display = "none";
                alert("Thanks for rating!");

            })
            .catch(error => {
                console.error("Error submitting rating:", error);
            });
        });
    </script>
</body>
</html>