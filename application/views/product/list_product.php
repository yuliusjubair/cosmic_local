<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/css/chocolat.css">
<script src="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
<div class="row">
<div class="col-12 col-md-12 col-lg-12">
<?php foreach ($product as $itemproduct)  { ?>
<div class="card">
          <!-- <div class="card-header">
            <h4>Check</h4>
          </div> -->
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="row pull-right">

                </div>
              <div class ="row">
                <div class ="col-12 col-md-6 col-lg-4">
                  <div class="gallery gallery-md gutters-sm">'
                    <div class="col-12 col-md-12 col-lg-12">
                        <?php
                        foreach ($company->result() as $co) {

                          $com = $co->mc_id;
                          $comname = $co->mc_name;
                         }
                        if(($itemproduct->mlp_filename!=null)){
                          $image1 = base_url('/assets/images/'.$itemproduct->mlp_filename);
                        } else {
                          $image1 = base_url().'assets/login/left_bg_font.png';
                        }

                        ?>
                        <div class="gallery-item" data-image="<?php echo $image1?>" data-title="Image 1" href="<?php echo $image1?>" title="Image 1"
                            style="background-image: url('<?php echo $image1?>'); width: 195px;"></div>


                    </div>
                   <div class="gallery-item gallery-hide" data-image="<?php echo $image1?>" data-title="Image 9" href="<?php echo $image1?>" title="Image 9" style="background-image: url('<?php echo $image1?>')"></div>
                </div>
                </div>
                <div class ="col-12 col-md-6 col-lg-8">
                  <div class="col-6 col-sm-12">
                    <h3><?php echo $itemproduct->mlp_name; ?></h3>
                  </div>
                  <div class="col-6 col-sm-12">
                    <h6><b>by : <?php echo $itemproduct->mlp_by; ?></b></h6>
                  </div>
                  <div class="col-6 col-sm-12">
                    <p><?php echo $itemproduct->mlp_desc; ?><br /></p>

                  </div>

                  <div class="col-6 col-sm-12">
                    <a href="javascript:void(0)" onclick="open_syarat_ketentuan(<?php echo $itemproduct->mlp_id; ?>)"><h6>Syarat dan Ketentuan<h6></a>
                  </div>
                  <div class="col-6 col-sm-12">
                    <?php if ($itemproduct->mlp_active=='t' && $group==2 && $com!=$itemproduct->mlp_mc_id) {?>
                      <?php if ($itemproduct->mlp_id=='1' ) {?>
                        <button type="button" class="btn btn-lg btn-primary" id="btnPengajuan_<?php echo $itemproduct->mlp_id; ?>" onclick="add_pengajuan(<?php echo $itemproduct->mlp_id; ?>)" >Daftar</button>
                      <?php } else if ($itemproduct->mlp_id=='2' ) {?>
                        <button type="button" class="btn btn-lg btn-primary" id="btnPengajuan_<?php echo $itemproduct->mlp_id; ?>" onclick="add_pengajuan_sertifikasi(<?php echo $itemproduct->mlp_id; ?>)" >Daftar</button>
                      <?php } ?>
                    <?php } ?>
                    <?php if ( $group==2  && $com==$itemproduct->mlp_mc_id ) {?>
                    <button type="button" class="btn btn-lg btn-primary" id="btnEditProduct_<?php echo $itemproduct->mlp_id; ?>"  onclick="edit_product(<?php echo $itemproduct->mlp_id; ?>)" ><i class ="fa fa-pencil"></i>  Edit</button>
                    <?php } ?>
                  </div>
                </div>
              </div>





          </div>
        </div>
</div>
<?php }?>
</div>
</div>
<script type="text/javascript">
var id_product;
var prm =[];
var txt1 ="";
function open_syarat_ketentuan(id_product) {

    $.ajax({
        url : "<?php echo site_url('product/ajax_edit_product')?>/"+id_product,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $("#iframepdf").attr("src", "<?php echo base_url('/uploads/product/')?>/"+data.mlp_file_syarat_ketentuan);

            $('#modal_syarat_ketentuan').modal('show');
            $('.modal-title').text('Syarat dan Ketentuan');

        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
    });



}

