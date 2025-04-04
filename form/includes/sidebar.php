  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link">
      <img src="../images/logo.jpg" alt="Logo" class="brand-image img-circle elevation-3"  style="width: 50px; height: 50px; object-fit: cover; opacity: .8;">
      <span class="brand-text font-weight-light"><?php echo $dev_projectname; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
   

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="dashboard" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="request_item" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'request_item.php' ? 'active' : ''; ?>">
              <i class="nav-icon fa fa-comment"></i>
              <p>
                Request
              </p>
            </a>
          </li>

          <?php
            // Fetch the count from the database
            $stmt = $conn->prepare("SELECT COUNT(id) AS count FROM tbl_driver_book WHERE driver_id = ? AND booking_status = 0");
            $stmt->execute([$_SESSION['SESS_USER_ID']]);
            $row = $stmt->fetch();
            $bookingCount = $row['count'];
            ?>

            <li class="nav-item">
                <a href="works" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'works.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fa fa-list-alt"></i>
                    <p>
                        Works
                        <?php if ($bookingCount > 0): ?>
                            <span class="badge badge-danger"><?php echo $bookingCount; ?></span>
                        <?php endif; ?>
                    </p>
                </a>  
            </li>
      

          <li class="nav-item">
            <a href="logi_fuelmonitoring" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'logi_fuelmonitoring.php' ? 'active' : ''; ?>">
              <i class="nav-icon fa fa-car"></i>
              <p>
                Fuel Monitoring
              </p>
            </a>  
          </li>

                
          <li class="nav-item">
                <a href="my_schedule" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'my_schedule.php' ? 'active' : ''; ?>">
                    <i class="nav-icon fa fa-calendar"></i>
                    <p>
                        My Schedule
                    </p>
                </a>  
            </li>

          <!-- <li class="nav-item">
            <a href="billing_statement" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'billing_statement.php' ? 'active' : ''; ?>">
              <i class="nav-icon fa fa-list-alt"></i>
              <p>
                Billing Statement
              </p>
            </a>
          </li> -->

       
    

            

        <!-- <li class="nav-item <?php echo in_array($current_page, ['setup_item', 'setup_port', 'setup_consignee', 'setup_flightno', 'setup_branch']) ? 'menu-open' : ''; ?>">
        <a href="#" class="nav-link <?php echo in_array($current_page, ['setup_item', 'setup_port', 'setup_consignee', 'setup_flightno', 'setup_branch']) ? 'active' : ''; ?>">
          <i class="nav-icon fa fa-cog"></i>
          <p>
            Maintenance
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="setup_item" class="nav-link <?php echo $current_page == 'setup_item' ? 'active' : ''; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Item</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="setup_port" class="nav-link <?php echo $current_page == 'setup_port' ? 'active' : ''; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Origin</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="setup_consignee" class="nav-link <?php echo $current_page == 'setup_consignee' ? 'active' : ''; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Consignee</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="setup_flightno" class="nav-link <?php echo $current_page == 'setup_flightno' ? 'active' : ''; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Flight No.</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="setup_branch" class="nav-link <?php echo $current_page == 'setup_branch' ? 'active' : ''; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Branch/ Facilities</p>
            </a>
          </li>
        </ul>
      </li> -->

  
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
