<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>COSMIC</title>
	<link rel="icon" href="<?php echo base_url(); ?>assets/images/logocosmic.png" type="image/x-icon" /> 
	<meta name="description" content="overview &amp; stats" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<!-- 
	<link href="<?php //echo base_url(); ?>assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/new-datatables/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/new-datatables/buttons.dataTables.min.css" />
	
	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/font-awesome.css" />

	<!-- page specific plugin styles -->

	<!-- text fonts -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/ace-fonts.css" />

	<!-- ace styles -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/ace-skins.css" class="ace-main-stylesheet" id="main-ace-style" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css" />
	<style>
	div.gridbox {
	  -webkit-box-sizing: content-box;
		 -moz-box-sizing: content-box;
			  box-sizing: content-box;
	}
	</style>
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
    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
    <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script>
	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
	<script src="<?php echo base_url(); ?>assets/template/ace/js/html5shiv.js"></script>
	<script src="<?php echo base_url(); ?>assets/template/ace/js/respond.js"></script>
	<![endif]-->
	<style> .navbar:focus {
    	width:70px;
    	height:74px;
    }
    
    .dt-buttons .ExportDialog{right: -5px !important;}
    
    </style>
</head>
<body class="skin-3 no-skin" >
	<div id="navbar" class="navbar navbar-defaultxx">
		<script type="text/javascript">
			try{ace.settings.check('navbar' , 'fixed')}catch(e){}
		</script>
		<div id="navbar-container">
			<div class="navbar-buttons navbar-header pull-left" >
				 &nbsp;&nbsp;&nbsp;<img style="width:50%" src="<?php echo base_url(); ?>assets/login/LogoShort.png">
			</div>
		    <!-- #section:basics/sidebar.mobile.toggle -->
			<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
				<span class="sr-only">Toggle sidebar</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<!-- /section:basics/sidebar.mobile.toggle -->
			<div class="navbar-buttons navbar-header pull-right" role="navigation" >
				<ul class="nav ace-nav" >
					<li class="white"></li>
					<!-- #section:basics/navbar.user_menu -->
					<li class="navbar" >
						<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<?php if($user->foto !='' or $user->foto!=null){ ?>
						<img class="nav-user-photo" src="<?php echo base_url().'uploads/profile/'.$user->foto; ?>" />
						<?php }else{?>
						<img class="nav-user-photo" src="<?php echo base_url().'uploads/profile/bumn.png'; ?>" />
						<?php }?>
							<span class="user-info">
								<b><small>Selamat datang,</small>
								<?php echo $user->first_name.' '.$user->last_name; ?></b>
							</span>
							<i class="ace-icon fa fa-caret-down"></i>
						</a>
						<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
							<li> <a href="<?php echo base_url(); ?>auth/change_password"><i class="ace-icon fa fa-key"></i> Change Password</a> </li> 
								<?php 
// 								if ($this->ion_auth->is_admin()){
// 									echo '<li> <a href="'. base_url().'admin/"><i class="ace-icon fa fa-cog"></i> Setting </a></li>';
// 								}
								?>
							<li class="divider"></li>
							<li> <a href="<?php echo base_url(); ?>auth/logout"><i class="fa fa-power-off"></i>&nbsp;&nbsp;&nbsp;&nbsp;Logout</a></li>
							
						</ul>
					</li>
					<!-- /section:basics/navbar.user_menu -->
				</ul>
			</div>
			<!-- /section:basics/navbar.dropdown -->
		</div><!-- /.navbar-container -->
	</div>

	<!-- /section:basics/navbar.layout -->
	<div class="main-container" id="main-container">
		<script type="text/javascript">
			try{ace.settings.check('main-container' , 'fixed')}catch(e){}	
		</script>

		<!-- #section:basics/sidebar -->
		<div id="sidebar" class="sidebar responsive compact">
			<script type="text/javascript">
				try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				
			</script>

			<ul class="nav nav-list">
<?php
		  $groupHeader="";
		  foreach ($menu->result() as $row)
	   { 
			if ($groupHeader!=$row->nameref)
			{
				if ($groupHeader!="")
					echo '</ul></li>';
				//echo '<ul>';
				if ($title==$row->nameref){
					echo '<li class="active hover">' ;
				} else {
					echo '<li class="hover">' ;
				}
				echo '<a href="#" class="dropdown-toggle">
							<div class="pull-leftxx">&nbsp;&nbsp;&nbsp;</div>
							<div class="pull-left">&nbsp;&nbsp;&nbsp;<i class="menu-icon fa '.$row->imgref.' mr-20"></i></div>
							<span class="menu-text">&nbsp;&nbsp;
							 '.$row->nameref.' </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>
						<ul class="submenu">';
				$groupHeader=$row->nameref;
			}
	   ?>
		
   
           
	<li class="">
		<a href="<?php echo base_url().''.$row->menu_url; ?>">
			<i class="menu-icon fa fa-caret-right"></i>
			<?php echo $row->menu_name; ?>
		</a>
		<b class="arrow"></b>
	</li>
<?php 
	} 
	
	?>
	</ul>
      </li>
	</ul>
			<!-- #section:basics/sidebar.layout.minimize -->
			<!-- <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
				<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
			</div> -->

			<!-- /section:basics/sidebar.layout.minimize -->
			<script type="text/javascript">
				try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
			</script>
		</div>

		<!-- /section:basics/sidebar -->
		<div class="main-content">
			<div class="main-content-inner">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="<?php echo base_url(); ?>">Home</a>
						</li>
						<li class="active"><?php echo $title;?></li>
						<li class="active"><?php echo $subtitle;?></li>
					</ul><!-- /.breadcrumb -->

					<!-- #section:basics/content.searchbox -->
					<div class="nav-search" id="nav-search">
						<!--<form class="form-search">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" name="nav-search-input" autocomplete="off" onkeydown="if (event.keyCode == 13) { RefreshAndFilter(); return false; }" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
						</form> -->
					</div><!-- /.nav-search -->

					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content" >

					<div class="row">
					
					