$(document).ready(function(){
    $('#btnPlus').click(function(){

     var id_prm = (document.getElementById("modal_perimeter").value);
     var txt_prm = (document.getElementById("modal_perimeter").options[document.getElementById("modal_perimeter").selectedIndex].text);
     add_tambah(id_prm,txt_prm);
    });


});

function add_pengajuan(id_product) {
    save_method = 'add';
    txt1 ="";
    document.getElementById("daftar-perimeter").innerHTML = txt1;
    prm =[];
    $('#form_modal')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_id_product').val(id_product);
    $('#modal_pengajuan').modal('show');
    $('#btnSave').attr('disabled',true);
    $('.modal-title').text('Form Pengajuan Pendaftaran Produk dan Layanan Terintegrasi Cosmic');
}


function add_pengajuan_sertifikasi(id_product) {
    save_method = 'add_sertifikasi';
    txt1 ="";
    //document.getElementById("daftar-perimeter").innerHTML = txt1;
    prm =[];
    $('#form_modal_sertifikasi')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_id_product_sert').val(id_product);
    $('#modal_pengajuan_sertifikasi').modal('show');
    $('#btnSaveSert').attr('disabled',true);
    $('.modal-title').text('Form Pengajuan Pendaftaran Produk dan Layanan Terintegrasi Cosmic');
}

function add_tambah(id,str) {
  if (prm.indexOf(id) == -1){
    prm.push(id);
    console.log(prm);
    txt1 = "<div class='row'><div class ='col-12'><label id='lbl-"+id+"' class='control-label col-sm-8'><i class='fa fa-plus'></i> "+str+"</label>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-xs' id='btn-"+id+"' onclick='remove_tambah("+id+")'><i class='fa fa-minus'></i></button></div></div>";
     $("#daftar-perimeter").append(txt1)
  }
}
function remove_tambah(id) {
  var prm2 = arrayRemove(prm, id);
  prm = prm2;
  var strprm = document.getElementById("daftar-perimeter").innerHTML;
  //var res = strprm.replace(str, "")
  var lbltxt = document.getElementById("lbl-"+id).innerHTML;
  txt1 = "<div class='row'><div class='col-12'><label id='lbl-"+id+"' class='control-label col-sm-8'>"+lbltxt+"</label>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-xs' id='btn-"+id+"' onclick='remove_tambah("+id+")'><i class='fa fa-minus'></i></button></div></div>";
  txt1 = txt1.replace(/\'/g, '\"');
  var res = strprm.replace(txt1, "");

  document.getElementById("daftar-perimeter").innerHTML = res;
}
    //txt1 = "<div class='row'><div class ='col-12'><label class='control-label col-sm-8'><i class='fa fa-plus'></i> "+str+"</label>&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-xs'><i class='fa fa-minus'></i></button></div></div>";
    // $("#daftar-perimeter").append(txt1)

function arrayRemove(arr, value) { return arr.filter(function(ele){ return ele != value; });}

function save() {
    document.getElementById("modal_perimeter_list").value = prm;
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
    	url = "<?php echo site_url('product/ajax_add')?>";
    } else {
    	url = "<?php echo site_url('product/ajax_update')?>";
    }

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
                $('#modal_pengajuan').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                txt1 ="";
                prm =[];
                $('#form_modal')[0].reset();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[id="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[id="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal')[0].reset();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
        }
	});
}

function save_sertifikasi() {
  //  document.getElementById("modal_perimeter_list").value = prm;
    var nama = $('#nama').val();
    $('#btnSaveSert').text('Saving...');
    $('#btnSaveSert').attr('disabled',true);
    var url;

    if(save_method == 'add_sertifikasi') {
    	url = "<?php echo site_url('product/ajax_add_sertifikasi')?>";
    } else {
    	url = "";
    }

    var formData = new FormData($('#form_modal_sertifikasi')[0]);
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
                $('#modal_pengajuan_sertifikasi').modal('hide');
                $('#btnSaveSert').text('Save');
                $('#btnSaveSert').attr('disabled',false);
                txt1 ="";
                prm =[];
                $('#form_modal_sertifikasi')[0].reset();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[id="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[id="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSaveSert').text('Save');
                $('#btnSaveSert').attr('disabled',false);
                $('#form_modal_sertifikasi')[0].reset();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSaveSert').text('Save');
            $('#btnSaveSert').attr('disabled',false);
        }
	});
}

