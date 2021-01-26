<?php header("Cache-Control: no-cache, must-revalidate");?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   </style>
<!-- <div class="container">
 -->   
  <!-- <div class="row">
        <div class="col-sm-6">
            <div class="form-group ">
               <select id="company" name="company" data-live-search="true" onchange="refresh_list()"
            	class="form-control  selectpicker" data-style="btn-white btn-default" >
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
        </div>
    </div> -->
    <div class="row">
        <?php $this->load->view('company_select');?>
    </div>
 	<div class="row">
            
             <?php echo $this->session->flashdata('notif') ?>
			 <?php if($create==1){ 
				echo '<div class="col-auto"><button type="button" onclick="open_modal()" id="btnUpload" class="btn btn-primary">
				<i class="ace-icon fa fa-upload bigger-120"></i> Upload</button>
                </div>
                <div class="col-auto">
                 <button class="btn btn-success" onclick="add_kasus()">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Kasus</button>
                </div>';
            }?> 
            <?php if($delete==1){ 
                 echo '<div class="col-auto order-0">
                 <button class="btn btn-white btn-danger" onclick="reset_kasus()">
                 <i class="ace-icon fa fa-refresh bigger-120"></i> Hapus Semua Kasus</button>
                 </div>';
            }?>

            <div class="col">
              	<button class="" style="border:0px solid black; background-color: transparent;white-space: nowrap; color:blue; margin-top: 7px" type="button"onclick="window.open('<?php echo base_url().'uploads/protokol/example/Example_Input_BUMN_-_Pegawai_Terdampak.xlsx'; ?>');"
                	formtarget='_self'><i class='ace-icon fa fa-download bigger-120'>
                	</i> Contoh File Pegawai Terdampak 
            	</button>
            </div>
        
		</div>	
	<div class="row" id="new_list_summary" style="line-height: 2; margin:2% 0% 1% -1%"></div>
    <div class="row">
        <div class="col-sm-12"> 
            <div class="table-responsive">
            <table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Nama</th>
                        <th>Status Pegawai</th>
                        <th>Provinsi</th>
                        <th>Kota</th>
                        <th>Status Kasus</th>
                        <th>Tanggal Dinyatakan Konfirmasi</th>
                        <th>Tanggal Dinyatakan Meninggal</th>
                        <th>Tempat Perawatan</th>
                        <th>Tanggal Dinyatakan Selesai</th>
                        <th>Tindakan</th>
                        <th>Edit/Delete</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>
<div class="modal fade" id="modal_form_upload" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Upload File Pegawai Terdampak</h5>
            </div>
            <div class="modal-body form">
                <div class="text-center">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" id="kd_perusahaan" name="kd_perusahaan"/>
                        <input type="file" id="file_import" name="file_import" class="btn form-control">
                        <input type="file" name="uploadfile" id="img" style="display:none;"/>
                        <!-- <label for="img"><i class="glyphicon glyphicon-upload"></i>Upload File</label> -->
                        <font size="1" color="#800000">* Tipe File xlsx atau xls dan max 10Mb</font>
                        <div>
                            <button type="button" onclick="save_excel()" id="btnUpload" class="btn btn-xs btn-success">
                            <i class='ace-icon fa fa-upload bigger-120'></i> Upload</button>
                        </div>
                    </form>
                    <div id="progress-bar" style="display:none;margin-top: 11px;">
                        <img src="<?php echo base_url(); ?>assets/images/busy.gif"/>&nbsp;Waiting For Download File...
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

function open_modal(){
    $('#modal_form_upload').modal('show');
}

function kasus_tgl(id){
	if(id == 3){
		$('#tgl').attr('disabled',true);
		$('#tgl_positif').removeAttr('disabled',true);
	    $('#tgl_kasus').html('');
	}else if( id == 4 || id == 5){
		$('#tgl').removeAttr('disabled',true);
		$('#tgl_positif').removeAttr('disabled',true);
		
        var kasus_text  =  $("#sts_kasus option:selected").text();
        $('#tgl_kasus').html(kasus_text);
	}else{
		$('#tgl').attr('disabled',true);
		$('#tgl_positif').attr('disabled',true);
		
        $('#tgl_kasus').html('');
	}
}

