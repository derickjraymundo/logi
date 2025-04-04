  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link">
      <img src="../images/logo.jpg" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
            <a href="view_vehicle" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'view_vehicle.php' ? 'active' : ''; ?>">
              <i class="nav-icon fa fa-eye"></i>
              <p>
                View Vehicle
              </p>
            </a>
          </li>

               
          <li class="nav-item">
            <a href="vehicle_lifespan" class="nav-link  <?php echo basename($_SERVER['PHP_SELF']) == 'vehicle_lifespan.php' ? 'active' : ''; ?>">
              <i class="nav-icon fa fa-cogs"></i>
              <p>
                Vehicle Lifespan
              </p>
            </a>
          </li>

    

          <!-- <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v3</p>
                </a>
              </li>
            </ul>
          </li> -->

    
<!-- 
            <li class="nav-item <?php echo in_array($current_page, ['cargoes_import', 'cargoes_released', 'cargoes_overstaying']) ? 'menu-open' : ''; ?>">
              <a href="#" class="nav-link <?php echo in_array($current_page, ['cargoes_import', 'cargoes_released', 'cargoes_overstaying']) ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-table"></i>
                <p>
                  Transactions
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="cargoes_import" class="nav-link <?php echo $current_page == 'cargoes_import' ? 'active' : ''; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Import Cargoes</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="cargoes_released" class="nav-link <?php echo $current_page == 'cargoes_released' ? 'active' : ''; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Released Cargoes</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="cargoes_overstaying" class="nav-link <?php echo $current_page == 'cargoes_overstaying' ? 'active' : ''; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Overstaying Cargoes</p>
                  </a>
                </li>
              </ul>
            </li> -->
          <!-- <li class="nav-header">Maintenance</li> -->

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