function ceklis(){
  if (document.getElementById('modal_ceklis').checked){
    $('#btnSave').attr('disabled',false);
  }else{
    $('#btnSave').attr('disabled',true);
  }

}

function ceklis_sertifikasi(){
  if (document.getElementById('modal_ceklis_sert').checked){
    $('#btnSaveSert').attr('disabled',false);
  }else{
    $('#btnSaveSert').attr('disabled',true);
  }

}



function edit_product(id_product) {

    save_method = 'update_product';
    $('#form_modal_edit')[0].reset();
    console.log('inid 1');
    $.ajax({
        url : "<?php echo site_url('product/ajax_edit_product')?>/"+id_product,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="modal_p_id_product"]').val(data.mlp_id);
            $('[name="modal_p_deskripsi"]').val(data.mlp_desc);
            $('[name="modal_p_nama_layanan"]').val(data.mlp_name);
            $('[name="modal_p_status"]').val(data.mlp_active);



            if(data.mlp_filename) {
                $('#label-photo1').text('Change Photo'); // label photo upload
                $('#modal_p_foto_txt').text(data.mlp_filename); // label photo upload
                $('#photo-preview1 div').text('(No photo)');
            } else {
                $('#label-photo1').text('Upload Photo'); // label photo upload
                  $('#modal_p_foto_txt').text('');
                $('#photo-preview1 div').text('(No photo)');
            }
            if(data.mlp_file_syarat_ketentuan) {
                $('#modal_p_file_txt').text(data.mlp_file_syarat_ketentuan); // label photo upload
            } else {
                $('#modal_p_file_txt').text('');
            }

            $('#modal_edit_product').modal('show');
            $('.modal-title').text('Edit Product');

        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
    });
}
function save_product() {

    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'update_product') {
      console.log('ini 1');
    	url = "<?php echo site_url('product/ajax_update_product')?>";
    }
    console.log('2');
    var formData = new FormData($('#form_modal_edit')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
          console.log('3');
            if(data.status) {
            	alert(data.message);
                $('#modal_edit_product').modal('hide');
                $('#btnSaveEditProduct').text('Save');
                $('#btnSaveEditProduct').attr('disabled',false);
window.location = window.location
                $('#form_modal_edit')[0].reset();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[id="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[id="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSaveEditProduct').text('Save');
                $('#btnSaveEditProduct').attr('disabled',false);
                $('#form_modal_edit')[0].reset();
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSaveEditProduct').text('Save');
            $('#btnSaveEditProduct').attr('disabled',false);
        }
	});
}
</script>

<!-- Syarat dan Ketentuan -->
<div class="modal fade" id="modal_syarat_ketentuan" role="dialog">
<div class="modal-dialog modal-lg" style="min-width:800px!important;">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title">Syarat dan Ketentuan</h5>
    </div>
    <div class="modal-body form">
      <div class="row">
        <div class="col-12">
          <iframe id="iframepdf" width="750" height="500" ></iframe>
        </div>
      </div>

    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" onclick="cancel()">Selesai</button>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add Pengajuan Atestasi -->
