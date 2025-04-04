
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

    <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <img src="<?php echo ($_SESSION['SESS_USER_PHOTO'] == "") ? "../images/user_photo/avatar3.png" : $_SESSION['SESS_USER_PHOTO']  ;?>" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline"><?php echo $_SESSION['SESS_USER_FIRSTNAME'] . " " . $_SESSION['SESS_USER_LASTNAME']; ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="<?php echo ($_SESSION['SESS_USER_PHOTO'] == "") ? "../images/user_photo/avatar3.png" : $_SESSION['SESS_USER_PHOTO']  ;?>" class="img-circle elevation-2" alt="User Image">

            <p>
            <?php echo ucfirst($_SESSION['SESS_USER_FIRSTNAME']) . " " . ucfirst($_SESSION['SESS_USER_LASTNAME']); ?> - <?php echo $_SESSION['SESS_USER_TYPE_NAME']; ?>
            <?php
            $originalDate = $_SESSION['SESS_USER_REGDATE']; // Example: "2024-06-27 22:36:04"
            $formattedDate = strtoupper(date('M. Y', strtotime($originalDate))); // Outputs: "JUN. 2024"
            ?>
            <small>Member since <?php echo $formattedDate; ?></small>
            </p>
          </li>
          <!-- Menu Body -->
          <!-- <li class="user-body">
            <div class="row">
              <div class="col-4 text-center">
                <a href="#">Followers</a>
              </div>
              <div class="col-4 text-center">
                <a href="#">Sales</a>
              </div>
              <div class="col-4 text-center">
                <a href="#">Friends</a>
              </div>
            </div>
   
          </li> -->
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="profile" class="btn btn-default btn-flat">Profile</a>
            <a href="../logout" class="btn btn-default btn-flat float-right">Sign out</a>
          </li>
        </ul>
    </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    
    </ul>
  </nav>
  <!-- /.navbar -->