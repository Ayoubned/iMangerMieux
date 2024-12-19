let map, directionsService, directionsRenderer, userLocation, searchedLocation;
let service, markers = [];
let activeService = "locations";
let showingLocations = false; // Toggle state for locations

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 14,
    center: { lat: 0, lng: 0 }, // Temporary center until geolocation is set
  });

  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer({ map });
  service = new google.maps.places.PlacesService(map);

  getUserLocation(); // Get user position
}

function getUserLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        console.log("Geolocation successful");
        userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        map.setCenter(userLocation);
        addMarker(userLocation, "You are here");
      },
      (error) => {
        console.error("Geolocation error:", error.message);

        if (error.code === error.PERMISSION_DENIED) {
          alert("Geolocation permission denied. Falling back to approximate location.");
        } else {
          alert("Unable to retrieve your location. Falling back to approximate location.");
        }

        fetchIPLocation(); // Fallback to approximate location
      },
      {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0,
      }
    );
  } else {
    alert("Geolocation is not supported by your browser.");
    fetchIPLocation(); // Call fallback directly if geolocation is not supported
  }
}

function fetchIPLocation() {
  fetch("https://ipapi.co/json/")
    .then((response) => response.json())
    .then((data) => {
      console.log("IP-based location data:", data);
      const fallbackLocation = { lat: parseFloat(data.latitude), lng: parseFloat(data.longitude) };
      userLocation = fallbackLocation;
      map.setCenter(fallbackLocation);
      addMarker(fallbackLocation, "IP-based Location");
    })
    .catch((error) => {
      console.error("IP location lookup failed:", error);
      alert("Unable to determine your location. Using default location.");
      const defaultLocation = { lat: 50.62925, lng: 3.057256 }; // Lille, France
      userLocation = defaultLocation;
      map.setCenter(defaultLocation);
      addMarker(defaultLocation, "Default Location");
    });
}

function addMarker(position, title) {
  const marker = new google.maps.Marker({
    position: position,
    map: map,
    title: title,
  });
  markers.push(marker); // Track marker for cleanup
}

function clearMarkers() {
  markers.forEach((marker) => marker.setMap(null));
  markers = [];
}

function clearPath() {
  directionsRenderer.setMap(null); // Remove any existing path
  directionsRenderer = new google.maps.DirectionsRenderer({ map }); // Reset the renderer
}

function searchLocation() {
  const address = document.getElementById("search-box").value;
  const geocoder = new google.maps.Geocoder();
  geocoder.geocode({ address }, (results, status) => {
    if (status === google.maps.GeocoderStatus.OK) {
      searchedLocation = results[0].geometry.location;
      map.setCenter(searchedLocation);
      addMarker(searchedLocation, "Searched Location");
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  });
}

function switchService(serviceType) {
  if (serviceType === "locations") {
    if (showingLocations) {
      clearMarkers();
      showingLocations = false;
      document.getElementById("toggle-locations-btn").textContent = "Show Locations";
    } else {
      displayFacilities();
      showingLocations = true;
      document.getElementById("toggle-locations-btn").textContent = "Hide Locations";
    }
  } else if (serviceType === "path") {
    showingLocations = false;
    document.getElementById("toggle-locations-btn").textContent = "Show Locations";
    clearMarkers();
    clearPath();
    generateTrajectory();
  }
}

function displayFacilities() {
  const location = searchedLocation || userLocation;
  if (!location) {
    alert("Please enable location services or search for a location.");
    return;
  }

  clearMarkers();
  service.nearbySearch(
    {
      location,
      radius: 5000,
      keyword: "sports OR gym OR health",
    },
    (results, status) => {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
        results.forEach((place) => {
          addMarker(place.geometry.location, place.name);
        });
      } else {
        alert("No facilities found in the selected area.");
      }
    }
  );
}

function generateTrajectory() {
  const location = searchedLocation || userLocation;
  if (!location) {
    alert("Please enable location services or search for a location.");
    return;
  }

  const desiredDistance = parseFloat(document.getElementById("length").value) * 1000; // Convert km to meters

  clearPath(); // Clear any existing path

  service.nearbySearch(
    {
      location,
      radius: 5000,
      keyword: "jardin",
    },
    (results, status) => {
      if (status === google.maps.places.PlacesServiceStatus.OK && results.length > 0) {
        const waypoints = results
          .map((place) => ({
            location: place.geometry.location,
            stopover: true,
          }))
          .slice(0, 5); // Limit waypoints to 5 to avoid exceeding the desired distance

        calculateRoute(location, waypoints, desiredDistance);
      } else {
        console.log("No green areas found nearby. Generating a path based on distance only.");
        calculateRoute(location, [], desiredDistance); // Generate path without waypoints
      }
    }
  );
}

function calculateRoute(origin, waypoints, desiredDistance) {
  directionsService.route(
    {
      origin: origin,
      destination: origin, // Loop back to the starting point
      waypoints: waypoints,
      travelMode: google.maps.TravelMode.WALKING,
      optimizeWaypoints: true,
    },
    (response, status) => {
      if (status === google.maps.DirectionsStatus.OK) {
        const route = response.routes[0];
        const routeDistance = calculateRouteDistance(route);

        console.log(`Desired distance: ${desiredDistance}m, Route distance: ${routeDistance}m`);

        if (Math.abs(routeDistance - desiredDistance) <= 500) {
          directionsRenderer.setDirections(response);
        } else {
          console.log(
            `Route distance (${routeDistance}m) does not match desired distance (${desiredDistance}m). Retrying.`
          );
          adjustRouteToMatchDistance(origin, desiredDistance);
        }
      } else {
        alert("Directions request failed due to " + status);
      }
    }
  );
}

function calculateRouteDistance(route) {
  let totalDistance = 0;

  route.legs.forEach((leg) => {
    totalDistance += leg.distance.value; // Add leg distance in meters
  });

  return totalDistance;
}

function adjustRouteToMatchDistance(origin, desiredDistance) {
  const loopLeg = desiredDistance / 4;
  const offset = loopLeg / 100000;

  const waypoints = [
    { location: { lat: origin.lat + offset, lng: origin.lng }, stopover: true },
    { location: { lat: origin.lat, lng: origin.lng + offset }, stopover: true },
    { location: { lat: origin.lat - offset, lng: origin.lng }, stopover: true },
    { location: { lat: origin.lat, lng: origin.lng - offset }, stopover: true },
  ];

  directionsService.route(
    {
      origin: origin,
      destination: origin,
      waypoints: waypoints,
      travelMode: google.maps.TravelMode.WALKING,
    },
    (response, status) => {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsRenderer.setDirections(response);
      } else {
        alert("Unable to create a path matching the desired distance.");
      }
    }
  );
}
