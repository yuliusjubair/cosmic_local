<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/css/chocolat.css">
<script src="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
<div class="row">
<div class="col-8 col-md-8 col-lg-8">
<div class="card">
          <!-- <div class="card-header">
            <h4>Check</h4>
          </div> -->
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="row pull-right">
              <?php
              if($update==1){
                echo '<a class="btn btn-lg btn-primary" href="javascript:void(0)" title="Edit"
                onclick="edit_sosialisasi('."'".$row->ts_id."'".')">
                <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
              }

              if($delete==1){
                echo '&nbsp;<a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete"
                onclick="delete_sosialisasi('."'".$row->ts_id."'".')">
                <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
              }
              ?><br />
                </div>
              <div class="gallery gallery-md gutters-sm">'
                <div class="col-8 col-md-8 col-lg-12">
                    <?php $image1 = base_url('/uploads/sosialisasi/'.$row->ts_mc_id.'/'.$row->ts_file1);
                    //$image1 = base_url().'assets/login/left_bg_font.png';
                    ?>
                    <div class="gallery-item" data-image="<?php echo $image1?>" data-title="Image 1" href="<?php echo $image1?>" title="Image 1"
                        style="background-image: url('<?php echo $image1?>');"></div>
                    <?php
                        $image2 = base_url('/uploads/sosialisasi/'.$row->ts_mc_id.'/'.$row->ts_file2);
                    ?>
                     <div class="gallery-item" data-image="<?php echo $image2?>" data-title="Image 2" href="<?php echo $image2?>" title="Image 2"
                        style="background-image: url('<?php echo $image2?>');">
              </div>

                </div>
               <div class="gallery-item gallery-hide" data-image="<?php echo $image1?>" data-title="Image 9" href="<?php echo $image1?>" title="Image 9" style="background-image: url('<?php echo $image1?>')"></div>
            </div>

             <div class="col-6 col-sm-12">
                    <h3><?php echo $row->ts_nama_kegiatan?></h3>
                </div>
                <div class="col-6 col-sm-12">
                    <h6><b><?php echo $row->ts_jenis_kegiatan?></b></h6>
                </div>
                 <div class="col-6 col-sm-12">
                    <h6><?php echo $row->ts_tanggal?></h6>
                </div>
                 <div class="col-6 col-sm-12">
                    <?php echo $row->ts_deskripsi?>
                </div>
          </div>
        </div>
</div>
</div>
</div>
<script type="text/javascript">
function refresh_list() {
	datatables();
}


function cancel() {
    $('#form_modal')[0].reset();
    datatables();
}

function datatables(){
    var company =  '<?php echo $row->ts_mc_id; ?>';
    $('#kd_perusahaan').val(company);
    $('#kd_perusahaan_modal').val(company);
    table = $('#table').DataTable({
    "responsive": true,
    "processing": true,
    "serverSide": true,
    "ordering": true,
    "bDestroy": true,
    "dataSrc": "",
    "ajax": {
        "url": "<?php echo base_url().'sosialisasi/ajax_list'?>/"+company,
        "type": "POST"
    },
    "columnDefs": [
     	{ "targets": 0, "orderable": false, "className": "text-right", },
     	{ "targets": 1, "orderable": false, "className": "text-left", },
     	{ "targets": 2, "orderable": false, "className": "text-center", },
     	{ "targets": 3, "orderable": false, "className": "text-left", },
     	{ "targets": 4, "orderable": false, "className": "text-left", },
     	{ "targets": 5, "orderable": false, "className": "text-center", },
     	{ "targets": 6, "orderable": false, "className": "text-center", },
     	{ "targets": 7, "orderable": false, "className": "text-center", },
    ]
    });

    table.ajax.reload();
    //table.ajax.draw();
}

$(document).ready(function() {
	$('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
        }).on('changeDate', function(e){
        $(this).datepicker('hide');
	});
	datatables();
});

function delete_sosialisasi(id) {
    if(confirm('Are you sure Delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('sosialisasi/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
             	alert(data.message);
                window.location.href='<?php echo base_url()."sosialisasi"; ?>';
                //refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	 alert(data.message);
            }
        });
	}
}

function add_sosialisasi() {
    save_method = 'add';
    $('#form_modal')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Sosialisasi');
}

function edit_sosialisasi(id) {
    var company =  '<?php echo $row->ts_mc_id; ?>';
    $('#kd_perusahaan_modal').val(company);
    save_method = 'update';
    $('#form_modal')[0].reset();
    $.ajax({
        url : "<?php echo site_url('sosialisasi/ajax_edit')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="modal_id"]').val(data.ts_id);
            $('[name="kd_perusahaan_modal"]').val(data.ts_mc_id);
            $('[name="modal_tgl"]').val(data.ts_tanggal);
            $('[name="modal_kegiatan"]').val(data.ts_nama_kegiatan);
            $('[name="modal_kategori"]').val(data.ts_mslk_id);
            $('[name="modal_deskripsi"]').val(data.ts_deskripsi);
            console.log(data.ts_checklist_dampak);
            if(data.ts_checklist_dampak==='t'){
                $('[name="modal_ceklis_dampak"]').attr('checked', true);
            } else {
              $('[name="modal_ceklis_dampak"]').attr('checked', false);
            }

            $('[name="modal_persen_bulan"]').val(data.ts_prsn_dampak);
            $('[name="modal_persen_all_bulan"]').val(data.ts_prsn_dampak_all);
            $('[name="modal_bulan"]').val(data.ts_bulan);

            if(data.ts_file1) {
                $('#label-photo1').text('Change Photo'); // label photo upload
                $('#photo-preview1 div').text('(No photo)');
            } else {
                $('#label-photo1').text('Upload Photo'); // label photo upload
                $('#photo-preview1 div').text('(No photo)');
            }
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Sosialisasi');
            refresh_list();
            ceklis_dampak();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
    });
}