<div class="modal fade" id="modal_pengajuan" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title">Form Pengajuan Pendaftaran Produk dan Layanan Terintegrasi Cosmic</h5>
    </div>
    <div class="modal-body form">
        <form action="#" id="form_modal" class="form-horizontal">
            <input type="hidden" value="0" id="modal_id" name="modal_id"/>
            <input type="hidden"  id="modal_id_product" name="modal_id_product"/>
            <input type="hidden" id="kd_perusahaan_modal" name="kd_perusahaan_modal" value ="<?php echo $com; ?>"/>
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-sm-12">Nama Perusahaan <span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_nama_perusahaan" name="modal_nama_perusahaan" placeholder="Nama Perusahaan" class="form-control" type="text" value ="<?php echo $comname; ?>" disabled>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Nama Penanggung Jawab<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_nama_pj" name="modal_nama_pj" placeholder="Nama Penanggung Jawab" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Nomor Telepon Penanggung Jawab<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_no_telp_pj" name="modal_no_telp_pj" placeholder="Nomor Telepon Penanggung Jawab" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Email Penanggung Jawab<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_email_pj" name="modal_email_pj" placeholder="Email Penanggung Jawab" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
  				      <div class="form-group">
                      <label class="control-label col-sm-12">Nama Perimeter</label>
                      <div class="col-sm-12">
                          <select name="modal_perimeter" id="modal_perimeter" class="form-control">
                            <?php foreach ($perimeter as $itemperimeter){ ?>
                                <option value="<?php echo $itemperimeter->mpm_id; ?>"><?php echo $itemperimeter->mpm_name; ?></option>
                            <?php } ?>
                          </select><br />
                          <button type="button" class="btn btn-sm btn-primary" id="btnPlus" >Tambah</button>
                          <span class="help-block"></span>
                      </div>
                </div>
                <div class="form-group">
                  <input id="modal_perimeter_list" name="modal_perimeter_list"  class="form-control" type="text" hidden>

                      <label class="control-label col-sm-12"><h6>Daftar Perimeter</h6></label>
                      <div class="col-sm-12" id="daftar-perimeter">

                      </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Keterangan : </label>
                    <div class="col-sm-12">
                        Layanan Atestasi adalah penilaian pihak ketiga yang berlaku untuk 1 perusahaan setiap prosesnya
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12 edit_checked">
                  <div class="form_check">
                    <input id="modal_ceklis" name="modal_ceklis" placeholder="Checklist" class="col-sm-1 align-top" type="checkbox"  onchange="ceklis()"  >
                    <label class="col-sm-10" for="modal_ceklis" style="font-size:11px;">Kami sudah membaca dan menyetujui syarat dan ketentuan
  pengajuan layanan</label>
                  </div>
                  </div>
                  <span class="help-block"></span>

                </div>

            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-primary" id="btnSave" onclick="save()" >Save</button>
        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Add Pengajuan Sertifikasi -->
<div class="modal fade" id="modal_pengajuan_sertifikasi" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title">Form Pengajuan Pendaftaran Produk dan Layanan Terintegrasi Cosmic</h5>
    </div>
    <div class="modal-body form">
        <form action="#" id="form_modal_sertifikasi" class="form-horizontal">
            <input type="hidden" value="0" id="modal_id_sert" name="modal_id"/>
            <input type="hidden"  id="modal_id_product_sert" name="modal_id_product_sert"/>
            <input type="hidden" id="kd_perusahaan_modal_sert" name="kd_perusahaan_modal_sert" value ="<?php echo $com; ?>"/>
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-sm-12">Nama Perusahaan <span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_nama_perusahaan_sert" name="modal_nama_perusahaan_sert" placeholder="Nama Perusahaan" class="form-control" type="text" value ="<?php echo $comname; ?>" disabled>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Nama Penanggung Jawab<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_nama_pj_sert" name="modal_nama_pj_sert" placeholder="Nama Penanggung Jawab" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Nomor Telepon Penanggung Jawab<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_no_telp_pj_sert" name="modal_no_telp_pj_sert" placeholder="Nomor Telepon Penanggung Jawab" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Email Penanggung Jawab<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_email_pj_sert" name="modal_email_pj_sert" placeholder="Email Penanggung Jawab" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
  				      <!-- div class="form-group">
                      <label class="control-label col-sm-12">Nama Perimeter</label>
                      <div class="col-sm-12">
                          <select name="modal_perimeter_sert" id="modal_perimeter_sert" class="form-control">
                            <?php foreach ($perimeter as $itemperimeter){ ?>
                                <option value="<?php echo $itemperimeter->mpm_id; ?>"><?php echo $itemperimeter->mpm_name; ?></option>
                            <?php } ?>
                          </select>
                          <button type="button" class="btn btn-lg btn-primary" id="btnPlus" >Tambah</button>
                          <span class="help-block"></span>
                      </div>
                </div -->
                <!--div class="form-group">
                  <input id="modal_perimeter_list" name="modal_perimeter_list"  class="form-control" type="text" hidden>

                      <label class="control-label col-sm-12"><h6>Daftar Perimeter</h6></label>
                      <div class="col-sm-12" id="daftar-perimeter">

                      </div>
                </div-->
                <div class="form-group">
                    <label class="control-label col-sm-12">Keterangan : </label>
                    <div class="col-sm-12">
                        Layanan Sertifikasi adalah penilaian pihak ketiga yang berlaku untuk 1 perusahaan setiap prosesnya
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12 edit_checked">
                  <div class="form_check">
                    <input id="modal_ceklis_sert" name="modal_ceklis" placeholder="Checklist" class="col-sm-1 align-top" type="checkbox"  onchange="ceklis_sertifikasi()"  >
                    <label class="col-sm-10" for="modal_ceklis_sert" style="font-size:11px;">Kami sudah membaca dan menyetujui syarat dan ketentuan
  pengajuan layanan</label>
                  </div>
                  </div>
                  <span class="help-block"></span>

                </div>


            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-primary" id="btnSaveSert" onclick="save_sertifikasi()" >Save</button>
        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Edit Product -->
