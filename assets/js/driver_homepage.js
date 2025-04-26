document.addEventListener("DOMContentLoaded", () => {
    const choice = document.querySelectorAll('.driver-choice');

    choice.forEach(driver_choice => {
        driver_choice.addEventListener('click', () => {
            if(driver_choice.classList.contains('selected')){
                driver_choice.classList.remove('selected');
            }
            else{
                choice.forEach(dr => dr.classList.remove('selected'));
                driver_choice.classList.add('selected');
            }
        });
    });

    // Define map
    const map = L.map("map").setView([6.9214, 122.0790], 13); // Zamboanga default

    // Add OSM Tile
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    let locationMarker, destinationMarker;

    // Fetch location and destination from PHP
    const locationInput = "<?= $location ?>";
    const destinationInput = "<?= $destination ?>";
    const priceText = document.getElementById('priceText');

    // Geocode function (uses Nominatim)
    async function geocode(address) {
      const response = await fetch(
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`
      );
      const data = await response.json();
      return data[0] ? [parseFloat(data[0].lat), parseFloat(data[0].lon)] : null;
    }

    // Haversine formula for distance (in km)
    function getDistance(lat1, lon1, lat2, lon2) {
      const R = 6371; // Radius of the Earth in km
      const dLat = ((lat2 - lat1) * Math.PI) / 180;
      const dLon = ((lon2 - lon1) * Math.PI) / 180;
      const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos((lat1 * Math.PI) / 180) *
        Math.cos((lat2 * Math.PI) / 180) *
        Math.sin(dLon / 2) ** 2;
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      return R * c;
    }

    // Calculate and update distance and price
    async function updateRoute() {
      const loc = locationInput.trim();
      const dest = destinationInput.trim();
      if (!loc || !dest) return;

      const locCoords = await geocode(loc);
      const destCoords = await geocode(dest);
      if (!locCoords || !destCoords) return;

      // Clear old markers
      if (locationMarker) map.removeLayer(locationMarker);
      if (destinationMarker) map.removeLayer(destinationMarker);

      // Add new markers
      locationMarker = L.marker(locCoords).addTo(map).bindPopup("Pickup").openPopup();
      destinationMarker = L.marker(destCoords).addTo(map).bindPopup("Destination").openPopup();

      // Adjust map to fit the bounds of both markers
      map.fitBounds([locCoords, destCoords], { padding: [50, 50] });

      // Calculate the distance and price
      const distance = getDistance(...locCoords, ...destCoords);
      const price = Math.max(40, Math.round(distance * 15));

      priceText.textContent = `Price: ₱ ${price}`;

      document.getElementById('hiddenPrice').value = price;
    }

    // Initial call to updateRoute
    updateRoute();
});

// document.querySelectorAll('.driver-action').forEach(btn => {
//   btn.addEventListener('click', function () {
//       const action = button.dataset.action;
//       const rideId = document.querySelector('input[name="ride_id"]')?.value || document.querySelector('#currentRideId')?.value || document.querySelector('#newRideId')?.value;

//       if (!rideId || !action) {
//         return alert("Missing ride or action")
//       };

//       fetch('../api/driver_ride_choice.php', {
//           method: 'POST',
//           headers: { "Content-Type": "application/json" },
//           body: JSON.stringify({ 
//             ride_id: rideId, 
//             action: action 
//           })
//       })
//       .then(res => res.json())
//       .then(data => {
//           alert(data.message);
//           if (data.status === 'success') {
//               window.location.reload();
//           }
//       })
//       .catch(err => console.error(alert("Something went wrong."), err));
//   });
// });

// document.addEventListener('DOMContentLoaded', function () {
//   // Accept Ride
//   const acceptBtn = document.getElementById('accept');
//   if (acceptBtn) {
//       acceptBtn.addEventListener('click', function () {
//           const rideId = document.getElementById('newRideId').value;
//           rideAction(rideId, 'accept');
//       });
//   }

//   // Decline Ride
//   const declineBtn = document.getElementById('decline');
//   if (declineBtn) {
//       declineBtn.addEventListener('click', function () {
//           const rideId = document.getElementById('newRideId').value;
//           rideAction(rideId, 'decline');
//       });
//   }

//   // Confirm Payment
//   const confirmPaymentBtn = document.querySelector('[data-action="confirm_payment"]');
//   if (confirmPaymentBtn) {
//       confirmPaymentBtn.addEventListener('click', function () {
//           const rideId = document.getElementById('currentRideId').value;
//           rideAction(rideId, 'confirm_payment');
//       });
//   }
// });


// Fix the ride action function for better error handling
function rideAction(rideId, action) {
  if (!rideId) {
    alert('Missing ride ID');
    return;
  }
  
  fetch('../api/driver_ride_choice.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ 
        ride_id: rideId, 
        action: action 
      })
  })
  .then(response => {
      if (!response.ok) {
          throw new Error('Network response was not ok');
      }
      return response.json();
  })
  .then(data => {
      alert(data.message);
      if (data.status === 'success') {
          window.location.reload();
      }
  })
  .catch(error => {
      console.error('Error:', error);
      alert('Something went wrong. Please try again.');
  });
}


document.addEventListener('DOMContentLoaded', function () {
  // Accept Ride
  const acceptBtn = document.getElementById('accept');
  if (acceptBtn) {
      acceptBtn.addEventListener('click', function () {
          const rideId = document.getElementById('newRideId').value;
          if (!rideId) {
              alert('Ride ID not found');
              return;
          }
          rideAction(rideId, 'accept');
      });
  }

  // Decline Ride
  const declineBtn = document.getElementById('decline');
  if (declineBtn) {
      declineBtn.addEventListener('click', function () {
          const rideId = document.getElementById('newRideId').value;
          if (!rideId) {
              alert('Ride ID not found');
              return;
          }
          rideAction(rideId, 'decline');
      });
  }

  // Confirm Payment
  const confirmPaymentBtn = document.querySelector('[data-action="confirm_payment"]');
  if (confirmPaymentBtn) {
      confirmPaymentBtn.addEventListener('click', function () {
          const rideId = document.getElementById('currentRideId').value;
          if (!rideId) {
              alert('Current ride ID not found');
              return;
          }
          rideAction(rideId, 'confirm_payment');
      });
  }
});