function ceklis_dampak(){
  if (document.getElementById('modal_ceklis_dampak').checked){
    document.getElementById("grupmodal_persen_bulan").style.display ="block" ;
    document.getElementById("grupmodal_persen_all_bulan").style.display="block";
    document.getElementById("grupmodal_bulan").style.display ="block";

  }else{
    document.getElementById("grupmodal_persen_bulan").style.display  = "none";
    document.getElementById("grupmodal_persen_all_bulan").style.display  = "none";
    document.getElementById("grupmodal_bulan").style.display  = "none";
  }

}

function save() {
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
    	url = "<?php echo site_url('sosialisasi/ajax_add')?>";
    } else {
    	url = "<?php echo site_url('sosialisasi/ajax_update')?>";
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
                $('#modal_form').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal')[0].reset();
                refresh_list();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
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
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title">Kasus Form</h5>
    </div>
    <div class="modal-body form">
        <form action="#" id="form_modal" class="form-horizontal">
            <input type="hidden" value="0" id="modal_id" name="modal_id"/>
            <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
            <div class="form-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Kegiatan<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_kegiatan" name="modal_kegiatan" placeholder="Nama Kegiatan" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
				<div class="form-group">
                    <label class="control-label col-sm-3">Kategori<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <select name="modal_kategori" id="modal_kategori" class="form-control">
                        <?php foreach ($kategori as $mslk){ ?>
                            <option value="<?php echo $mslk->mslk_id; ?>"><?php echo $mslk->mslk_name; ?></option>
                        <?php } ?>
                        </select>
                        <span class="help-block"></span>
                    </div>
                </div>
					<div class="form-group">
                    <label class="control-label col-md-3">Tanggal<span style="color:red">*</span><span id="tgl_kasus"></span></label>
                    <div class="col-sm-12">
                        <input id="modal_tgl" name="modal_tgl" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text" onkeydown="return false">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Deskripsi<span style="color:red">*</span></label>
                    <div class="col-sm-12">
                        <input id="modal_deskripsi" name="modal_deskripsi" placeholder="Deskripsi" class="form-control" type="text" required>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group" id="photo-preview1" style="display:none;">
                    <label class="control-label col-md-3">Photo</label>
                    <div class="col-md-8">
                        (No photo)
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" id="label-photo1">Upload Photo<span style="color:red">*</span></label>
                    <div class="col-md-12">
                        <input id="modal_foto_sosialisasi" name="modal_foto_sosialisasi" type="file">
                        <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <span class="help-block"></span>
                    </div>
                </div>
				<div class="form-group" id="photo-preview2" style="display:none;">
                    <label class="control-label col-md-3">Photo 2</label>
                    <div class="col-md-12">
                        (No photo)
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" id="label-photo2">Upload Photo 2</label>
                    <div class="col-md-12">
                        <input id="modal_foto_sosialisasi2" name="modal_foto_sosialisasi2" type="file">
                        <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                  <input id="modal_ceklis_dampak" name="modal_ceklis_dampak" placeholder="Checklist Dampak Timeline Penanganan Covid" class="form-control col-sm-2" type="checkbox"  onchange="ceklis_dampak()" >
                  <label class="col-sm-8 " for="modal_ceklis_dampak" style="font-size:11px;">Berdampak pada timeline penanganan Covid</label>
                  <span class="help-block"></span>

                </div>
                <div class="form-group" id="grupmodal_bulan">
                    <label class="control-label col-lg-6">Bulan Kegiatan</label>
                    <div class="col-sm-12">
                        <select name="modal_bulan" id="modal_bulan" class="form-control" >
                          <option value="">--Pilih Bulan--</option>
                          <option value="Januari">Januari</option>
                          <option value="Februari">Februari</option>
                          <option value="Maret">Maret</option>
                          <option value="April">April</option>
                          <option value="Mei">Mei</option>
                          <option value="Juni">Juni</option>
                          <option value="Juli">Juli</option>
                          <option value="Agustus">Agustus</option>
                          <option value="September">September</option>
                          <option value="Oktober">Oktober</option>
                          <option value="November">November</option>
                          <option value="Desember">Desember</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
                </div>

                <div class="form-group" id="grupmodal_persen_bulan">
                    <label class="control-label col-sm-12">Presentase Dampak Terhadap Penanganan Covid (%)</label>
                    <div class="col-sm-12">
                        <input id="modal_persen_bulan" name="modal_persen_bulan" placeholder="Pada Penanganan Covid Bulan Tersebut (%)" class="form-control" type="text"  >
                        <span class="help-block">%</span>
                    </div>
                </div>
                <div class="form-group" id="grupmodal_persen_all_bulan">
                    <label class="control-label col-sm-12">Presentase Dampak Terhadap Penanganan Covid (%)</label>
                    <div class="col-sm-12">
                        <input id="modal_persen_all_bulan" name="modal_persen_all_bulan" placeholder="Pada Penanganan Covid Keseluruhan (%)" class="form-control" type="text" >
                        <span class="help-block">%</span>
                    </div>
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
<!-- End Bootstrap modal -->
