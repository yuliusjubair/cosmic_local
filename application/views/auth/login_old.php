
<!doctype html>
<html lang="en" class="fullscreen-bg">
<head>
	<title>Login</title>
	<meta name="description" content="User login page" />
	<link rel="icon" href="<?php echo base_url(); ?>assets/images/logocosmic.png" type="image/x-icon" /> 
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/style.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/main.css">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
</head>
<style type="text/css">
	.btn-block-new{
		background: #235797;
		border-radius: 32px;
		width: 170px;
	}
	.label-new{
		
		left: -15px;
		top: 0px;

		font-style: normal;
		font-weight: normal !important;
		font-size: 16px;
		line-height: 22px;
		color: #000000;
	}
</style>
<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle">
				<div class="auth-box ">
					<div class="left">
						<!-- <div class="overlay"></div> -->
						<!-- <div class="content text"> -->
							<!-- <h1 class="heading"><b>Aplikasi COSMIC	</b></h1>
								<p>Masing-masing BUMN agar melakukan Update Protokol dan memastikan 5 Protokol
									besaran terpenuhi.</p> -->
						<!-- </div> -->

						<!-- <img style="width:94%" src="<?php echo base_url(); ?>assets/login/left_bg_font.png"> -->
					</div>
					<div class="right">
						<div class="content">
							<div class="header">
							 <div class="logo text-center">
							 <img style="width:50%" src="<?php echo base_url(); ?>assets/login/LogoShort.png">
							 </div> 
								<p class="lead">Login </p>
							</div>
							<hr style="width: 50%">
							<?php if($this->session->flashdata('sukses') ) : ?>
											<div class="alert alert-danger alert-dismissable">
												<button type="button" class="close" data-dismiss="alert">
													<i class="ace-icon fa fa-times"></i>
												</button>
												<?= $this->session->flashdata('sukses'); ?>
											</div>
										<?php endif; ?>
										<?php if($this->session->flashdata('terkirim') ) : ?>
											<div class="alert alert-success alert-dismissable">
												<button type="button" class="close" data-dismiss="alert">
													<i class="ace-icon fa fa-times"></i>
												</button>
												<?= $this->session->flashdata('terkirim'); ?>
											</div>
										<?php endif; ?>
										<?php if($this->session->flashdata('flash') ) : ?>
										<div class="alert alert-success alert-dismissable">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>
											<?= $this->session->flashdata('flash'); ?>
										</div>
									<?php endif; ?>
							<form class="form-auth-small" action="<?php echo base_url()."auth/login"; ?>" method="post" accept-charset="utf-8" style="text-align: center !important;">
								<div class="form-group row">
									<div class="col-md-8 col-md-offset-3">
									<label for="username"  class="label-new col-sm-1 col-form-label">Username</label>
										<input type="text" class=" form-control-sm form-control" id="username" name="identity" placeholder="Username">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-md-8 col-md-offset-3">
									<label for="signin-password" class="label-new col-sm-2 col-form-label control-label">Password</label>
									<input type="password" class="form-control" id="password" name="password" placeholder="Password">
								</div>
								</div>
							
							<div class="space"></div>
							
							<div class="form-group row" style="font-size: 14px !important;">
									<div class="col-md-4 col-md-offset-3" nowrap>
									<input type="radio" name="">
										Ingat Saya
									</div>	
									<div class="col-md-5" style="margin-left: -15px;">
										<a href="#">Forgot Password</a>
									</div>
								</div>

								<div class="col-md-8 col-md-offset-3">
									<button type="submit" class="btn btn-primary btn-lg btn-block-new">Masuk</button>
								</div>

							</form>

						<div id="infoMessage" style="color:red; text-align: center; ">
							<div class="col-md-8 col-md-offset-3">
								<?php echo "<br>".$message;?>	
							</div>
						</div>
						</div>
					</div>
					<div class="clearfix"></div>

				</div>
			</div>
		</div>
	</div>
</body>
</html>