<?php 
    include "includes/session.php";
    include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Driver Helper</h1>
                </div>
            </div>
        </div>
    </section>
<!-- Modal -->
<div class="modal fade" id="addWorkModal" tabindex="-1" aria-labelledby="addWorkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addWorkModalLabel">Assign Work to Driver</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="workForm">
          <input type="hidden" id="work_driver_id" name="driver_id">

          <div class="mb-3">
            <label>Driver Name</label>
            <input type="text" class="form-control" id="work_driver_name" readonly>
          </div>

          <div class="mb-3 d-none">
            <label>From Location</label>
            <input type="text" class="form-control" id="from_location" name="from_location">
          </div>

          <div class="mb-3">
            <label>To Location</label>
            <input type="text" class="form-control" id="to_location" name="to_location" required>
          </div>

          <div class="mb-3 d-none">
            <label>To Long</label>
            <input type="text" class="form-control" id="to_long" name="to_long" required>
          </div>

          <div class="mb-3 d-none">
            <label>To Lat</label>
            <input type="text" class="form-control" id="to_lat" name="to_lat" required>
          </div>
          


          <div class="mb-3">
            <label>Date</label>
            <input type="date" class="form-control" id="work_date" name="booking_date"  required>
          </div>

          <div class="mb-3">
            <label>Time</label>
            <input type="time" class="form-control" id="work_time" name="booking_time"  required>
          </div>

          <div class="mb-3">
            <label>Purpose</label>
            <textarea class="form-control"  name="work_purpose" id="u"></textarea>
          </div>

          <hr>
  <?php
$query = "SELECT id, lastname, firstname, middlename FROM tbl_users WHERE user_type_id = 4 AND isDeleted = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$helpers = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

      <h6>Helpers</h6>
          <div id="helpersContainer">
            <div class="mb-3 helper-group">
              <select name="helper_id[]" class="form-control">
                <option value="">Select Helper</option>
                <?php foreach ($helpers as $helper): ?>
                  <option value="<?= $helper['id'] ?>">
                    <?= $helper['lastname'] . ', ' . $helper['firstname'] . ' ' . $helper['middlename'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <button type="button" class="btn btn-danger btn-sm removeHelperBtn">Remove</button>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-success" id="addHelperBtn">+ Add Helper</button>


          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Work</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="viewWorkModal" tabindex="-1" aria-labelledby="viewWorkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewWorkModalLabel">Work Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="workDetailsContent">
        <!-- Work details will be displayed here -->
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="realtimeLocationModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="realtimeLocationLabel">
          <i class="fas fa-map-marker-alt"></i> Driver Realtime Location
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="map" class="rounded"></div>
      </div>
    </div>
  </div>
</div>


    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Driver List</h3>
            </div>
            <div class="card-body">
            <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>Driver ID</th>
                      <th>Name</th>
                      <th>Contact Number</th>
                      <th>Email Address</th>
                      <th>Availability</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $query = "SELECT a.id, a.drivers_id, a.contact_number, a.email_address,
                                  a.lastname, a.firstname, a.middlename, a.rider_availability
                            FROM tbl_users a 
                            WHERE a.user_type_id = 3 AND a.isDeleted = 0";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  if (empty($rows)) {
                      echo "<tr><td colspan='6' class='text-center'>No Drivers Found</td></tr>";
                  } else {
                      foreach ($rows as $row) {
                          $availabilityIcon = $row['rider_availability'] == 1
                              ? "<i class='fas fa-circle text-success'></i> Available"
                              : "<i class='fas fa-circle text-danger'></i> Not Available";

                          $actionButton = $row['rider_availability'] == 1
                              ? "<button class='btn btn-primary btn-sm addWorkBtn' 
                                        data-driverid='{$row['id']}'
                                        data-drivername='{$row['lastname']}, {$row['firstname']} {$row['middlename']}'>
                                        Add Work
                                </button>"
                              : "<button class='btn btn-secondary btn-sm' disabled>Not Available</button>
                              
                                   <button class='btn btn-info btn-sm viewWorkBtn' data-driverid='{$row['id']}'>View</button>
                                     <button class='btn btn-warning btn-sm viewLocationBtn' data-driverid='{$row['id']}'>View Realtime Location</button>
                              ";

                          echo "<tr>
                                  <td>{$row['drivers_id']}</td>
                                  <td>{$row['lastname']}, {$row['firstname']} {$row['middlename']}</td>
                                  <td>{$row['contact_number']}</td>
                                  <td>{$row['email_address']}</td>
                                  <td>{$availabilityIcon}</td>
                                  <td>{$actionButton}</td>
                                </tr>";
                      }
                  }
                  ?>
              </tbody>
          </table>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</body>