<div class="modal fade" id="modal_edit_product" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title">Form Pengajuan Pendaftaran Produk dan Layanan Terintegrasi Cosmic</h5>
    </div>
    <div class="modal-body form">
        <form action="#" id="form_modal_edit" class="form-horizontal">
            <input type="hidden"  id="modal_p_id_product" name="modal_p_id_product"/>
            <input type="hidden" id="kd_perusahaan_modal" name="kd_perusahaan_modal" value ="<?php echo $com; ?>"/>
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-sm-12">Nama Layanan<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_p_nama_layanan" name="modal_p_nama_layanan" placeholder="Nama Layanan" class="form-control" type="text" value ="<?php echo $comname; ?>" disabled>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-12">Deskripsi<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <textarea id="modal_p_deskripsi" row="4" style="height:100px;" form="form_modal_edit" name="modal_p_deskripsi" placeholder="Deskripsi" class="form-control"></textarea>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group" id="photo-preview1" style="display:none;">
                    <label class="control-label col-md-3">Photo</label>
                    <div class="col-md-12">
                        (No photo)
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6" id="label-photo1">Tambah Photo<span style="color:red">*</span></label>
                    <div class="col-md-12">
                        <input id="modal_p_foto" name="modal_p_foto" type="file" accept="image/jpeg,image/x-png" class="form-control" >
                        <label class="control-label col-md-12" id="modal_p_foto_txt" name="modal_p_foto_txt"></label>
                        <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-6" id="label-photo1">File Syarat dan Ketentuan<span style="color:red">*</span></label>
                    <div class="col-md-12">
                      <input type="file" id="modal_p_file" name="modal_p_file" class="form-control" accept="application/pdf">
                      <label class="control-label col-md-12" id="modal_p_file_txt" name="modal_p_file_txt"></label>
                      <font size="1" color="#800000">* Filtype yang diperbolehkan pdf dan max 30Mb</font><br>
                      <span>
                        <span class="help-block"></span>
                    </div>
                </div>
  				      <div class="form-group">
                      <label class="control-label col-sm-12">Status</label>
                      <div class="col-sm-12">
                          <select name="modal_p_status" id="modal_p_status" class="form-control">

                                <option value="t">Aktif</option>
                                <option value="f">Nonaktif</option>

                          </select>

                          <span class="help-block"></span>
                      </div>
                </div>


            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-primary" id="btnSaveEditProduct" onclick="save_product()" >Save</button>
        <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<!-- End Bootstrap modal -->
