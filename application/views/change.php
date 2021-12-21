<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="apple-touch-icon-precomposed">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="shortcut icon" type="image/png">
  <title>LEO Fitness Gym | Change Password </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('public/');?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url('public/');?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('public/');?>dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-info">
    <div class="card-header text-center">
      <a href="index2.html" class="h1"><b>LEO</b>Fitness GYM</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
      <form action="http://localhost/gym/admin/change_pass" method="post">
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="new_pass" placeholder="New Password" id="pass">
          <div class="input-group-append">
            <div class="input-group-text" onclick="myFunction()">
              <span class="fas fa-eye" ></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="pass" placeholder="Confirm Password" id="re_pass">
          <div class="input-group-append">
            <div class="input-group-text" onclick="mySecondFunction()">
              <span class="fas fa-eye"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-info btn-block">Change password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

     
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url('public/');?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('public/');?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('public/');?>dist/js/adminlte.min.js"></script>

<script type="text/javascript">
  var pass = document.getElementById('pass');
  var re_pass = document.getElementById('re_pass');
  

  function myFunction() {
    if (pass.type === "password") {
      pass.type = "text";
    } else {
      pass.type = "password";
    }
  } 

  function mySecondFunction() {
    if (re_pass.type === "password") {
      re_pass.type = "text";
    } else {
      re_pass.type = "password";
    }
  } 

</script>
</body>
</html>
