<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<body class="light_theme  fixed_header left_nav_fixed">
<div class="wrapper">
  <!--\\\\\\\ wrapper Start \\\\\\-->
  
  <div class="login_page">
  <div class="login_content">
  <div class="panel-heading border login_heading">change password</div>	
  <div id="infoMessage"><?php echo $message;?></div>
 <form role="form" action="<?php echo base_url()."auth/change_password"; ?>" method="post" accept-charset="utf-8" class="form-horizontal">
      <div class="form-group">
        <div class="col-sm-10">
          <input type="password" placeholder="Old Password" id="old" name="old" class="form-control">
        </div>
      </div>
      <div class="form-group">        
        <div class="col-sm-10">
          <input type="password" placeholder="<?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?>" id="new" name="new" class="form-control">
        </div>
      </div>
      <div class="form-group">        
        <div class="col-sm-10">
          <input type="password" placeholder="Confirm New Password" id="new_confirm" name="new_confirm" class="form-control">
        </div>
      </div>
      <div class="form-group">
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
      </div>
      
    </form>
 </div>
  </div>
  
