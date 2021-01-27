<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>:: COSMIC ::</title>
  <link rel="icon" href="<?php echo base_url(); ?>assets/images/CosmicLogo1024.png" type="image/x-icon" />
  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
 <!-- CSS Libraries -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/weathericons/css/weather-icons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/weathericons/css/weather-icons-wind.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/summernote/dist/summernote-bs4.css"> -->
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css">
  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/components.css">


  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/ace/css/font-awesome.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css" />

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new-datatables/jquery.dataTables.min.css" />
   <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.1/css/datepicker.css" rel="stylesheet"/>


<!--   <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
  <script type="text/javascript" src="<?php echo base_url(); ?>assets_stisla/jquery-3.5.1.js"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets_stisla/js/stisla.js"></script>

<script src="<?php echo base_url(); ?>assets/template/ace/js/bootstrap.js"></script>

  <!-- JS Libraies -->
 <!--  <script src="<?php echo base_url(); ?>assets_stisla/node_modules/simpleweather/jquery.simpleWeather.min.js"></script> -->

  <!-- Template JS File -->
  <script src="<?php echo base_url(); ?>assets_stisla/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets_stisla/js/custom.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/main.js"></script>

  <!-- <script src="<?php echo base_url(); ?>assets/template/ptsi/js/jquery-2.2.0.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
    <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.print.min.js"></script>
</head>
<style type="text/css">
  .dt-buttons .ExportDialog{margin-bottom: 5px; right: -5px !important;}
  .dataTables_wrapper .dt-buttons {
      margin-left: 2px;
      margin-bottom: -26px;
    }
    @media screen and (max-width: 767px){
      .dt-buttons .ExportDialog{margin-bottom: 22px !important;}
    }
    @media screen and (max-width: 640px){
      .dt-buttons .ExportDialog{margin-bottom: 22px !important;}
    }
</style>
<?php ?>
<?php if(isset($menu_hide)){?>
  <body class="sidebar-mini">
<?php }else{?>
  <body>
<?php }?>
<div id="throbber" class="modal" role="dialog" style="display:none; position:relative; opacity:0.6; background-color:white;">
	    <img style="margin: 0 auto;
	                position: absolute;
	                top: 0; bottom: 0; left:0; right:0;
	                margin: auto;
	                display: block;
	               " src="https://icon-library.net//images/loading-icon-animated-gif/loading-icon-animated-gif-19.jpg" />
	</div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
          </ul>
          
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
          	<?php if($user->foto !='' or $user->foto!=null){ ?>
					<img class="rounded-circle mr-1" src="<?php echo base_url().'uploads/profile/'.$user->foto; ?>" />
			<?php }else{?>       
					<img class="rounded-circle mr-1" src="<?php echo base_url().'assets/images/user.png'; ?>" />
      <?php }?>
            <div class="d-sm-none d-lg-inline-block">Selamat datang, <?php echo $user->first_name.' '.$user->last_name; ?></div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <!-- <div class="dropdown-title">Logged in 5 min ago</div> -->
             <a href="<?php echo base_url(); ?>auth/change_password" class="dropdown-item has-icon"><i class="fas fa-key"></i>Change Password</a> 
			<?php 
// 			  if ($this->ion_auth->is_admin()){
// 				echo '<li> <a href="'. base_url().'admin/"><i class="ace-icon fa fa-cog"></i> Setting </a></li>';
// 			  }
			?>

           
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>auth/logout" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i>Logout</a>
              
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?php echo base_url(); ?>dashmin"><img style="width:50%" src="<?php echo base_url(); ?>assets/login/LogoShort.png"></a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?php echo base_url(); ?>dashmin"><img style="width:60%" src="<?php echo base_url(); ?>assets/images/CosmicLogo1024.png"></a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">&nbsp;</li>
            <?php
			  $groupHeader="";
			  foreach ($menu->result() as $row)
		   { 
				if ($groupHeader!=$row->nameref)
				{
					if ($groupHeader!="")
						echo '</ul></li>';
					//echo '<ul>';
					if (trim($title)==trim($row->nameref)){
						//echo '<li class="active highlight">' ;
						echo '<li class="dropdown active">' ;
					} else {
						echo '<li class="dropdown">' ;
					}										

					$icon = $row->imgref;
					if ($title != "Monitoring Usage"){
						if($row->menu_id=='2'){
							$icon = 'fa-fire';
						}elseif($row->menu_id=='7'){
							$icon = 'fa-file-excel';
						}
						$icon_image = '<i class="fas '.$icon.' mr-20"></i>';
					}else{
						$icon_image = '<img src="'.base_url().'assets/images/icons/'.$icon.'" class="mr-2 ml-2"/>';
					}

					$dropdown = $title == "Monitoring Usage" ? "" : "has-dropdown";
					echo '<a href="#" class="nav-link '.$dropdown.'" data-toggle="dropdown">'
								.$icon_image.
								'<span class="menu-text">
								 '.$row->nameref.' </span>

								<!--b class="arrow fa fa-angle-down"></b-->
							</a>

							<b class="arrow"></b>
							<ul class="dropdown-menu">';
					$groupHeader=$row->nameref;
				}
		   ?>
			
	   
	    <?php if ($title != "Monitoring Usage"){?>
			<li class="active">
				<a href="<?php echo base_url().''.$row->menu_url; ?>" class="nav-link">
					<!-- <i class="menu-icon fa fa-caret-right"></i> -->
					<?php echo $row->menu_name; ?>
				</a>
				<b class="arrow"></b>
			</li>	    	
	    <?php } ?>
	<?php 
		} 
		
		?>
		</ul>
	      </li>
          </ul>
    
      	</aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
      	 <section class="section">
          <div class="section-header">
            <h1>
              <?php if(isset($menu_hide)){?>
                <a href="#" onclick="window.history.go(-1); return false;"><i class='ace-icon fa fa-chevron-left bigger-120'></i></a>
                <?php } ?>
              <span class="text_breadcump"><?php echo $subtitle;?></span></h1>
          </div>