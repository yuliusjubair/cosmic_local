<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/css/chocolat.css">
<script src="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<div class="row">
<div class="col-12 col-md-12 col-lg-12">
<div class="card">
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="row pull-right">
              <?php 
             /* if($update==1){
                echo '<a class="btn btn-lg btn-primary" href="javascript:void(0)" title="Edit"
                onclick="edit_rumahsinggah('."'".$row->mc_id."'".')">
                <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
              }
              
              if($delete==1){
                echo '&nbsp;<a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete"
                onclick="delete_rumahsinggah('."'".$row->mc_id."'".')">
                <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
              }*/
              ?><br />
                </div>
              <div class="gallery gallery-md gutters-sm">'
                <div class="col-12 col-md-12 col-lg-12">
                    <?php $image1 = base_url('/uploads/companynonbumn/'.$row->mc_foto);
                    //$image1 = base_url().'assets/login/left_bg_font.png';
                    ?> 
                    <div class="gallery-item" data-image="<?php echo $image1?>" data-title="Image 1" href="<?php echo $image1?>" title="Image 1" 
                        style="background-image: url('<?php echo $image1?>');">
                    </div>
                    <?php
                        $image2 = base_url('/uploads/companynonbumn/'.$row->mc_foto2);
                    ?>
                    <div class="gallery-item" data-image="<?php echo $image2?>" data-title="Image 2" href="<?php echo $image2?>" title="Image 2"
                            style="background-image: url('<?php echo $image2?>');">
                    </div>
                    <?php
                        $image3 = base_url('/uploads/companynonbumn/'.$row->mc_foto3);
                    ?>
                    <div class="gallery-item" data-image="<?php echo $image3?>" data-title="Image 3" href="<?php echo $image3?>" title="Image 3"
                            style="background-image: url('<?php echo $image3?>');">
                    </div>
             
                </div>
               <div class="gallery-item gallery-hide" data-image="<?php echo $image1?>" data-title="Image 9" href="<?php echo $image1?>" title="Image 9" style="background-image: url('<?php echo $image1?>')"></div>
            </div>

            <div class="container">
              <div class="row">
                 
                <div class="col-sm">
                      <div class="col-12 col-sm-12">
                        <h3><?php echo $row->mc_name?></h3>
                        <h7><a href="#" class="stretched-link"><?php echo $row->mc_website?></a></h7>
                    </div>
                    <div class="col-12 col-sm-12">
                        <h6><b><?php echo $row->jenis?></b></h6>
                    </div>
                     <div class="col-12 col-sm-12">
                        <h7><?php echo $row->mc_lokasi?> </h7><h6><?php echo $row->kota.", ".$row->provinsi?> </h6>
                    </div>

                    <div class="col-12 col-sm-8">
                        <h7>Status Perusahaan : </h7> 
                            <h6>
                                <?php 
                                if($row->mc_status=="Verifikasi"){
                                    echo "Ter".$row->mc_status;
                                }else{
                                    echo $row->mc_status;
                                }
                                ?>
                            </h6>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="col-12 col-sm-8">
                        <h7>Jumlah Pegawai : </h7> <h6><?php echo $row->mc_jumlah_pegawai?></h6>
                    </div>
                    <div class="col-12 col-sm-8">
                        <h7>Nama Penanggung Jawab : </h7> <h6><?php echo $row->mc_nama_pic?></h6>
                    </div>
                    <div class="col-12 col-sm-8">
                        <h7>Kontak Penanggung Jawab : </h7> <h6><?php echo $row->mc_kontak?></h6>
                    </div>
                    <div class="col-12 col-sm-8">
                    <h7>Email Penanggung Jawab : </h7> <h6><?php echo $row->mc_email?></h6>
                    </div> 
                </div>
                <div class="col-12">
                    <div class="col-12 col-sm-12">
                     <?php 
                      if($row->mc_status=="Belum terverifikasi"){ ?>
                        
                            <a class="btn btn-lg btn-success" href="javascript:void(0)" title="Verifikasi" onclick="open_dialog_verifikasi('<?php echo $row->mc_id?>','<?php echo $row->mc_name?>','<?php echo $row->mc_username ?>','<?php echo $row->mc_email?>','<?php echo $row->mc_password?>')">
                        Verifikasi
                            </a>
                      <?php 
                          }
                      ?>
                      <button name="back" class="btn btn-danger" onclick="window.history.go(-1); return false;">Kembali</button>  
                      <button name="akses" class="btn btn-primary" onclick="akses_user('<?php echo $row->mc_id?>','<?php echo $row->mc_name?>','<?php echo $row->mc_username ?>','<?php echo $row->mc_email?>')">Lihat Akses User</button>
                       <?php 
                      if($row->mc_status=="Non Aktif"){ ?>

                        <button name="akses" class="btn btn-danger" onclick="aktif_perusahaan('<?php echo $row->mc_id?>','<?php echo $row->mc_username ?>')">Aktifkan Perusahaan</button>  
                      <?php }else{ ?>
                        <?php if($row->mc_status=="Verifikasi"){?>
                        <button name="akses" class="btn btn-danger" onclick="hapus_perusahaan('<?php echo $row->mc_id?>','<?php echo $row->mc_username ?>')">Hapus Perusahaan</button>
                      <?php 
                            }
                      } ?>
                </div>
                </div>
              </div>
            </div>

                
                
                     
               
            </div>
        </div>