</html>
<script>
var map; // Global map variable
var routeLine; // Store the route line globally
var startMarker, endMarker; // Store markers globally

document.getElementById("to_location").addEventListener("blur", function() {
    let location = this.value.trim(); // Get the location input

    if (location === "") {
        console.log("Please enter a location");

        // Clear longitude and latitude fields
        document.getElementById("to_long").value = "";
        document.getElementById("to_lat").value = "";
        return; // Stop execution if input is empty
    }

    let apiKey = "5b3ce3597851110001cf6248815c35339e694fde96ecb05c571c3629"; // Replace with your actual OpenRouteService API key
    let url = `https://api.openrouteservice.org/geocode/search?api_key=${apiKey}&text=${encodeURIComponent(location)}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.features.length > 0) {
                let coordinates = data.features[0].geometry.coordinates;
                let longitude = coordinates[0];
                let latitude = coordinates[1];

                // Set values in the longitude and latitude input fields
                document.getElementById("to_long").value = longitude;
                document.getElementById("to_lat").value = latitude;

                console.log(`Saved: Latitude: ${latitude}, Longitude: ${longitude}`);
            } else {
                console.log("Location not found!");

                // Clear longitude and latitude fields if location is not found
                document.getElementById("to_long").value = "";
                document.getElementById("to_lat").value = "";
            }
        })
        .catch(error => {
            console.error("Error:", error);

            // Clear fields in case of an error
            document.getElementById("to_long").value = "";
            document.getElementById("to_lat").value = "";
        });
});

 document.addEventListener("DOMContentLoaded", function () {
    // let map;
    // let marker;
    // let routePath = [];
    // let polyline;

    // function initMap() {
    //     map = new google.maps.Map(document.getElementById("map"), {
    //         center: { lat: 0, lng: 0 }, // Default location
    //         zoom: 15,
    //     });

    //     marker = new google.maps.Marker({
    //         position: { lat: 0, lng: 0 },
    //         map: map,
    //         title: "Driver's Location",
    //     });

    //     polyline = new google.maps.Polyline({
    //         path: routePath,
    //         geodesic: true,
    //         strokeColor: "#FF0000",
    //         strokeOpacity: 1.0,
    //         strokeWeight: 2,
    //     });
    //     polyline.setMap(map);
    // }

    document.querySelectorAll(".viewLocationBtn").forEach(button => {
        button.addEventListener("click", function () {
            let driverId = this.dataset.driverid;
            let chk_status = "";
           

            $.ajax({
              url : "check_status_book.php",
              method : "post",
              dataType :"json",
              data : {
                chk_status, driverId
              },
              success : function(response) {
                  if(response[0] == 0) {
                    alert("Request is not viewed or approved by driver yet.");
                  }else {
                    //  new bootstrap.Modal(document.getElementById("realtimeLocationModal")).show();
                    location.href=`driver_map.php?driver=${driverId}`;
                    // fetchDriverLocation(driverId);
                  }
              }

            });
      
        });
    });



    function fetchDriverLocation(driverId) {
    alert("Fetching location for Driver ID: " + driverId);

    fetch(`get_driver_location.php?driverId=${driverId}`)
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Driver not found");
            return;
        }

        let start = [parseFloat(data.froms_lat), parseFloat(data.froms_long)];
        let end = [parseFloat(data.tos_lat), parseFloat(data.tos_long)];

        // **Check if the map exists**
        if (!map) {
            map = L.map('map').setView(start, 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        } else {
            map.setView(start, 15); // Recenter on start
        }

        // **Clear previous markers and route**
        if (startMarker) map.removeLayer(startMarker);
        if (endMarker) map.removeLayer(endMarker);
        if (routeLine) map.removeLayer(routeLine);

        // **Add Markers for Driver's Start & Destination**
        startMarker = L.marker(start).addTo(map).bindPopup("Driver Start Location").openPopup();
        endMarker = L.marker(end).addTo(map).bindPopup("Driver Destination");

        // **Fetch and Draw Route**
        fetch(`proxy.php?start=${data.froms_long},${data.froms_lat}&end=${data.tos_long},${data.tos_lat}`)
        .then(response => response.json())
        .then(routeData => {
            if (!routeData.features || routeData.features.length === 0) {
                alert("No route found. Try different locations.");
                return;
            }

            var coords = routeData.features[0].geometry.coordinates;
            var latlngs = coords.map(coord => [coord[1], coord[0]]); 

            // **Draw Route on Map**
            routeLine = L.polyline(latlngs, { color: 'blue', weight: 5 }).addTo(map);
            map.fitBounds(routeLine.getBounds());
        })
        .catch(error => console.error("Error fetching route:", error));

    })
    .catch(error => console.error("Error fetching driver location:", error));
}

// **Fix Map Size When Modal Opens**
document.getElementById('realtimeLocationModal').addEventListener('shown.bs.modal', function () {
    if (map) {
        setTimeout(() => {
            map.invalidateSize(); // Fix any resizing issues
        }, 500);
    }
});
// **Ensure map is properly initialized when modal opens**
document.getElementById('realtimeLocationModal').addEventListener('shown.bs.modal', function () {
    if (map) {
        map.invalidateSize(); // Fix any resizing issues when modal opens
    }
});

        // fetch("fetch_driver_location.php?driver_id=" + driverId)
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             let position = { lat: parseFloat(data.latitude), lng: parseFloat(data.longitude) };
        //             map.setCenter(position);
        //             marker.setPosition(position);
        //             routePath.push(position);
        //             polyline.setPath(routePath);
        //         }
        //     })
        //     .catch(error => console.error("Error fetching location:", error));


    // initMap();

    // setInterval(() => {
    //     let driverId = document.querySelector(".viewLocationBtn")?.dataset.driverid;
    //     if (driverId) {
    //         fetchDriverLocation(driverId);
    //     }
    // }, 5000); 
});


 document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".viewWorkBtn").forEach(button => {
        button.addEventListener("click", function () {
            let driverId = this.dataset.driverid;
            fetch("fetch_work_details.php?driver_id=" + driverId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("workDetailsContent").innerHTML = data;
                    new bootstrap.Modal(document.getElementById("viewWorkModal")).show();
                })
                .catch(error => console.error("Error fetching work details:", error));
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    let helpersDropdownOptions = "";

    // Fetch helper options from PHP
    fetch("fetch_helpers.php")
        .then(response => response.json())
        .then(data => {
          helpersDropdownOptions = data.map(helper => 
              `<option value="${helper.id}" 
                      data-lastname="${helper.lastname}" 
                      data-firstname="${helper.firstname}" 
                      data-middlename="${helper.middlename}">
                  ${helper.lastname}, ${helper.firstname} ${helper.middlename || ""}
              </option>`
          ).join("");
        })
        .catch(error => console.error("Error fetching helpers:", error));

    // Handle 'Add Work' button click
    document.querySelectorAll(".addWorkBtn").forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("work_driver_id").value = this.dataset.driverid;
            document.getElementById("work_driver_name").value = this.dataset.drivername;
            document.getElementById("helpersContainer").innerHTML = ""; // Clear previous helpers
            new bootstrap.Modal(document.getElementById("addWorkModal")).show();
        });
    });

    // Handle adding multiple helpers (Now as a dropdown)
    document.getElementById("addHelperBtn").addEventListener("click", function () {
        const helperHTML = `
            <div class="helper-group border p-2 mb-2">
                <div class="row">
                    <div class="col-md-10">
                        <select class="form-control helper-select" name="helpers[]" required>
                            <option value="">Select Helper</option>
                            ${helpersDropdownOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm removeHelperBtn">Remove</button>
                    </div>
                </div>
            </div>`;
        
        document.getElementById("helpersContainer").insertAdjacentHTML("beforeend", helperHTML);
    });

    // Remove helper field
    document.getElementById("helpersContainer").addEventListener("click", function (e) {
        if (e.target.classList.contains("removeHelperBtn")) {
            e.target.closest(".helper-group").remove();
        }
    });

    // Handle form submission with AJAX
    document.getElementById("workForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let helpers = [];

        document.querySelectorAll(".helper-select").forEach(select => {
        let selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            helpers.push({
                id: selectedOption.value,
                lastname: selectedOption.getAttribute("data-lastname"),
                firstname: selectedOption.getAttribute("data-firstname"),
                middlename: selectedOption.getAttribute("data-middlename") || ""
            });
        }
    });

        formData.append("helpers", JSON.stringify(helpers));

        fetch("save_booking.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text()) // Change to text() instead of json()
        .then(data => {
            console.log("Server Response:", data); // Debugging: Show full response in the console
            try {
                let jsonData = JSON.parse(data); // Try parsing as JSON
                if (jsonData.success) {
                    alert("Work Assigned Successfully!");
                    location.reload();
                } else {
                    alert("Error: " + jsonData.message);
                }
            } catch (error) {
                console.error("Invalid JSON:", data); // Debugging: Show invalid JSON
                alert("Unexpected response from server. Check console.");
            }
        })
        .catch(error => console.error("Fetch Error:", error));
    });
});

</script>