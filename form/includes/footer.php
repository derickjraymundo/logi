<footer class="main-footer">
    <strong>Copyright &copy; <?php echo $dev_project_start; ?> <a href="#"><?php echo $dev_projectname; ?></a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> <?php echo $dev_web_version; ?>
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<!-- <script src="../plugins/jquery-ui/jquery-ui.min.js"></script> -->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- ChartJS -->

<script src="../plugins/select2/js/select2.full.min.js"></script>
<script src="../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="dist/js/demo.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/sweetalert2/sweetalert2.js"></script>
<script src="../plugins/toastr/toastr.min.js"></script>
<script src="../plugins/datatables/datetime.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>

<script src="../custom.js"></script>


<script>
//   requestLocation();
  function requestLocation() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                // console.log("Location access granted!");
            },
            (error) => {
                if (error.code === error.PERMISSION_DENIED) {
                    // alert("Location access is denied. Please enable it manually.");
                    // Redirect to Android settings (Chrome only)
                    if (/android/i.test(navigator.userAgent)) {
                        window.location.href = "intent://settings/#Intent;scheme=android.settings.LOCATION_SOURCE_SETTINGS;end;";
                    }
                }
            }
        );
    } else {
        // console.log("Geolocation is not supported by your browser.");
    }
}

function getLocation() {
    if ("geolocation" in navigator) {
        // Get initial position first
        navigator.geolocation.getCurrentPosition(
            (position) => {
                // console.log("Initial Location Accessed.");
                trackLocation(); // Start real-time tracking
            },
            (error) => {
                // console.error("Geolocation Error: ", error.message);
                // console.log("Please enable location services in your phone settings.");
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        // console.log("Geolocation is not supported by your browser.");
    }
}

function trackLocation() {
    navigator.geolocation.watchPosition(
        (position) => {
            const latitude = position.coords.latitude.toFixed(6);
            const longitude = position.coords.longitude.toFixed(6);
            const accuracy = position.coords.accuracy.toFixed(2);

            // console.log(`Latitude: ${latitude}, Longitude: ${longitude}, Accuracy: ${accuracy}m`);

            // Send location via AJAX
            $.ajax({
                url: "updatedriverloc.php",
                method: "POST",
                data: { latitude, longitude },
                success: function(response) {
                    $("#sptestla").text(response);
                    // console.log(response);
                },
                error: function(xhr, status, error) {
                    // console.error("AJAX Error: ", error);
                }
            });
        },
        (error) => {
            // console.error("Error getting location: ", error.message);
            // console.log("Error getting location: " + error.message);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

// Call function on page load
getLocation();


</script>