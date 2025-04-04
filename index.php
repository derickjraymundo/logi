<?php
  include "includes/session.php";
  $conn = $pdo->open();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $dev_projectname; ?> | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="icon" type="image/x-icon" href="images/logo.jpg">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b><?php echo $dev_projectname; ?></b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

    <div class="text-center mb-3">
      <img src="images/logo.jpg" alt="Company Logo" class="img-fluid" style="max-width: 300px;">
    </div>
      <p class="login-box-msg">Sign in to start your session</p>

      <form id="frmlogin" method="post"  action="index_act.php">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" id="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" name="login" id="login">Sign In</button>
            <div id="div_result"></div>
          </div>
     
          <!-- /.col -->
        </div>
      </form>

      <div class="mt-2">

        <p class="mb-0">
          <a href="forgot-password" class="float-right">I forgot my password</a>
        </p>
        <!-- <p class="mb-0">
          <a href="register.html" class="text-center">Register</a>
        </p> -->
        </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<!-- <script src="dist/js/adminlte.min.js"></script> -->

<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="custom.js"></script>
<script>
        $(function () {
            $('form#frmlogin').validate({
                rules: {
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                },
                messages: {
                    email: {
                        required: "Please Enter Your Email",
                    },
                    password: {
                        required: "Please Enter Your Password",
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.mb-3').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        dataType: "json",
                        beforeSend: function () {
                            $("#div_result").html("");
                            $("#login").addClass("d-none");
                        },
                        success: function (response) {
                            $("#login").removeClass("d-none");
                            $("#div_result").html(responseMessge(response[0], response[1], response[2]));

                            if (response[0] == "success") {
                                setTimeout(function () { location.reload(); }, 2000);
                            }
                        },
                    });
                }
            });
        });
    </script>
</body>
</html>
