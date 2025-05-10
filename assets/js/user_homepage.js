document.querySelector('.input-form').addEventListener('submit', function (e) {
  e.preventDefault();

  const location = document.getElementById('location').value;
  const destination = document.getElementById('destination').value;
  const contact = document.getElementById('contact').value;
  const pickup = document.getElementById('pickup').value;
  const price = document.getElementById('hiddenPrice').value;

  fetch('../api/book_ride.php', {
      method: 'POST',
      headers: {
          "Content-Type": "application/json"
      },
      body: JSON.stringify({
          location: location,
          destination: destination,
          pickup: pickup,
          contact: contact,
          price: price
      })
  })
  .then(response => response.json())
  .then(data => {
      if (data.status === "success") {
          window.location.href = data.redirect;
      } 
      else {
          alert("Failed to book ride. Try again.");
      }
  })
  .catch(error => console.error("Error submitting ride booking:", error));
  
});



// MAP INTEGRATION
const map = L.map("map").setView([6.9214, 122.0790], 13);


L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution: "&copy; OpenStreetMap contributors",
}).addTo(map);

let locationMarker, destinationMarker;

const locationInput = document.getElementById("location");
const destinationInput = document.getElementById("destination");
const priceText = document.querySelector(".php-price");
const basefare = document.getElementById("fare-data").getAttribute("data-basefare");
const perKmRate = document.getElementById('per-km-rate-data').getAttribute("data-perKmRate");


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
  const R = 6371;
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
  const loc = locationInput.value.trim();
  const dest = destinationInput.value.trim();
  if (!loc || !dest) return;

  const locCoords = await geocode(loc);
  const destCoords = await geocode(dest);
  if (!locCoords || !destCoords) return;

  // Clear old markers
  if (locationMarker) map.removeLayer(locationMarker);
  if (destinationMarker) map.removeLayer(destinationMarker);

  locationMarker = L.marker(locCoords).addTo(map).bindPopup("Pickup").openPopup();
  destinationMarker = L.marker(destCoords).addTo(map).bindPopup("Destination").openPopup();

  map.fitBounds([locCoords, destCoords], { padding: [50, 50] });

  const distance = getDistance(...locCoords, ...destCoords);
  const price = Math.max(basefare, Math.round(distance * perKmRate)); // Base fare ₱40 + ₱15/km

  priceText.textContent = `Price: ₱ ${price}`;

  document.getElementById('hiddenPrice').value = price;

}

// Event listeners
locationInput.addEventListener("change", updateRoute);
destinationInput.addEventListener("change", updateRoute);

// Use browser geolocation to prefill location
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(async (pos) => {
    const { latitude, longitude } = pos.coords;
    const res = await fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`
    );
    const data = await res.json();
    locationInput.value = data.display_name || "";
    updateRoute();
  });
}

