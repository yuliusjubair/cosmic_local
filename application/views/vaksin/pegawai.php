<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Vaksin &mdash; Cosmic</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/components.css">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
  <div id="app">
    <section class="section">
		<div class="card card-primary">
      		<div class="login-brand">
          		<img src="<?php echo base_url(); ?>assets/login/LogoShort.png" alt="logo" width="150">
        	</div>
			<div class="card-header"><h3>Pegawai Vaksin</h3></div>
            <div class="card-body">
            <form  action="<?php echo base_url('vaksin/googleCaptachStore') ?>">
                <div class="form-group col-6">
                	<label for="company">Company</label>
                  	<select id="company" name="company" data-live-search="true" onchange="refresh_list()"
                    	class="form-control selectpicker " data-style="btn-white btn-default" class="form-control selectric">
                    	<?php  foreach ($company->result() as $row) {  ?>
                        <option value="<?php echo $row->mc_id;?>">
                			<?php echo $row->mc_name;?>
                	    </option>
                		<?php } ?>
            		</select>
                </div>
				<div class="form-group col-6">
                  <label for="nik">NIK</label>
                  <input type="text" class="form-control" id="nik" name="nik" autofocus>
                </div>
                <div class="form-group col-6">
                	<div class="g-recaptcha" data-sitekey="6Lc9Xd8ZAAAAAHttSC7P7uXXARbQSQ76iaKEqUeo"></div>  
                </div>
                <div class="form-group col-6">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Register
                    </button>
                </div>
            </form>
            </div>
		</div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets_stisla/js/stisla.js"></script>
  <!-- JS Libraies -->
  <!-- Template JS File -->
  <script src="<?php echo base_url(); ?>assets_stisla/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets_stisla/js/custom.js"></script>
  <!-- Page Specific JS File -->
</body>
</html>
