<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; Cosmic</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/components.css">
</head>
<style type="text/css">
  .modal-body{
    height: 600px;
    overflow-y: auto;
  }

  .has-error {
    color: #8a6d3b;
    background-color: #fcf8e3;
    border-color: #8a6d3b;
}

 /* @media (min-width: 576px) {
  .modalku {
    width: 800px;
    margin: 30px auto;
  }
  .modal-content {
    -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
  }
  .modal-sm {
    width: 300px;
  }
}*/
</style>
<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch">
      	<div class="col-lg-8 col-12 order-lg-1 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" data-background="<?php echo base_url(); ?>assets/login/left_bg_font.png">
          <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
             
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12 order-lg-2 min-vh-100 order-2 bg-white">
          <div class="p-4 m-3">
            <div style="text-align: center;line-height: 70%">
	            <img src="<?php echo base_url(); ?>assets/login/LogoShort.png" alt="logo" width="200" class="align-items-center">
              <br />
	            <br />
            <h4 class="m-2 text-dark font-weight-normal"><span class="font-weight-bold">Login</span></h4>
	        </div>
            <!-- <p class="text-muted">Before you get started, you must login or register if you don't already have an account.</p> -->
            <form class="needs-validation form-auth-small" action="<?php echo base_url()."auth/login"; ?>" method="post" accept-charset="utf-8">

              <div class="form-group">
                <label for="email" style="font-size: 16px">Username</label>
                <input id="username" type="text" class="form-control" name="identity" tabindex="1" required autofocus>
                <div class="invalid-feedback">
                  Please fill in your Username
                </div>
              </div>

              <div class="form-group">
                <div class="d-block">
                  <label for="password" class="control-label" style="font-size: 16px">Password</label>
                </div>
                <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                <div class="invalid-feedback">
                  please fill in your password
                </div>
              </div>

             <!--  <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                  <label class="custom-control-label" for="remember-me">Ingat Saya</label>
                </div>
                <div class="text-right">
	                <a href="auth-forgot-password.html" class="float-left mt-3">
	                  Forgot Password?
	                </a>
	            </div>
              </div>
 -->
              <!-- <div class="form-group row" style="font-size: 14px !important;margin-left: 10px;">
					<div class="col-md-7 col-md-offset-0" nowrap>
					<input type="radio" name="">
						Ingat Saya
					</div>	
					<div class="col-md-5">
						<a href="#">Forgot Password</a>
					</div>
				</div> -->

              <!-- <div class="form-group text-right">
                <a href="auth-forgot-password.html" class="float-left mt-3">
                  Forgot Password?
                </a>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                  <label class="custom-control-label" for="remember-me">Ingat Saya</label>
                </div>
              </div> -->

              <div class="mt-5 text-center">
                <button type="submit" class="btn btn-round btn-primary btn-lg btn-icon icon-right" tabindex="4">
                  Masuk
                </button>
              </div>
              <div class="form-group">
      	        <div class="mt-6" id="infoMessage" style="color:red;text-align: -webkit-center;">
        					<div class="col-md-8 col-md-offset-3">
        						<?php echo "<br><u>".$message."</u>";?>	
        					</div>
      				  </div>

                <div class="mt-6" id="infoMessage" style="color:red;text-align: -webkit-center;">
                  <div class="col-md-8 col-md-offset-3">
                    Belum terdaftar? Daftarkan perusahaanmu <a href="#" onclick="open_form_register()">disini</a>
                  </div>
                </div>

                 <?php 
                 $msg2 = $this->session->userdata("msg2");
                 if($this->session->flashdata('msg') || !empty($msg2)){ ?>
                    <div class="alert alert-success">
                        <!-- <a href="#" class="close" data-dismiss="alert">&times;</a> -->
                        <strong>Success!</strong> <?php echo $this->session->flashdata('msg'); 
                        //echo $this->session->keep_flashdata('msg');
                        ?>
                    </div>

                <?php } ?>
        			</div>

            </form>

            

            <!-- <div class="text-center mt-5 text-small">
              Copyright &copy; Your Company. Made with ðŸ’™ by Stisla
              <div class="mt-2">
                <a href="#">Privacy Policy</a>
                <div class="bullet"></div>
                <a href="#">Terms of Service</a>
              </div>
            </div> -->
          </div>
        </div>
        
      </div>
    </section>
  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="modal modal2" id="myModal" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header text-center">
    <h3 class="modal-title w-100">Mendaftarkan Perusahaan</h3>
  </div>
  <div class="modal-body">
    <form id="form_modal" onsubmit="return confirm('Do you really want to submit the form?');" method="POST" action="<?php echo base_url()?>auth/register_non_bumn" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
      <input type="hidden" value="0" id="modal_id" name="modal_id"/>
      <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
      <input type="hidden" value="0" name="id">
      <div class="form-body">
        <div class="row">
          <div class="col">
              <div class="form-group">
                <label class="control-label col-sm-6">Nama Perusahaan<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="nama" name="nama" placeholder="Nama Perusahaan" class="form-control" type="text" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-6">Jenis Industri<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <select name="jenis" id="jenis" class="form-control required" data-toggle="tooltip" data-placement="top" data-html="true">
                    <?php foreach ($jenis_industri as $pro){ ?>
                        <option value="<?php echo $pro->ms_id; ?>" title="<?php echo $pro->ms_name; ?>"><?php echo $pro->ms_name; ?></option>
                    <?php } ?>
                    </select>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-12">Lokasi Kantor/Tempat Industri<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="lokasi" name="lokasi" placeholder="Lokasi" class="form-control" type="text" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4">Provinsi<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <select name="provinsi" id="provinsi" class="form-control">
                      <option value="">-Pilih Provinsi-</option>
                    <?php foreach ($provinsi as $pro){ ?>
                        <option title="<?php echo $pro->mpro_name; ?>" value="<?php echo $pro->mpro_id; ?>"><?php echo $pro->mpro_name; ?></option>
                    <?php } ?>
                    </select>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4">Kota<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <select class="form-control" name="kabupaten" id="kabupaten" >
                    </select>
                    <span class="help-block"></span>
                </div>
            </div>
           
          </div>

          <div class="col">
              <div class="form-group">
                <label class="control-label col-sm-6">Jumlah Pegawai<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <select class="form-control" name="jumlah_pegawai" id="jumlah_pegawai">
                      <option value="1 Pegawai">1 Pegawai</option>
                      <option value="2-10 Pegawai">2 – 10 Pegawai</option>
                      <option value="11 – 50 Pegawai">11 – 50 Pegawai</option>
                      <option value="51 – 100 Pegawai">51 – 100 Pegawai</option>
                      <option value="101 – 500 Pegawai">101 – 500 Pegawai</option>
                      <option value="501 – 1000">501 – 1000 Pegawai</option>
                      <option value="Diatas 1000 Pegawai">Diatas 1000 Pegawai</option>
                    </select>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-12">Nama Penanggung Jawab<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="nama_penanggungjawab" name="nama_penanggungjawab" placeholder="Nama Penanggung Jawab" class="form-control" type="text" required>
                    <span class="help-block"></span>
                </div>
            </div>
             <div class="form-group">
                <label class="control-label col-sm-12">Kontak Penanggung Jawab<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="kontak_penanggungjawab" name="kontak_penanggungjawab" placeholder="Kontak Penanggung Jawab" class="form-control" type="text" required>
                    <span class="help-block"></span>
                </div>
            </div>
             <div class="form-group">
                <label class="control-label col-sm-12">Email Penanggung Jawab<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="email_penanggungjawab" name="email_penanggungjawab" placeholder="Email Penanggung Jawab" class="form-control" type="text" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group" id="photo-preview1" style="display:none;">
                <label class="control-label col-md-6">Photo</label>
                <div class="col-md-12">
                    (No photo)
                </div>
            </div>
            <div class="form-group photos">
                <label class="control-label col-md-6" id="label-photo1">Upload Photo<span style="color:red">*</span></label>
                <div class="col-md-12">
                    <input type="hidden" name="nomor" value="1" id="nomor">
                    <input id="modal_foto" name="modal_foto" type="file" class="required" required  accept="image/png, image/jpeg, image/jpg">
                    <font size="1" color="#800000">* Filetype yang diperbolehkan jpg, png dan max 2Mb</font>
                    <span class="help-block"></span>
                </div>
                   
            </div>
            <div class="form-group">
              <div class="col-sm-12">
               <button type="button" class="btn btn-sm btn-primary photo">Add Photo</button>
              </div>
            </div>
             <div class="form-group">
                <label class="control-label col-sm-12">Website Perusahaan (Optional)</label>
                <div class="col-sm-12">
                    <input id="website" name="website" placeholder="Website Perusahaan" class="form-control" type="text">
                </div>
            </div>
             <hr/>
            <div class="form-group">
                <label class="control-label col-sm-12">Username Login<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="usernamex" name="username" placeholder="Username" class="form-control" type="text" autocomplete="no-fill">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-12">Password Login<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="password" name="password" placeholder="Password" class="form-control" type="password" autocomplete="new-password">
                    <span class="help-block"></span>
                </div>
            </div>
             <hr/>
          </div>
         
             <div class="form-group">
                
                <div class="col-sm-12">
                   <label class="control-label col-sm-12"><input type="checkbox" class="check1" name="check1"> Saya telah membaca dan menyetujui syarat dan ketentuan yang berlaku<span style="color:red">*</span></label>
                </div>
            </div>
             <div class="form-group">
                
                <div class="col-sm-12">
                   <label class="control-label col-sm-12"><input type="checkbox" class="check2" name="check2"> Saya bersedia dihubungi apabila permohonan di setujui oleh COSMICSystem<span style="color:red">*</span></label>
                </div>
            </div>
        
        </div>
         <div class="modal-footer">

            <button type="submit" class="btn btn-md btn-primary" id="btnSave" disabled>Daftar</button>
            <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Batal</button>
        </div>
          
          
      </div>
  </form>
  </div>
