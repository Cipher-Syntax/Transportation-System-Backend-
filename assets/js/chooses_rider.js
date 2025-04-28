// var map = L.map('map').setView([6.9214, 122.0790], 14);
var map = L.map('map').setView([6.9214, 122.0790], 12);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

let marker, circle;

function success(pos) {
    const lat = pos.coords.latitude;
    const long = pos.coords.longitude;
    const accuracy = pos.coords.accuracy;

    console.log("Lat:", lat, "Long:", long, "Accuracy:", accuracy);


    // Restrict location to Zamboanga City bounds
    const bounds = L.latLngBounds(
        [6.80, 121.90],  // More southwest coverage
        [7.00, 122.20]   // More northeast coverage
    );


    // if (!bounds.contains([lat, long])) {
    //     alert("Location is outside Zamboanga City.");
    //     return;
    // }

    // Remove old marker and circle
    if (marker) {
        map.removeLayer(marker);
        map.removeLayer(circle);
    }

    marker = L.marker([lat, long]).addTo(map)
        .bindPopup("You are here").openPopup();
    circle = L.circle([lat, long], {radius: accuracy}).addTo(map);

    // Keep the map centered on the user's location
    map.setView([lat, long], 14);
}

function error(err) {
    if (err.code === 1) {
        alert("Please allow geolocation access.");
    } else {
        alert("Cannot get user's location.");
    }
}

navigator.geolocation.watchPosition(success, error, {
    enableHighAccuracy: true,
    maximumAge: 0
});

document.addEventListener('DOMContentLoaded', () => {
    const rider = document.querySelectorAll('.scroll-rider');
    const selectButton = document.querySelector('.select-rider button');
    const slideUpModal = document.querySelector('.slide-up-rider-details');
    const closeBtn = document.querySelector('.close-btn');
    let selectedRider = null;

    rider.forEach(card => {
        card.addEventListener('click', () => {
            // If clicked again, unselect it
            if (card.classList.contains('selected')) {
                card.classList.remove('selected');
                selectedRider = null;
            } 
            else {
                rider.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                selectedRider = card;
            }
        });
    });
    

    // Show the modal if a rider is selected
    selectButton.addEventListener('click', () => {
        if (selectedRider) {
            slideUpModal.style.display = 'block';
        } 
        else {
            alert("Please select a rider first.");
        }
    });

    // Hide the modal
    closeBtn.addEventListener('click', () => {
        slideUpModal.style.display = 'none';
    });
});

let driverId, driverName, driverRating, driverNotes, driverSeats, driverImage;
document.querySelectorAll('.scroll-rider').forEach((rider) => {
    rider.addEventListener('click', function () {
        driverId = rider.getAttribute('data-id');
        driverName = rider.getAttribute('data-name');
        driverRating = rider.getAttribute('data-rating');
        driverNotes = rider.getAttribute('data-notes');
        driverLicense = rider.getAttribute('data-license');
        driverImage = rider.getAttribute('data-image');

        console.log(driverId);
        console.log(driverName);
        console.log(driverRating);
        console.log(driverNotes);
        console.log(driverLicense);
        console.log(driverImage);

        document.getElementById('selectedDriverInput').value = driverId;
        document.getElementById('driver-name').innerHTML = driverName;
        document.getElementById('driver-rating').innerHTML = driverRating;
        document.getElementById('driver-notes').innerHTML = driverNotes;
        document.getElementById('driver-license').innerHTML = "License Number: " + driverLicense;
        document.getElementById('driver-img').src = driverImage;
    });
});

document.getElementById('confirmRiderBtn').addEventListener('click', function () {
    if (!driverId) {
        alert('No driver selected!');
        return;
    }

    fetch('../api/confirm_rider.php', {
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            driver_id: driverId,
            driver_name: driverName,
            driver_rating: driverRating,
            driver_notes: driverNotes,
            driver_license: driverLicense,
            driver_image: driverImage
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            window.location.href = "../pages/user_homepage.php";
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Something went wrong.');
    });
});
