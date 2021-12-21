<!DOCTYPE html>
<html lang="en"  ng-app="forgotApp">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="apple-touch-icon-precomposed">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="shortcut icon" type="image/png">
  <title>LEO Fitness Gym | Forgot Password </title>

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
<body class="hold-transition login-page" ng-controller="forgotCtrl">
<div class="login-box">
  <div class="card card-outline card-info">
    <div class="card-header text-center">
      <a href="index.html" class="h1"><b>LEO</b>Fitness GYM</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <form name="forgotForm" novalidate action="http://localhost/gym/admin/forgot" method="post">
        <div class="input-group">
          <input type="email" class="form-control" name="email" placeholder="Email"
          required="required" 
          pattern="^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$" ng-model="ng_email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div ng-show="forgotForm.email.$dirty && forgotForm.email.$invalid">
          <small class="text-danger" ng-show="forgotForm.email.$invalid">
            Invalid Format!
          </small> <br>
          <small class="text-danger" ng-show="forgotForm.email.$error.required">
            Required*
          </small>
        </div>

        <?php if($this->session->flashdata('mail_not_sent')){ ?>
        <div class="alert alert-warning mt-3" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> <?php echo $this->session->flashdata('mail_not_sent'); ?> !
        </div>
        <?php } ?>

        <?php if($this->session->flashdata('request_error')){ ?>
        <div class="alert alert-info mt-3" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> <?php echo $this->session->flashdata('request_error'); ?> !
        </div>
        <?php } ?>

        <?php if($this->session->flashdata('email_not_found')){ ?>
        <div class="alert alert-danger mt-3" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> <?php echo $this->session->flashdata('email_not_found'); ?> !
        </div>
        <?php } ?>

        <div class="row mt-3">
          <div class="col-12">
            <button ng-disabled="forgotForm.$invalid" 
            type="submit" data-callback='onSubmit' data-action='submit'
            class="btn btn-info btn-block">
              Request new password
            </button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- <p class="mt-3 mb-1">
        <a href="login.html">Login</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->



<script>
  var forgot_app = angular.module('forgotApp', ['ngRoute']);
  forgot_app.controller('forgotCtrl', function($scope){
    console.log("Forgot Controller");
  });
</script>
</body>
</html>
