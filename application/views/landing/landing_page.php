<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Cosmic</title>
    <meta content="" name="descriptison">
    <meta content="" name="keywords">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-landing.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/icofont/icofont.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/aos/aos.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/line-awesome/css/line-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/landing_style.css" rel="stylesheet">

     <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-css/1.4.6/select2-bootstrap.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
 
   </head>
</head>
<body>
    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icofont-close js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>
    <!-- <header class="site-navbar js-sticky-header site-navbar-target" role="banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6 col-lg-2">
                    <img class="img-banner" src="<?=base_url()?>assets/images/Logo.png" alt="Cosmic Logo">
                </div>

                <div class="col-12 col-md-10 d-none d-lg-block">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <button class="btn btn-outline-light rounded-pill pl-5 pr-5 mt-3 mb-3 ">Daftar</button>
                    </nav>
                </div>

                <div class="col-6 d-inline-block d-lg-none ml-md-0 py-3" style="position: relative; top: 3px;">
                    <a href="#" class="burger site-menu-toggle js-menu-toggle" data-toggle="collapse"
                        data-target="#main-navbar">
                        <span></span>
                    </a>
                </div>
            </div>
        </div>
    </header> -->
    <nav class="navbar navbar-expand-lg site-navbar js-sticky-header site-navbar-target">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img class="img-banner" src="<?=base_url()?>assets/images/Logo.png" alt="Cosmic Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <!-- <span class="navbar-toggler-icon"></span> -->
                <strong class="text-light">☰</strong>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ml-auto">
                    <a href="http://103.146.244.139/cosmic_vaksin/auth/login">
                    <button class="btn btn-outline rounded-pill pl-5 pr-5 mt-3 mb-3 text-light">Lapor Vaksin</button>
                    </a>
                    &nbsp;
                    <a href="<?php echo base_url()?>auth/login">
                    <button class="btn btn-outline rounded-pill pl-5 pr-5 mt-3 mb-3 text-light">Login</button>
                    </a>
                    &nbsp;
                    <a href="<?php echo base_url()?>auth/login">
                    <button class="btn btn-outline-light rounded-pill pl-5 pr-5 mt-3 mb-3 ">Daftar</button>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9 hero-text-image">
                    <div class="row">
                        <div class="col-lg-7 text-center text-lg-left">
                            <h1 class="text-light">Re-Start Your Busnisess</h1>
                            <p class="mb-5 text-light">Kami membantu Usaha Anda Menjalankan kembali Bisnis dengan
                                Penerapan Protokol Sesuai dengan Standar yang direkomendasikan.</p>
                            <a href="<?php echo base_url()?>auth/login">
                                <button class="btn rounded btn-warning text-dark px-4 py-2 mb-3 rounded">
                                    Daftar Sekarang
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <main id="main">
        <section class="section">
            <div class="container">
                <div class="row justify-content-center text-center mb-5">
                    <div class="col-md-6">
                        <h2 class="section-heading font-weight-bold mb-5">Kami Membantu Anda Memantau yang Penting</h2>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-5">
                        <img src="<?=base_url('assets/images/task_focus.png')?>" alt="Images Focus" class="img-fluid">
                    </div>
                    <div class="col-md-5 offset-md-1">
                        <div class="row lign-items-center mb-3">
                            <div class="col-md-12 ml-auto mr-auto">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="col-md-12 d-flex p-0">
                                            <div class="mb-3">
                                                <img src="<?=base_url('assets/images/system_icon.png')?>" width="70"
                                                    alt="Images Focus" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <h5 class="font-weight-bold">System</h5>
                                        <p class="text-dark">Ketersediaan Protokol, Perimeters dsb.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row lign-items-center mb-3">
                            <div class="col-md-12 ml-auto mr-auto">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="col-md-12 d-flex p-0">
                                            <div class="mb-3">
                                                <img src="<?=base_url('assets/images/behavior_icon.png')?>" width="70"
                                                    alt="Images Focus" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <h5 class="font-weight-bold">Behavior</h5>
                                        <p class="text-dark">Pelaksanaan Monitoring Protokol, Evidence.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row lign-items-center mb-3">
                            <div class="col-md-12 ml-auto mr-auto">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="col-md-12 d-flex p-0">
                                            <div class="mb-3">
                                                <img src="<?=base_url('assets/images/impact_icon.png')?>" width="70"
                                                    alt="Images Focus" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <h5 class="font-weight-bold">Impact</h5>
                                        <p class="text-dark">Data Pegawai terdampak.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="section section-offset bg-gray-custom">
            <div class="container wrap-relative">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <h2 class="section-heading-2">Lakukan Yang Perlu dilakukan dengan Cosmic</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="wrapper center-block">
                            <div class="panel-group" id="accordion" role="tablist">
                                <div class="panel panel-default bg-white mt-3">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                data-parent="#accordion" href="#collapseOne" aria-expanded="false"
                                                aria-controls="collapseOne">
                                                Kelola Perimeter Anda
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            Kelola Aset yang perusahaan anda miliki, gedung kantor, restoran, hotel, tempat wisata dsb.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default bg-white mt-3">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                Kelola Protokol Usaha Anda
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingTwo">
                                        <div class="panel-body">
                                            Kelola protokol anda sesuai dengan ketentuan yang disarankan. Kami membantu menstandarisasi kebutuhan protokol sesuai ketentuan yang berlaku.
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default bg-white mt-3">
                                    <div class="panel-heading" role="tab" id="headingThree">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                data-parent="#accordion" href="#collapseThree" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                Monitor Pelaksanaan Protokol
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingThree">
                                        <div class="panel-body">
                                            Monitor pelaksanaan protokol di tempat usaha anda dengan aplikasi, pantau kedisiplinan di setiap perimeter anda dengan mudah dan dapatkan skor kepatuhan yang baik
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default bg-white mt-3">
                                    <div class="panel-heading" role="tab" id="headingFour">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                                data-parent="#accordion" href="#collapseFour" aria-expanded="false"
                                                aria-controls="collapseFour">
                                                Dapatkan Skor Kepatuhan Langsung
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingFour">
                                        <div class="panel-body">
                                            Deklarasikan kepatuhan anda dengan cosmic Index. Beritahu kepada rekan usaha dan pelanggan bahwa perusahaan anda menjalankan protokol dengan baik
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1 ml-auto align-items-center">
                        <img src="<?=base_url('assets/images/img_task_list.png')?>" alt="Images Focus"
                            class="img-fluid">
                    </div>
                </div>
                <div class="col-md-12 card-absolute">
                    <div class="row justify-content-center">
                        <div class="col-md-4 card-field px-5">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <img src="<?=base_url('assets/images/user.png')?>" width="50"
                                            alt="Images Focus" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="font-weight-bold font-size-card-heading ">108+</h5>
                                    <p class="text-dark font-size-card">Perusahaan Terdaftar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 card-field px-5">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <img src="<?=base_url('assets/images/location.png')?>" width="50"
                                            alt="Images Focus" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="font-weight-bold font-size-card-heading ">16,500+</h5>
                                    <p class="text-dark font-size-card">Lokasi Perimeter Terdaftar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 card-field px-5">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <img src="<?=base_url('assets/images/Server.png')?>" width="50"
                                            alt="Images Focus" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="font-weight-bold font-size-card-heading ">33</h5>
                                    <p class="text-dark font-size-card">Provinsi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <section class="section section-offset-1">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-md-6">
                    <h2 class="section-heading mb-5">Daftarkan Bisnis Anda Sekarang</h2>
                    <p class="display-6">Mulai Kembali Bisnis Anda Dengan Standar Kesehatan <br> yang
                        direkomendasikan*.
                    </p>
                    <a href="<?php echo base_url()?>auth/login">
                    <button class="btn rounded btn-warning text-secondary px-4 py-2 mt-3 mb-3 rounded">Daftar
                        Sekarang</button>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="section-finding">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-5 section-finding-part">
                    <h2 class="text-light mb-5 section-finding-title">Menemukan Pelanggaran Protokol?</h2>
                    <p class="text-light section-finding-content">Bantu kami memastikan semua pihak menjalankan protokol dengan baik</p>