function reset_kabupaten(id_provinsi, id_kabupaten){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kasus/kabupaten",
        data: {id_provinsi : id_provinsi, id_kabupaten : id_kabupaten},
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

function get_list_summary(){
    var company =  $('#company').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kasus/ajax_list_summary_new/"+company,
        dataType: "html",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#new_list_summary").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function refresh_list() {
    datatables();
    get_list_summary();
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
            "url": "<?php echo base_url().'kasus/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-right", },
            { "targets": 1, "orderable": false, "className": "text-left", },
            { "targets": 2, "orderable": false, "className": "text-left", },
            { "targets": 3, "orderable": false, "className": "text-left", },
            { "targets": 4, "orderable": false, "className": "text-left", },
            { "targets": 5, "orderable": false, "className": "text-left", },
            { "targets": 6, "orderable": false, "className": "text-center", },
            { "targets": 7, "orderable": false, "className": "text-center", },
            { "targets": 8, "orderable": false, "className": "text-left", },
            { "targets": 9, "orderable": false, "className": "text-center", },
            { "targets": 10, "orderable": false, "className": "text-center", },
            { "targets": 11, "orderable": false, "className": "text-left", "width": "20%" },
        ]
    });

    table.ajax.reload();
}

function delete_kasus(id) {
    if(confirm('Are you sure Delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('kasus/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
                refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	  alert('Error get data from ajax');
            }
        });
    }
}

$(document).ready(function() {
	var group = <?php echo $group;?>;
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
                    console.log(data[i].mc_name);
                }
                $('#company').html(html);
            }
        });
	}
	
     $('input[type=file]').change(function () {
        var val = $(this).val().toLowerCase(),
            regex = new RegExp("(.*?)\.(xlsx|xls)$");
        if (!(regex.test(val))) {
            $(this).val('');
            alert('Filtype yang diperbolehkan xlsx atau xls');
        }

        if(this.files[0].size/1024/1024 > 10){
            $(this).val('');
            alert('File size max 10Mb');
        }
    });  
        
    kasus_tgl($("#sts_kasus").val());
    reset_kabupaten(1);
    get_list_summary();
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    }).on('changeDate', function(e){
        $(this).datepicker('hide');
    });;
    
    $("#provinsi").change(function(){ 
        $("#kabupaten").hide();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>kasus/kabupaten",
            data: {id_provinsi : $("#provinsi").val(), id_kabupaten: $("#kabupaten").val()},
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
    });
    datatables();
});

function reset_kasus(kd_perusahaan) {
    var company = $('#company').val();
    if(confirm('Are you sure Clear All data?')) {
        $.ajax({
            url : "<?php echo site_url('kasus/ajax_reset')?>/"+company,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
                refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	alert('Error get data from ajax');
            }
        });
    }
}

function add_kasus() {
    save_method = 'add';
    $('#form_modal')[0].reset();
    reset_kabupaten(1, 1);
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Kasus');
}

function cancel() {
    $('#form_modal')[0].reset();
    reset_kabupaten(1);
}

function save() {
    var nama = $('#nama').val();
    var tgl = $('#tgl').val();
    var tgl_now = "<?php echo $tgl_now; ?>";
    $('#btnSave').text('Saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(tgl > tgl_now){
        alert('Tgl tidak boleh lebih dari hari ini');
    }

    if(save_method == 'add') {
        url = "<?php echo site_url('kasus/ajax_add')?>";
    } else {
        url = "<?php echo site_url('kasus/ajax_update')?>";
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
            //alert(data.status);
            if(data.status==200) {
            	alert(data.message);
                $('#modal_form').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal')[0].reset();
                reset_kabupaten(1);
                refresh_list();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false); 
            }
         
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false); 
            cancel();
        }
    });
}

