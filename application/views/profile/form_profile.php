<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   </style>
    </head> 
<body>
<div class="container">
    <form action="<?php base_url("profile/") ?>" method="post"
    	enctype="multipart/form-data">
    	<div class="form-group">
    		<label for="name">Name*</label>
    		<input class="form-control <?php echo form_error('hp') ? 'is-invalid':'' ?>"
    		 type="text" name="hp" placeholder="Hp" value="<?php echo $user->no_hp; ?>" />
    		<div class="invalid-feedback">
    			<?php echo form_error('hp') ?>
    		</div>
    	</div>
    	<div class="form-group">
    		<label for="name">Photo</label>
    		<input class="form-control-file <?php echo form_error('image') ? 'is-invalid':'' ?>"
    		 type="file" name="image" />
    		<input type="hidden" name="old_image" value="<?php echo $user->foto ?>" />
    		<div class="invalid-feedback">
    			<?php echo form_error('image') ?>
    		</div>
    	</div>
    	<input class="btn btn-success" type="submit" name="btn" value="Save" />
    </form>
</div>
</body>
</html>