<!--   					<a href="http://103.146.244.139/cosmic_dev/reportprotokol/reportprotokol"> -->
                  
                    <button class="btn btn-danger px-4 py-2 mt-3 mb-3 rounded" onclick="open_lapor()">Laporkan Disini</button>
<!--                     </a> -->
                </div>
                <div class="col-md-5 offset-md-2">
                    <img src="<?=base_url('assets/images/img_finding_protocol.png')?>" alt="Images Focus"
                        class="img-fluid img-finding">
                </div>
            </div>
        </div>
    </section>
    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <img class="img-banner mb-4" src="<?=base_url()?>assets/login/LogoShort.png" alt="Cosmic Logo">
                    <p class="text-dark"><strong>Cosmic</strong> adalah platform pemantauan kepatuhan pelaksanaan
                        standar kesehatan yang direkomendasikan*.</p>
                </div>
                <div class="col-md-7 ml-auto">
                    <div class="row site-section pt-0">
                        <div class="col-md-6 offset-md-6 mb-4 mb-md-0">
                            <h3>&nbsp;</h3>
                            <div class="row">
                                <div class="col">
                                    <a href="https://play.google.com/store/apps/details?id=com.cosmicsysid">
                                    <img class="img-banner mb-4" src="<?=base_url()?>assets/images/img_googleplay.png"
                                        alt="Google Play Store Logo">
                                    </a>
                                </div>
                                <div class="col">
                                    <a href="https://apps.apple.com/id/app/cosmic-system/id1536380572?l=id">
                                    <img class="img-banner mb-4" src="<?=base_url()?>assets/images/img_appstore.png"
                                        alt="App Store Logo">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row text-dark mt-5 font-weight-bold">
                <div class="col-md-4">
                    <p class="copyright">&copy; Kementerian BUMN 2020</p>
                </div>
                <div class="col-md-7 ml-auto">
                    <div class="row site-section pt-0">
                        <div class="col-md-6 offset-md-6">
                            <p class="copyright">*Berdasarkan Kementerian Kesehatan dan WHO</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
    <script src="<?=base_url()?>assets/jquery/jquery-2.2.3.min.js"></script>
    <script src="<?=base_url()?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url()?>assets/jquery.easing/jquery.easing.min.js"></script>
    <script src="<?=base_url()?>assets/aos/aos.js"></script>
    <script src="<?=base_url()?>assets/owl.carousel/owl.carousel.min.js"></script>
    <script src="<?=base_url()?>assets/jquery-sticky/jquery.sticky.js"></script>
    <script src="<?=base_url()?>assets/js/main_landing.js"></script>
    