</div>
</div>
</div>
<style type="text/css">
  .tooltip {
  z-index: 2000;
}
</style>
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
  <script type="text/javascript">
    $(function(){
        /*$(".form_modal").submit(function(){
          $.ajax({
            url:$(this).attr("action"),
            data:$(this).serialize(),
            type:$(this).attr("method"),
            dataType: 'html',
            beforeSend: function() {
              $("textarea").attr("disabled",true);
              $("button").attr("disabled",true);
            },
            complete:function() {
              $("textarea").attr("disabled",false);
              $("button").attr("disabled",false);               
            },
            success:function(hasil) {
              alert('Sukses Register');
              window.location.reload();
            }
          })
          return false;
        });*/
      });

    $(document).ready(function() {

     
      $("#jenis").change(function(event) {
        $.each($(this).find('option'), function(key, value) {
          $(value).removeClass('active');
        })
        $('option:selected').addClass('active');

      });

      $("#jenis").tooltip({
        placement: 'right',
        trigger: 'hover',
        container: 'body',
        title: function(e) {
          return $(this).find('.active').attr('title');
        }
      });

      $('option').each(function () {
        var text = $(this).text();
        if (text.length > 10) {
          text = text.substring(0, 50) + '...';
          $(this).text(text);
        }
      });

      var c=0;
      var d=0;
      $('.check1').change(function() {
          if($(this).is(":checked")) {
            c=1;
          }else{
            c=0;
          }        
          ceklist();
      });

      $('.check2').change(function() {
          if($(this).is(":checked")) {
            d=1;
          }else{
            d=0;
          }        
          ceklist();
      });

      function ceklist(){
         if(c+d==2){
          $("#btnSave").prop("disabled",false);
        }else{
          $("#btnSave").prop("disabled",true);
        }
      }

      
      $("#usernamex").change(function(){ 
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>auth/checkUsername",
            data: {username : $(this).val()},
            /*dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },*/
            success: function(response){
                if(response=="ada"){
                  alert("Username already Use!");
                  $('#usernamex').addClass('has-error');
                  $('#usernamex').val('');
                  $('#usernamex').focus();
                  return false;
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
      });
      $("#provinsi").change(function(){ 
          $("#kabupaten").hide();
          get_kota($("#provinsi").val(), $("#kabupaten").val());
      });

      $(".photo").click(function(){
        var nomor = $("#nomor").val();
        var nomor = parseInt(nomor)+1;
        $("#nomor").val(nomor);
        var row='<label class="control-label col-md-6" id="label-photo1">Upload Photo<span style="color:red">*</span></label><div class="col-md-12"><input id="modal_foto'+nomor+'" name="modal_foto'+nomor+'" type="file" class="required" required  accept="image/png, image/jpeg, image/jpg"><font size="1" color="#800000">* Filetype yang diperbolehkan jpg, png dan max 2Mb</font><span class="help-block"></span></div>';
        console.log(nomor);
        if(nomor<=3){
          $('.photos').append(row);
        }
      })

    });

    function get_kota(provinsi_id, kabupaten_id){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kasus/kabupaten",
        data: {id_provinsi : provinsi_id},
        dataType: "json",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#kabupaten").html(response.list_kabupaten).show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

    function open_form_register() {
        $('.modal2').modal('show');
    }

    function cancel(){
      $('.modal.in').modal('hide') 
    }
  </script>
</body>
</html>