function save_excel() {
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true); 
    var company =  $('#company').val();
    $('#kd_perusahaan').val(company);
    var url ="<?php echo site_url('kasus/ajax_exceladd')?>";
    var formData = new FormData($('#form')[0]);
    block();
    $('#modal_form_upload').modal('hide');
    //$("#progress-bar").show();
    $("#form_div").hide();
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            alert(data.message);
            $('input[type=file]').val('');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            $("#progress-bar").hide();
            $("#form_div").show();
            setTimeout(function(){
                unblock();
            }, 500);

            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            $("#progress-bar").hide();
            $("#form_div").show();
            setTimeout(function(){
                unblock();
            }, 500);
            refresh_list();
        }
    });
}

function edit_kasus(idtk) {
    save_method = 'update';
    $('#form_modal')[0].reset();
    $.ajax({
        url : "<?php echo site_url('kasus/ajax_edit')?>/"+idtk,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id"]').val(data.id);
            $('[name="nama_pegawai"]').val(data.nama_pegawai);
            if(data.provinsi > 0){
                var provinsi_val=data.provinsi;
            }else{
                var provinsi_val=1;
            }
            $('[name="provinsi"]').val(provinsi_val); 
            reset_kabupaten(provinsi_val, data.kabupaten);
            $('[name="sts_kasus"]').val(data.sts_kasus);
            kasus_tgl(data.sts_kasus);
            $('[name="sts_pegawai"]').val(data.sts_pegawai);
            kasus_tgl(data.sts_kasus);
            $('[name="tgl"]').val(data.tgl);
            $('[name="tgl_positif"]').val(data.tgl_positif);
            $('[name="tmpt_rawat"]').val(data.tmpt_rawat);
            $('[name="tindakan"]').val(data.tindakan);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Kasus');
            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
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
                    <input type="hidden" value="0" name="id"/>
                    <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Nama Pegawai<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="nama_pegawai" name="nama_pegawai" placeholder="Nama Pegawai" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Status Kasus<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select name="sts_kasus" id="sts_kasus" class="form-control"  onchange="kasus_tgl(this.value)">
                                <?php foreach ($sts_kasus as $msk){ ?>
                                    <option value="<?php echo $msk->msk_id; ?>"><?php echo $msk->msk_name2; ?></option>
                                <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
  						<div class="form-group">
                            <label class="control-label col-md-4">Tanggal Positif</label>
                            <div class="col-sm-12">
                                <input id="tgl_positif" name="tgl_positif" placeholder="yyyy-mm-dd" class="form-control datepicker" autocomplete="off" type="text" onkeydown="return false">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Tanggal <span id="tgl_kasus"></span></label>
                            <div class="col-sm-12">
                                <input id="tgl" name="tgl" placeholder="yyyy-mm-dd" class="form-control datepicker" autocomplete="off" type="text" onkeydown="return false">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Status Pegawai<span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                <select name="sts_pegawai" class="form-control">
                                <?php foreach ($sts_pegawai as $msp){ ?>
                                    <option value="<?php echo $msp->msp_id; ?>"><?php echo $msp->msp_name; ?></option>
                                <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Provinsi<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select name="provinsi" id="provinsi" class="form-control">
                                <?php foreach ($provinsi as $pro){ ?>
                                    <option value="<?php echo $pro->mpro_id; ?>"><?php echo $pro->mpro_name; ?></option>
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
                        <div class="form-group">
                            <label class="control-label col-sm-4">Tempat Perawatan</label>
                            <div class="col-sm-12">
                                <input id="tmpt_rawat" name="tmpt_rawat" placeholder="Tempat Perawatan" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Tindakan<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="tindakan" name="tindakan" placeholder="Tindakan" class="form-control" type="text" required>
                                <span class="help-block"></span>
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