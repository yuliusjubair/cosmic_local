<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Onboarding &mdash; Cosmic</title>
</head>
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/components.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


    <style> 
      .modal-content-carousel{
        width: 900px;
      }
      .modal-dialog.modal-800 {
          width: 800px;
          margin: 30px auto;
      }
    </style>

<div class="row">
  <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
    <div class="login-brand">
      Cosmic Onboarding
    </div>

    <div class="card card-primary">
      <div class="card-header">
        <h4 class=" text-center">
        
        <!-- Akses anda saat ini sedang menunggu verifikasi oleh Administrator. Silakan cek kembali paling lambat 2x24 jam -->
        </h4>
      </div>

      <div class="card-body">
        <form method="POST">
          <div class="align-items-center" style="text-align: center;">
            <img src="<?php echo base_url(); ?>assets/images/undraw_teamwork_hpdk.svg" alt="logo" width="400" class="img-responsive">
          </div>
         <!--  <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-envelope"></i>
                </div>
              </div>
              <input id="email" type="email" class="form-control" name="email" autofocus placeholder="Email">
            </div>
          </div> -->
          <div class="form-group text-center">
            <br />
            <h6>Akun anda sedang dalam verifikasi oleh Administrator</h6>
            Administrator akan memeriksa data perusahaan yang anda input saat registrasi.
            Proses ini akan memakan waktu paling lambat 2x24 jam. 
          </div>
          <div class="form-group text-center">
            <br />
              <a class="btn btn-lg btn-round btn-primary" href="#" onclick="open_modal()">Lihat Ada di Cosmic
              </a>
          </div>
        </form>
      </div>
    </div>
    <div class="simple-footer">
      Copyright &copy; Cosmic <?php echo date('Y')?>
    </div>
  </div>
</div>

</body>
</html>
 <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets_stisla/js/stisla.js"></script>

  <script type="text/javascript" src="<?php echo base_url(); ?>assets/owl.carousel/owl.carousel.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css">
  <link rel="stylesheet" type="text/css" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css">

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="<?php echo base_url(); ?>assets_stisla/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets_stisla/js/custom.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var owlCarousel = $('.owl-carousel');
    $('#myModal').on('shown.bs.modal', function (event) {
      owlCarousel.owlCarousel({
        margin: 10,
        loop: true,
        autoWidth: true,
        items: 4
      });
    });
   /* $('#myModal').on('hidden.bs.modal', function (event) {
      owlCarousel.data('owlCarousel').destroy();
      owlCarousel.find('.owl-stage-outer').children().unwrap();
    });*/

  $(".owl-carousel").owlCarousel({
    loop:true,
    autoplay: true,
    autoplayTimeout: 4000,
    navigation: true,
    items:1,
    autoHeight:true,
    margin:10,
    // autoplayHoverPause: true
  });
});
  $("#btnSave").click(function(event) {
    window.location.href="<?php echo base_url()?>auth/login";
  })
  function open_modal() {
    $('.modal2').modal('show');
  }
</script>

<div class="modal modal2" id="myModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-800 modal-dialog modal-lg modal-dialog-centered">
  <div class="modal-content" style="width:980px;">
  <div class="modal-header text-center">
    <h3 class="modal-title w-100">Cosmic Feature</h3>
  </div>
  <div class="modal-body">
    <form id="form_modal" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
      <input type="hidden" value="0" id="modal_id" name="modal_id"/>
      <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
      <input type="hidden" value="0" name="id">
      <div class="form-body">
        <div class="row">
          <div class="col-12 col-sm-12 col-lg-12">
            <div class="owl-carousel owl-theme">
                <div class="item"> 
                  <h6 class="text-center">Input dan manage protokol dalam perusahaan</h6>
                  <img class="img-responsive d-block w-100" src="<?php echo base_url(); ?>assets/images/frame1_onboarding.svg" alt="What's News?">
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="item"> 
                  <h6 class="text-center">Menginput dan memonitor perimeter perusahaan</h6>
                  <img class="img-responsive d-block w-100" src="<?php echo base_url(); ?>assets/images/frame2_onboarding.svg" alt="What's News?">
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="item"> 
                  <h6 class="text-center">Menginput dan memonitor pegawai yang terdampak COVID-19</h6>
                  <img class="img-responsive d-block w-100" src="<?php echo base_url(); ?>assets/images/frame3_onboarding.svg" alt="What's News?">
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="item"> 
                  <h6 class="text-center">Request pengajuan atestasi dan sertifikasi</h6>
                  <img class="img-responsive d-block w-100" src="<?php echo base_url(); ?>assets/images/frame4_onboarding.svg" alt="What's News?">
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
            </div>         
          
        </div>
      </div>
       <div class="modal-footer">
            <!-- <button type="button" class="btn btn-md btn-primary" id="btnSave">Redirect Halaman Login</button> -->
            <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Keluar</button>
        </div>
    </div>
  </form>
  </div>
</div>
</div>
</div>