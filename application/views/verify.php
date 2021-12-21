<!DOCTYPE html>
<html lang="en" ng-app="verifyApp">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="apple-touch-icon-precomposed">
  <link href="<?php echo base_url('public/');?>dist/img/favicon.png" rel="shortcut icon" type="image/png">
  <title>LEO Fitness Gym | Verify Code </title>

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
<body class="hold-transition login-page" ng-controller="verifyCtrl">
<div class="login-box">
  <div class="card card-outline card-info">
    <div class="card-header text-center">
      <a href="index.html" class="h1"><b>LEO</b>Fitness GYM</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Please enter 4 digit code? We have sent you on your email.</p>
      <form name="verifyForm" novalidate 
      action="http://localhost/gym/admin/verify" method="post">
        <div class="input-group">
          <input type="text" class="form-control" name="code" placeholder="1234" required="required"
          minlength="4" maxlength="4" pattern="^[0-9]*$" ng-model="ng_code">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="form-group" ng-show="verifyForm.code.$dirty && verifyForm.code.$invalid">
          <small class="text-danger" ng-show="verifyForm.code.$invalid">
            ( Only number allowed, no character accepted )
          </small>
          <br>
          <small class="text-danger" ng-show="verifyForm.code.$error.required">
            Required*
          </small>
          <small class="d-block text-danger" ng-show="((verifyForm.code.$error.minlength || verifyForm.code.$error.maxlength) && verifyForm.code.$dirty)">
            Code should be length of 4
          </small>
        </div>

        <?php if($this->session->flashdata('code_not_verify')){ ?>
        <div class="alert alert-danger mt-3" role="alert">
        <i class="fas fa-exclamation-circle mr-1"></i> <?php echo $this->session->flashdata('code_not_verify'); ?> !
        </div>
        <?php } ?>
        
        <div class="row mt-3">
          <div class="col-12">
            <button 
            ng-disabled="verifyForm.$invalid" 
            type="submit" data-callback='onSubmit' data-action='submit'
            class="btn btn-info btn-block">Verify Code</button>
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
  var verify_app = angular.module('verifyApp', ['ngRoute']);
  verify_app.controller('verifyCtrl', function($scope){
    console.log("Verify Controller");
  });
</script>
</body>
</html>
