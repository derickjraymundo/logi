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
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">Update Profile</h3>
            </div>
            <div class="card-body">
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                    <label>Profile Photo</label>
                                <div>
                                    <img id="profilePreview" src="<?php echo $_SESSION['SESS_USER_PHOTO']; ?>" alt="User Photo" class="img-thumbnail rounded-circle border" width="150" height="150">
                                </div>
                                <input type="file" class="form-control mt-2" name="profile_photo" accept="image/*"  onchange="previewImage(event)">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="lastname" value="<?php echo $_SESSION['SESS_USER_LASTNAME']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="firstname" value="<?php echo $_SESSION['SESS_USER_FIRSTNAME']; ?>"  required>
                            </div>
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" class="form-control" name="middlename" value="<?php echo $_SESSION['SESS_USER_MIDDLENAME']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Suffix</label>
                                <input type="text" class="form-control" name="suffix" value="<?php echo $_SESSION['SESS_USER_SUFFIX']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $_SESSION['SESS_USER_EMAIL']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="1" <?php echo ($_SESSION['SESS_USER_GENDER_ID']==1) ? "selected" : ""; ?> >Male</option>
                                    <option value="2" <?php echo ($_SESSION['SESS_USER_GENDER_ID']==2) ? "selected" : ""; ?> >Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                              <label>Branch</label>
                              <select class="form-control" name="branch" id="branch" required>
                                  <option value="">--Select Branch--</option>
                                  <?php
                                  try {
                                      $getAllbranch = $conn->prepare("SELECT id, branch_name FROM tbl_setup_branch WHERE isDeleted = :isDeleted");
                                      $getAllbranch->execute(['isDeleted' => 0]);
                                      $countgetAllbranch = $getAllbranch->rowCount();
                                      
                                      if ($countgetAllbranch == 0) {
                                          echo "<option value=''>No Branch on setup yet.</option>";
                                      } else {
                                          foreach ($getAllbranch as $rowgetAllbranch) {
                                              $selected = ($_SESSION['SESS_USER_BRANCH'] == $rowgetAllbranch['id']) ? "selected" : "";
                                              echo "<option value='" . $rowgetAllbranch['id'] . "' $selected>" . $rowgetAllbranch['branch_name'] . "</option>";
                                          }
                                      }
                                  } catch (PDOException $e) {
                                      echo $e->getMessage();
                                  }
                                  ?>
                              </select>
                          </div>
                            
                        </div>
                    </div>
                    <?php 
                            if (isset($_SESSION['success'])) {
                                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                                unset($_SESSION['success']);
                            } elseif (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                unset($_SESSION['error']);
                            }
                        ?>
                    <div class="float-right">
                      <button type="submit" class="btn btn-success" name="btnUpdateProfile">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title">Change Password</h3>
            </div>
            <div class="card-body">
                <form action="update_password.php" method="POST">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <?php 
                            if (isset($_SESSION['successpass'])) {
                                echo '<div class="alert alert-success">' . $_SESSION['successpass'] . '</div>';
                                unset($_SESSION['successpass']);
                            } elseif (isset($_SESSION['errorpass'])) {
                                echo '<div class="alert alert-danger">' . $_SESSION['errorpass'] . '</div>';
                                unset($_SESSION['errorpass']);
                            }
                        ?>
                    <div class="float-right">
                    <button type="submit" class="btn btn-danger">Change Password</button>
                    </div>
                
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profilePreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
