<!DOCTYPE html>
<html lang="en" ng-app="loginApp">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="apple-touch-icon-precomposed">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="shortcut icon" type="image/png">
  <title>LEO Fitness Gym | Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('public/');?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url('public/');?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('public/');?>dist/css/adminlte.min.css">

  
<!-- jQuery -->
<script src="<?php echo base_url('public/');?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('public/');?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('public/');?>dist/js/adminlte.min.js"></script>

<script src="<?php echo base_url();?>public/plugins/angular-1.8.0/angular.min.js"></script>
<script src="<?php echo base_url();?>public/plugins/angular-1.8.0/angular-route.min.js"></script>
</head>
<body class="hold-transition login-page"  >
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-info shadow" ng-controller="loginCtrl">
    <div class="card-header text-center">
      <a href="" class="h1"><b>LEO</b>Fitness GYM</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form name="loginForm" novalidate
      method="POST" action="http://localhost/gym/admin/login"  enctype="multipart/form-data">
        <div class="input-group">
          <input type="email" class="form-control" placeholder="Email" name="username"
          required="required"
          pattern="^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$" ng-model="ng_email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div ng-show="loginForm.username.$dirty && loginForm.username.$invalid">
          <small class="text-danger" ng-show="loginForm.username.$invalid">
            Invalid Format!
          </small> <br>
          <small class="text-danger" ng-show="loginForm.username.$error.required">
            Required*
          </small>
        </div>
        
        <div class="input-group mt-3">
          <input type="password" class="form-control" placeholder="Password" id="pass" name="password" 
          required="required" minlength="8"  maxlength="12" ng-model="ng_pass">
          <div class="input-group-append">
            <div class="input-group-text" onclick="myFunction()">
              <span class="fas fa-eye"></span>
            </div>
          </div>
        </div>

        <div ng-show="loginForm.password.$dirty && loginForm.password.$invalid">
          <small class="text-danger" ng-show="loginForm.password.$invalid">                            
            Invalid!
          </small> <br>
          <small class="text-danger" ng-show="loginForm.password.$error.required">
            Required*
          </small>
        </div>

        <?php if($this->session->flashdata('error')){ ?>
        <div class="alert alert-danger mt-2" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> <?php echo $this->session->flashdata('error'); ?> !
        </div>
        <?php } ?>
        <!-- <div class="row">
          <div class="col-12 text-right">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Remember Me</label>
            </div>

          </div>
        </div> -->
        <div class="row">
          <div class="col-12 mt-2">
            <button 
            ng-disabled="loginForm.$invalid"
            type="submit" data-callback='onSubmit' data-action='submit' 
            class="btn btn-block btn-info btn-block">
              Sign In
            </button>
          </div>
        </div>
      </form>

      <p class="mb-1 mt-2">
        <a href="<?php echo base_url('admin/');?>forgot">I forgot my password</a>
      </p>
      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->


<script type="text/javascript">
  var pass = document.getElementById('pass');


  function myFunction() {
    if (pass.type === "password") {
      pass.type = "text";
    } else {
      pass.type = "password";
    }
  } 

</script>
<script>
  var login_app = angular.module('loginApp', ['ngRoute']);
  login_app.controller('loginCtrl', function($scope){
    console.log("Login Controller");
  });
</script>
</body>
</html>