</div>
</div>
<script type="text/javascript">
function cancel() {
$('#form_modal')[0].reset();
//datatables();
}

function save() {
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;
    block();
    url = "<?php echo site_url('RegisterCompany/update_verifikasi')?>";
    var formData = new FormData($('#form_modal')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            if(data.status) {
                alert(data.message);
                $('#modal_form').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal')[0].reset();
                window.location.reload();
                // refresh_list();
            } 
         unblock();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            unblock();
        }
    });
}

function open_dialog_verifikasi(id, name, username, email, password){
    $('[name="modal_id"]').val(id);
    $('[name="username"]').val(username);
    $('[name="email"]').val(email);
    $('[name="password"]').val(password);
    $('.name').html("Verifikasi " + name + " ?");
    $('#modal_form').modal('show');
    $('.modal-title').text('');
}

function akses_user(id, name, username, email){
    $('[name="modal_id"]').val(id);
    $('[name="modal_username"]').val(username);
    $('[name="email"]').val(email);
    $('#modal_form_akses').modal('show');
}

function edit_username(username) {
    var username_lama = $('#modal_username').val();
    if(confirm('Apa Anda Yakin Ubah Username ?')) {
        $.ajax({
            url : "<?php echo site_url('RegisterCompany/update_username')?>/"+username+"/"+username_lama,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                 alert(data.message);
            }
        });
    }
}

function reset_password(fs) {
    if(confirm('Apa Anda Yakin Reset Password untuk User '+fs+' ?')) {
        $.ajax({
            url : "<?php echo site_url('RegisterCompany/reset_password')?>/"+fs,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                 alert(data.message);
            }
        });
    }
}

function hapus_perusahaan(mc_id, username) {
    if(confirm('Apa Anda Yakin Hapus Perusahaan ini ?')) {
        $.ajax({
            url : "<?php echo site_url('RegisterCompany/hapus_perusahaan')?>/"+mc_id+"/"+username,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
                window.location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                 alert(data.message);
            }
        });
    }
}

function aktif_perusahaan(mc_id, username) {
    if(confirm('Apa Anda Yakin Aktifkan Perusahaan ini ?')) {
        $.ajax({
            url : "<?php echo site_url('RegisterCompany/aktif_perusahaan')?>/"+mc_id+"/"+username,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
                window.location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                 alert(data.message);
            }
        });
    }
}
</script>
<style type="text/css">
    .name{
        font-size: 16px;
        font-weight: bold;
    }
</style>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form">
                <form action="#" id="form_modal" class="form-horizontal">
                    <input type="hidden" value="0" id="modal_id" name="modal_id"/>
                    <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
                    <input type="hidden" value="0" name="id">
                    <input type="hidden" value="" name="username" id="username">
                    <input type="hidden" name="email" id="email">
                    <input type="hidden" name="password" id="password">
                    <div class="form-body d-flex justify-content-center">
                        <span class="name"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                 <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Batalkan</button>
                <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="save()" >Verifikasi</button>
               
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_akses" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Akses User</h5>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_modal" class="form-horizontal">
                    <input type="hidden" value="0" id="modal_id" name="modal_id"/>
                    <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
                    <input type="hidden" value="0" name="id">
                    <input type="hidden" value="" name="username" id="username">
                    <input type="hidden" name="email" id="email">
                    <div class="form-group">
                            <label class="control-label col-sm-3">Username<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="modal_username" name="modal_username" placeholder="Deskripsi" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                 <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Batalkan</button>
                 <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="edit_username('<?php echo $row->mc_username ?>')" >Save</button>
                 <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="reset_password('<?php echo $row->mc_username ?>')" >Reset Password</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
