<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COSMIC - List Kasus Covid</title>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   .control-label{
    font-size: 15px !important;
   }
   </style>
    </head>
<body>
    <div class="row">

		<div class="form-group col-sm-6" style="margin-left:-15px">
            <?php if ($group == 1){?>
              <div class="col-sm-3 form-group">
                    <select id="group_company" name="group_company" data-live-search="true"
                      class="form-control " data-style="btn-white btn-default" >
                        <option value="3" selected>SEMUA</option>
                        <option value="1" >BUMN</option>
                        <option value="2" >NON BUMN</option>
                    </select>
              </div>
              <?php }?>
			<div class="col-sm-10">
			<select id="company" name="company" data-live-search="true" onchange="refresh_list()"
            	class="form-control selectpicker " data-style="btn-white btn-default" >
                <?php
            	foreach ($company->result() as $row) {
            	    if($p_mc_id!=NULL && $p_mc_id==$row->mc_id){
            	        $mc_selected='selected="selected"';
            	    }else{
            	        $mc_selected='';
            	    }
            	?>
                <option value="<?php echo $row->mc_id;?>" <?php echo $mc_selected;?>>
        			<?php echo $row->mc_name;?>
        	    </option>
        		<?php } ?>
            </select>
            </div>
			<div class="col-sm-12">
            	<h6>Realisasi Rencana Kerja Penanganan Covid</h6>
                <button class="btn btn-success" onclick="add_sosialisasi()">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Event</button>
            </div>
         </div>
         <div class="form-group col-sm-6">
         	<div class="col-sm-12">
         		<h6>Rencana Kerja Penanganan Covid</h6>
         	</div>
            <div class="col-sm-12">
                <?php echo $this->session->flashdata('notif') ?>
                <?php if($create==1) {?>
                <form action="#" id="form" class="form-horizontal">
                  <input type="hidden" value="" id="kd_perusahaan_rk" name="kd_perusahaan_rk"/>
                    <input type="file" id="file_rk" name="file_rk" class="form-control">
                    <font size="1" color="#800000">* Filtype yang diperbolehkan pdf dan max 30Mb</font><br>
                    <span>
                    <button type="button" onclick="save_rk()" id="btnUpload" class="btn btn-md btn-primary">
                    <i class='ace-icon fa fa-upload bigger-120'></i> Upload</button></span>
               	   	<span id="rencanakerja_download">
               	   	<?php echo "<button type='button' onclick=\"download_rk()\" class='btn btn-md btn-primary'
                    formtarget='_self'><span class='bluexx'><i class='ace-icon fa fa-download bigger-120'>
                    </i> Download Rencana Kerja</span></button>";?>
                    </span>
                    <input type="hidden" value="" id="filename_rk" name="filename_rk"/>
               	 </form>
               	 <?php } ?>
            </div>
         </div>
    </div>
    <div class="row">
    	<div class="form-group col-sm-6" style="margin-left:-15px">

     	</div>
 	</div>
    <div class="row col-sm-12">
        <div class="table-responsive">
        	<table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                    	<th>No. </th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jenis Kegiatan</th>
                        <th>Deskripsi</th>
     					<th>Foto</th>
     					<th>Foto 2</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
	</div>
<script type="text/javascript">
function refresh_list() {
	var company =  $('#company').val();
	datatables();
	get_download();
}


function cancel() {
	$('#form_modal')[0].reset();
	datatables();
}

