<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');
?>
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Change Password</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<link href="<?php echo base_url(); ?>assets/template/ptsi/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/template/ptsi/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/template/ptsi/css/animate.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/template/ptsi/css/admin.css" rel="stylesheet" type="text/css" />
</head>
<body class="light_theme  fixed_header left_nav_fixed"> -->
<div class="row">
  <div class="col d-flex justify-content-center">
  <div class="col-4 col-md-4 col-lg-4">

    <div class="card">
      <div class="card-body">
        <div class="card-header justify-content-center">
            <h4>Ganti Password</h4>
          </div>
           <form role="form" action="<?php echo base_url()."auth/change_password"; ?>" method="post" accept-charset="utf-8" class="form-horizontal">
                <!-- <div class="form-group">
                  <div class="col-sm-10">
                    <input type="password" placeholder="Old Password" id="old" name="old" class="form-control">
                  </div>
                </div> -->
                <div class="form-group">
                    <label class="control-label col-sm-12">Password Lama<span style="color:red">*</span></label>
                    <div class="col-sm-12 input-group" id="show_hide_password">
                        <input id="old" name="old" placeholder="Password Lama" class="form-control" type="password" required>
                        <span class="input-group-addon">
                          <a href="" style="margin-left: -7px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Password Baru<span style="color:red">*</span></label>
                    <div class="col-sm-12 input-group" id="show_hide_password2">
                        <input data-toggle="password" id="new" name="new" placeholder="Password Baru" class="form-control" type="password" required>
                        <span class="input-group-addon">
                          <a href="#" style="margin-left: -7px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Konfirmasi Password Baru<span style="color:red">*</span></label>
                    <!-- <div class="col-sm-12">
                        <input id="new_confirm" name="new_confirm" placeholder="Konfirm Password Baru" class="form-control" type="password" required>
                        <span class="help-block"></span>
                    </div> -->
                    <div class="col-sm-12 input-group" id="show_hide_password3">
                        <input data-toggle="password" id="new_confirm" name="new_confirm" placeholder="Konfirm Password Baru" class="form-control" type="password" required>
                        <span class="input-group-addon">
                          <a href="#" style="margin-left: -7px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </span>
                    </div>
                </div>
                <div class="modal-footer2">
                  <center>
                      <button type="submit" class="btn btn-md btn-primary" id="btnSave" onclick="save()" >Ganti Password</button>
                  </center>
                  <!-- <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button> -->
              </div>
                <!-- <div class="form-group">        
                  <div class="col-sm-10">
                    <input type="password" placeholder="<?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?>" id="new" name="new" class="form-control">
                  </div>
                </div>
                <div class="form-group">        
                  <div class="col-sm-10">
                    <input type="password" placeholder="Confirm New Password" id="new_confirm" name="new_confirm" class="form-control">
                  </div>
                </div> -->
                <!-- <div class="form-group">
                  <div class=" col-sm-10">
          			<?php echo form_input($user_id);?>
                      <button class="btn btn-primary pull-right" type="submit" 
                      	name="submit">Change
                      </button>
                      <button class="btn btn-danger pull-right" type="button"  
                      	onclick="location.href='<?php echo base_url()."dashboard/"; ?>';">
                          <i class="fa fa-close"></i> Close
                      </button>
                  </div>
                </div> -->
                <!-- <div id="infoMessage"> -->
                  <?php if(!empty($message)):?>
                    <br />
                  <div class="form-group">
                    <div class="col-sm-10">
                      <?php echo $message;?>
                    </div>
                  </div> 
                <?php endif;?>
                <!-- </div> -->
              </form>
          </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
    $("#show_hide_password2 a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password2 input').attr("type") == "text"){
            $('#show_hide_password2 input').attr('type', 'password');
            $('#show_hide_password2 i').addClass( "fa-eye-slash" );
            $('#show_hide_password2 i').removeClass( "fa-eye" );
        }else if($('#show_hide_password2 input').attr("type") == "password"){
            $('#show_hide_password2 input').attr('type', 'text');
            $('#show_hide_password2 i').removeClass( "fa-eye-slash" );
            $('#show_hide_password2 i').addClass( "fa-eye" );
        }
    });
    $("#show_hide_password3 a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password3 input').attr("type") == "text"){
            $('#show_hide_password3 input').attr('type', 'password');
            $('#show_hide_password3 i').addClass( "fa-eye-slash" );
            $('#show_hide_password3 i').removeClass( "fa-eye" );
        }else if($('#show_hide_password3 input').attr("type") == "password"){
            $('#show_hide_password3 input').attr('type', 'text');
            $('#show_hide_password3 i').removeClass( "fa-eye-slash" );
            $('#show_hide_password3 i').addClass( "fa-eye" );
        }
    });
});
</script>
  
