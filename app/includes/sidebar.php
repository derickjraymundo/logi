
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link">
      <img src="../images/logo.jpg" alt="Logo" class="brand-image img-circle elevation-3" style="width: 50px; height: 50px; object-fit: cover; opacity: .8;">
      <span class="brand-text font-weight-light"><?php echo $dev_projectname; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div> -->

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

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
              <a href="add_vehicle" class="nav-link  
                <?php echo (basename($_SERVER['PHP_SELF']) == 'add_vehicle.php' || basename($_SERVER['PHP_SELF']) == 'add_vehicle.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-plus"></i>
                  <p>Add Vehicle</p>
              </a>
          </li>


          <?php
          $stmtnotif1 = $conn->prepare("
              SELECT COUNT(*) AS nearly_expired_count
              FROM vehicles
              WHERE vehicle_lifespan IS NOT NULL 
              AND DATE_ADD(created_at, INTERVAL vehicle_lifespan MONTH) <= DATE_ADD(NOW(), INTERVAL 60 DAY)
          ");
          $stmtnotif1->execute();
          $notif1 = $stmtnotif1->fetch(PDO::FETCH_ASSOC)['nearly_expired_count'];
          ?>

          <li class="nav-item">
              <a href="view_vehicle" class="nav-link  
                <?php echo (basename($_SERVER['PHP_SELF']) == 'view_vehicle.php' || basename($_SERVER['PHP_SELF']) == 'view_vehicle.php') 
                
                  || (basename($_SERVER['PHP_SELF']) == 'vehicle_parts.php' || basename($_SERVER['PHP_SELF']) == 'vehicle_parts.php') 
                
                  || (basename($_SERVER['PHP_SELF']) == 'edit_vehicle.php' || basename($_SERVER['PHP_SELF']) == 'edit_vehicle.php')     ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-eye"></i>
                  <p>View Vehicle       
                      <?php if ($notif1 > 0) { ?>
                          <span class="badge badge-danger right"><?php echo $notif1; ?></span>
                      <?php } ?>
                    </p>
              </a>
          </li>

          <?php
          $stmtnotif2 = $conn->prepare("
              SELECT COUNT(*) AS nearly_expired_count
              FROM tbl_v_vehicles_parts
              WHERE vehicle_parts_lifespan IS NOT NULL 
              AND DATE_ADD(added_date, INTERVAL vehicle_parts_lifespan MONTH) <= DATE_ADD(NOW(), INTERVAL 60 DAY)
          ");
          $stmtnotif2->execute();
          $notif2 = $stmtnotif2->fetch(PDO::FETCH_ASSOC)['nearly_expired_count'];
          ?>

          <li class="nav-item">
              <a href="vehicle_lifespan.php" class="nav-link  
                  <?php echo (basename($_SERVER['PHP_SELF']) == 'vehicle_lifespan.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-cogs"></i>
                  <p>Vehicle Parts Lifespan 
                      <?php if ($notif2 > 0) { ?>
                          <span class="badge badge-danger right"><?php echo $notif2; ?></span>
                      <?php } ?>
                  </p>
              </a>
          </li>
            <!-- <li class="nav-item <?php echo in_array($current_page, ['fees_admin', 'fees_balance']) ? 'menu-open' : ''; ?>">
              <a href="#" class="nav-link <?php echo in_array($current_page, ['fees_admin', 'fees_balance']) ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-table"></i>
                <p>
                  Transactions
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="fees_admin" class="nav-link <?php echo $current_page == 'fees_admin' ? 'active' : ''; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Admin Fees</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="fees_balance" class="nav-link <?php echo $current_page == 'fees_balance' ? 'active' : ''; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Balances Managements</p>
                  </a>
                </li>
              </ul>
            </li> -->

          <?php
          $stmtnotif3 = $conn->prepare("
              SELECT COUNT(*) AS total_pending
              FROM tbl_vehicle_requests
              WHERE request_status = :request_status
          ");
          $stmtnotif3->execute(['request_status'=>'Pending']);
          $notif3 = $stmtnotif3->fetch(PDO::FETCH_ASSOC)['total_pending'];
          ?>
          <li class="nav-item">
              <a href="vehicle_request" class="nav-link  
                <?php echo (basename($_SERVER['PHP_SELF']) == 'vehicle_request.php' || basename($_SERVER['PHP_SELF']) == 'vehicle_request.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-taxi"></i>
                  <p>Vehicle Requests       
                     <?php if ($notif3 > 0) { ?>
                          <span class="badge badge-danger right"><?php echo $notif3; ?></span>
                      <?php } ?></p>
              </a>
          </li>

          <li class="nav-item">
              <a href="fuel_monitoring" class="nav-link  
                <?php echo (basename($_SERVER['PHP_SELF']) == 'fuel_monitoring.php' || basename($_SERVER['PHP_SELF']) == 'fuel_monitoring.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-book"></i>
                  <p>Fuel Monitoring</p>
              </a>
          </li>

          <li class="nav-item">
              <a href="driver_helper" class="nav-link  
                  <?php echo (basename($_SERVER['PHP_SELF']) == 'driver_helper.php' || basename($_SERVER['PHP_SELF']) == 'driver_map.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-car"></i>
                  <p>Driver Helper</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="performance_rating_mdl" class="nav-link  
                  <?php echo (basename($_SERVER['PHP_SELF']) == 'performance_rating_mdl.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-car"></i>
                  <p>Performance Rating</p>
              </a>
          </li>
          <?php
          // Fetch pending requests count
          try {
              $sql = "SELECT COUNT(*) AS pending_count FROM tbl_vehicle_rollouts WHERE status = 'Pending'";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              $pendingCount = $result['pending_count'];
          } catch (PDOException $e) {
              $pendingCount = 0; // Fallback in case of an error
          }
          ?>

          <li class="nav-item">
              <a href="vehicle_rollouts.php" class="nav-link  
                  <?php echo (basename($_SERVER['PHP_SELF']) == 'vehicle_rollouts.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-truck"></i>
                  <p>Vehicle Rollouts  
                      <?php if ($pendingCount > 0): ?>
                          <span class="badge badge-danger right"><?php echo $pendingCount; ?></span>
                      <?php endif; ?>
                  </p> 
              </a>
          </li>

          

          <li class="nav-header">Maintenance</li>
              <li class="nav-item">
              <a href="setup_vendor" class="nav-link  
                <?php echo (basename($_SERVER['PHP_SELF']) == 'setup_vendor.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-home"></i>
                  <p>Vendor</p>
              </a>
              </li>
          
              <li class="nav-item">
              <a href="setup_vehicle_manufacturers" class="nav-link  
                <?php echo (basename($_SERVER['PHP_SELF']) == 'setup_vehicle_manufacturers.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-building"></i>
                  <p>Make(Manufacturer)</p>
              </a>
              </li>
              <li class="nav-item">
              <a href="setup_vehicle_types" class="nav-link  
                <?php echo (basename($_SERVER['PHP_SELF']) == 'setup_vehicle_types.php') ? 'active' : ''; ?>">
                  <i class="nav-icon fa fa-car"></i>
                  <p>Vehicle Types</p>
              </a>
              </li>
              <li class="nav-item">
                  <a href="setup_vehicle_parts" class="nav-link  
                    <?php echo (basename($_SERVER['PHP_SELF']) == 'setup_vehicle_parts.php') ? 'active' : ''; ?>">
                      <i class="nav-icon fa fa-cogs"></i>
                      <p>Vehicle Parts</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="setup_users" class="nav-link  
                    <?php echo (basename($_SERVER['PHP_SELF']) == 'setup_users.php') ? 'active' : ''; ?>">
                      <i class="nav-icon fa fa-users"></i>
                      <p>Users</p>
                  </a>
              </li>
  

              
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