<div class="modal modal2" id="modal_lapor" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog">
<div class="modal-content">
  	<div class="modal-body">
          	<div class="form-body">
            <div class="row">
				<div class="col-sm-12">
                	<div class="form-group">
                        <label class="control-label col-sm-12">Nama Perusahaan</label>
                        <div class="col-sm-12">
                            <select id="company" name="company" style="width:100%" 
                            	class="col-sm-12 select2">
                              	<?php foreach ($company->result() as $row) { ?>
                                <option value="<?php echo $row->mc_id;?>" >
                        			<?php echo $row->mc_name;?>
                        	    </option>
                        		<?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
						<label class="control-label col-sm-12">Nama Perimeter</label>
						<div class="col-sm-12">
                       		<select id="perimeter" name="perimeter" style="width:100%" 
                            	class="col-sm-12 select2">
							</select>
                            <span class="help-block"></span>
                        </div>
                        <input type="hidden" id="perimeter_link" name="perimeter_link" value=""/>
                	</div>
             	</div>
             	<div class="col-sm-12">
                    <div class="modal-footer justify-content-between"><div class="w-100">
                        <button type="submit" class="btn btn-md btn-primary mr-auto " id="btnSave" onclick="lapor()">Lapor Pelanggaran Protokol</button>
                        <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Batal</button>
                    </div></div>
              	</div>
             </div>
             </div>
  	</div>
</div>
</div>
</div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#btnSave").prop("disabled",true);
	$('#company').select2();
	$('#perimeter').select2();
	perimeter();
	link_reportperimeter();
});

function open_lapor() {
    $('#modal_lapor').modal('show');
}

$("#company").change(function(){ 
    $("#perimeter").hide();
    perimeter();
	link_reportperimeter();
});


$("#perimeter").change(function(){ 
	link_reportperimeter();
});

function perimeter(){
	$.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportprotokol/perimeter",
        data: {kd_perusahaan : $("#company").val()},
        dataType: "json",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
           	$("#perimeter").show();
            $('#perimeter').html(response.list_perimeter);
            link_reportperimeter();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function link_reportperimeter(){
    if( $("#perimeter").val()==null){
    	$("#btnSave").prop("disabled",true);
    }else{
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>landing/link_reportperimeter/",
            data: {perimeter_id : $("#perimeter").val()},
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function(response){
            	$("#btnSave").prop("disabled",false);
        		$('#perimeter_link').val(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
}

function lapor(){
	window.location.href = $('#perimeter_link').val();
}


</script>
</body>
</html>