function datatables(){
	var company =  $('#company').val();
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
	var group = '<?php echo $group;?>';
	//alert(group);

    $('#group_company').selectpicker();
    $('#company').selectpicker();
    $('#group_company').change(function(){
       block();
       var id=$(this).val();
       console.log(id);
       $.ajax({
           url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=');?>"+id,
           method : "POST",
           async : true,
           dataType : 'json',
           success: function(data){
               $('#company').html('');
               var html = '';
               var i;
               for(i=0; i<data.length; i++){
                   html += '<option value='+data[i].mc_id+'>'+data[i].mc_name+'</option>';
                   // console.log(data[i].mc_name);
               }
               $('#company').append(html);
               refresh_list();
               $('#company').selectpicker('refresh');
               unblock();
           }
       });
       return false;
   });
    
	if(group ==1){
        $.ajax({
            url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=1');?>",
            method : "POST",
            async : true,
            dataType : 'json',
            success: function(data){
                var html = '';
                var i;
                for(i=0; i<data.length; i++){
                    html += '<option value='+data[i].mc_id+'>'+data[i].mc_name+'</option>';
                    //console.log(data[i].mc_name);
                }
                $('#company').html(html);
            }
        });
	}
	$(function () {
	    $('input[type=file]').change(function () {
	    	if($(this).attr('name')=='file_rk'){
    	        var val = $(this).val().toLowerCase(),
    	            regex = new RegExp("(.*?)\.(pdf|PDF)$");
    	        if (!(regex.test(val))) {
    	            alert('Filtype yang diperbolehkan pdf');
    	            $(this).val('');
    	        }
    	        if(this.files[0].size/1024/1024 > 30){
    	            alert('File lebih dari 30 Mb');
    	        	$(this).val('');
    		    }
	    	}
	    });
	});

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
	var company =  $('#company').val();

});


function add_sosialisasi() {
    save_method = 'add';
    $('#form_modal')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    document.getElementById("grupmodal_persen_bulan").style.display  = "none";
    document.getElementById("grupmodal_persen_all_bulan").style.display  = "none";
    document.getElementById("grupmodal_bulan").style.display  = "none";
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Sosialisasi');
}

function edit_sosialisasi(id) {
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
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
    });
}

function save() {
	var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;
    block();
    if(save_method == 'add') {
        url = "<?php echo site_url('sosialisasi/ajax_add')?>";
    } else {
        url = "<?php echo site_url('sosialisasi/ajax_update')?>";
    }

    var formData = new FormData($('#form_modal')[0]);
    //console.log(formData);
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

function save_rk() {
	block();
    var company =  $('#company').val();
	$('#kd_perusahaan_rk').val(company);
    var url ="<?php echo site_url('sosialisasi/ajaxrk_add')?>";
    var formData = new FormData($('#form')[0]);

    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
        	alert(data.message);
            get_download();
            $('input[type=file]').val('');
            unblock();
        },
        error: function (jqXHR, textStatus, errorThrown) {
        	alert('Error adding / update data');
        	$('input[type=file]').val('');
        	unblock();
        }
    });
}

function get_download(){
	var company =  $('#company').val();
    var urlrk = "<?php echo site_url('sosialisasi/download_rk')?>/"+company;
    $.ajax({
        type: "GET",
        url: urlrk,
        dataType: "html",
        success: function(response){
            if(response!=0){
            	$('#rencanakerja_download').show();
            	$('#filename_rk').val(response);
            }else{
            	$("#rencanakerja_download").removeAttr("style").hide();
            	$('#filename_rk').val(response);
            }
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

function download_rk(){
	var res = $('#filename_rk').val();
    var urldownloadrk = "<?php echo site_url('uploads/rencanakerja')?>/";
	window.location = urldownloadrk+res;
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
                            <label class="control-label col-sm-6">Nama Kegiatan<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="modal_kegiatan" name="modal_kegiatan" placeholder="Nama Kegiatan" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-lg-6">Kategori Kegiatan<span style="color:red">*</span></label>
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
                            <label class="control-label col-lg-6">Tanggal Kegiatan<span style="color:red">*</span><span id="tgl_kasus"></span></label>
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
                            <div class="col-md-12">
                                (No photo)
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-6" id="label-photo1">Tambah Photo<span style="color:red">*</span></label>
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
                            <label class="control-label col-md-3" id="label-photo2">&nbsp;</label>
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
                <div style="text-align:center;">
                <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="save()" >Tambah Kegiatan</button>
            </div>
                <!-- <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>
