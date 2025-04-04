<?php 
    include "includes/session.php";
    include "includes/header.php"; 
    include "includes/sidebar.php"; 
    include "includes/topbar.php";

    $stmt = $conn->prepare("SELECT * FROM tbl_driver_book WHERE driver_id =:driver_id AND booking_status NOT IN('2','3')");
    $stmt->execute(['driver_id'=>$_GET['driver']]);
    $ftc =$stmt->fetch();

    $from_lat = $ftc['froms_lat'];
    $from_long = $ftc['froms_long'];

    $to_lat = $ftc['tos_lat'];
    $to_long = $ftc['tos_long'];

    $to_loc = $ftc['tos'];
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h6>
                    <strong>Route:</strong> 
                    <span class="text-primary"><i class="fas fa-map-marker-alt"></i> From: <span id="txtfrom"></span></span> 
                    <span class="mx-2">➝</span> 
                    <span class="text-danger"><i class="fas fa-location-arrow"></i> To: <?php echo $to_loc; ?></span>
                </h6>
            </div>

            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header d-flex p-0">
                      <h3 class="card-title p-3">Map View</h3>
                      <div class="ml-auto p-2">
                          <a href="javascript:history.back()" class="btn btn-danger btn-sm">Go Back</a>
                      </div>
                  </div>


                        <div class="card-body">
                            <div id="map" style="height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include "includes/footer.php"; ?>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
var map = L.map('map').setView([14.728829287890006, 121.04168472494896], 12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var startLat = parseFloat("<?php echo isset($from_lat) ? $from_lat : '0'; ?>");
var startLong = parseFloat("<?php echo isset($from_long) ? $from_long : '0'; ?>");
var endLat = parseFloat("<?php echo isset($to_lat) ? $to_lat : '0'; ?>");
var endLong = parseFloat("<?php echo isset($to_long) ? $to_long : '0'; ?>");

console.log("Start Coordinates:", startLat, startLong);
console.log("End Coordinates:", endLat, endLong);



if (!isNaN(startLat) && !isNaN(startLong) && startLat !== 0 && startLong !== 0) {
    L.marker([startLat, startLong]).addTo(map).bindPopup("Start Location").openPopup();
}

if (!isNaN(endLat) && !isNaN(endLong) && endLat !== 0 && endLong !== 0) {
    L.marker([endLat, endLong]).addTo(map).bindPopup("End Location");
}

fetch(`proxy.php?start=${startLong},${startLat}&end=${endLong},${endLat}`)
.then(response => response.json())
.then(data => {
    console.log("Route Data:", data);
    if (!data.features || data.features.length === 0) {
        alert("No route found. Try different locations.");
        return;
    }
    var coords = data.features[0].geometry.coordinates;
    var latlngs = coords.map(coord => [coord[1], coord[0]]);
    var routeLine = L.polyline(latlngs, { color: 'blue', weight: 5 }).addTo(map);
    map.fitBounds(routeLine.getBounds());
})
.catch(error => console.error("Error fetching route:", error));

getAddress(<?php echo isset($from_long) ? $from_long : '0'; ?>, <?php echo isset($from_lat) ? $from_lat : '0'; ?>);
 

function getAddress(latitude, longitude) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // console.log("Address:", data.display_name);
            // alert("Address: " + data.display_name);
            $("#txtfrom").text(data.display_name);
        })
        .catch(error => console.error("Error fetching address:", error));
}

</script>
