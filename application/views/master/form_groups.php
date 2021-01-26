<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
<head>
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace-extra.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ptsi/js/jquery-2.2.0.js"></script>
</head>
<body >

<?php echo $error;?>


<form id="id-message-form" class="form-horizontal message-form col-xs-12" action="<?php echo base_url(); ?>master/save_groups" method="post" accept-charset="utf-8">

	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-subject">No:</label>
		<div class="col-sm-9 col-xs-12">
			<div class="input-icon block col-xs-12 no-padding">
				<input maxlength="100" type="text" class="col-xs-12" name="id" id="id" placeholder="NO" value="<?php echo $key_id; ?>" readonly />
				<i class="ace-icon fa fa-key"></i>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label no-padding-right" for="form-field-subject">Nama:</label>
		<div class="col-sm-9 col-xs-12">
			<div class="input-icon block col-xs-12 no-padding">
				<input maxlength="100" type="text" class="col-xs-12" name="name" id="name" placeholder="Nama" value="<?php echo isset($groups)?$groups->description:''; ?>"/>
				<i class="ace-icon fa fa-map-o"></i>
			</div>
		</div>
	</div>
	<br><br><br>
    <div class="messagebar-item-right">
    	<button type="button" class="btn btn-sm btn-warning" onClick="w1.close();">
    	<i class="ace-icon fa fa-times"></i>Tutup</button>
    	<button type="submit" class="btn btn-sm btn-success" >
    	<i class="ace-icon fa fa-floppy-o"></i>Simpan</button>
    </div>
</form>

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/template/ace/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/template/ace/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url(); ?>assets/template/ace/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url(); ?>assets/template/ace/js/bootstrap-tag.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/jquery.hotkeys.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/bootstrap-wysiwyg.js"></script>
				<!--[if lte IE 8]>
		  <script src="<?php echo base_url(); ?>assets/template/ace/js/excanvas.js"></script>
		<![endif]-->
		<script src="<?php echo base_url(); ?>assets/template/ace/js/jquery-ui.custom.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/jquery.ui.touch-punch.js"></script>
		
		
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/elements.wysiwyg.js"></script>

		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.ajax-content.js"></script>
		<script src="<?php echo base_url(); ?>assets/template/ace/js/ace/ace.touch-drag.js"></script>



</body>
</html>