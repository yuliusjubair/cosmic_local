<?php header("Cache-Control: no-cache, must-revalidate");?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
    <style>
    .datepicker{
        z-index:9999 !important
    }
    .ui-autocomplete {
        position:absolute;
        cursor:default;
    }
    </style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/jquery-ui/jquery-ui.css"/> 
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery-ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets_stisla/jquery.autocomplete.js"></script>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
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
        </div>
        <div class="col-auto">
             <?php echo $this->session->flashdata('notif') ?>
    		 <?php if($create==1){ 
    			echo '
                <div class="col-auto">
                 <button class="btn btn-success" onclick="add_vaksin()">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Vaksin</button>
                </div>';
            }?> 
     	</div>   
    </div>
	<div class="row" id="list_summary" style="line-height: 2; margin:0% 0% 0% -1%"></div>

	<?php echo $this->session->flashdata('notif') ?>

	<div class="row">
        <div class="col-sm-12">
        	<h5>Manage Data Pegawai Vaksin</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
            <table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>NIP</th>
                        <th>Kota</th>
                        <th>Tanggal Vaksin 1</th>
                        <th>Lokasi Vaksin 1</th>
                        <th>Edit/Delete</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
function open_modal(){
    $('#modal_form_vaksin_upload').modal('show');
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
	
	vaksin_default();
	anak_default();
	$('.selectpicker').selectpicker();
	get_list_summary();
	$('input[type=file]').val('');
    $('input[type=file]').change(function () {
        var val = $(this).val().toLowerCase(),
            regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
        if (!(regex.test(val))) {
            $(this).val('');
            alert('Filtype yang diperbolehkan jpg, jpeg atau png');
        }

        if(this.files[0].size/1024/1024 > 2){
            $(this).val('');
            alert('File size max 2Mb');
        }
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
    $("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();   
    });

    $("#ExportCsv").on("click", function() {
        $('.buttons-csv').click();   
    });
});

function get_list_summary(){
    var company =  $('#company').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>vaksin/ajax_list_summary/"+company,
        dataType: "html",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#list_summary").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
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
       "destroy": true,
       "language": {
           "emptyTable": "No data available"
       },
	   "responsive": true,
       "processing": true, 
       "serverSide": true, 
       "ordering": true,
       "searching" : true,
       "dataSrc": "",
       "ajax": {
    	   "url": "<?php echo base_url().'vaksin/ajax_list'?>/"+company,
           "type": "POST"
       },
       "lengthMenu": [[10, 100, -1], [10, 100, "All"]],	
	   "pageLength": 10,
       "dom": 'Bfrtip',
       "buttons": [
           {
               "extend": 'csv',
               "text": '<i class="fa fa-file-text-o" style="color:green;"></i>&nbsp;&nbsp;CSV',
               "titleAttr": 'CSV',
               "className": 'hidden',                               
               "action": newexportaction,
               "exportOptions": {
            	   "columns": [ 0, 1, 2, 3, 4, 5, 6]
            	  },
           },
           {
               "extend": 'excel',
               "text": '<i class="fa fa-file-excel-o" style="color:green;"></i>&nbsp;&nbsp;Excel',
               "titleAttr": 'Excel', 
               "className": 'hidden',                              
               "action": newexportaction,
               "exportOptions": {
            	   "columns": [ 0, 1, 2, 3, 4, 5, 6]
            	  },
           },
           {
               "text": '<i class="fa fa-download"></i>&nbsp;&nbsp;Download',
               "titleAttr": 'Download', 
               "className": 'btn btn-primary ExportDialog',
               "action" : open_dialog
           },
		],
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-right", },
            { "targets": 1, "orderable": false, "className": "text-left", },
            { "targets": 2, "orderable": false, "className": "text-left", },
            { "targets": 3, "orderable": false, "className": "text-left", },
            { "targets": 4, "orderable": false, "className": "text-left", },
            { "targets": 5, "orderable": false, "className": "text-left", },
            { "targets": 6, "orderable": false, "className": "text-left", },
            { "targets": 7, "orderable": false, "className": "text-left", },
        ]
    });

    table.ajax.reload();
}

function add_vaksin() {
	$('.selectpicker').selectpicker();
    save_method = 'add';
    $('#form_modal_vaksin')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form_vaksin').modal('show');
    $('.modal-title').text('Add Vaksin');
}

function cancel() {
    $('#form_modal')[0].reset();
}

function save() {
    //$('#btnSave').text('Saving...'); //change button text
    //$('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('vaksin/ajax_add')?>";
    } else {
        url = "<?php echo site_url('vaksin/ajax_update')?>";
    }

    var formData = new FormData($('#form_modal_vaksin')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success:  function(data){
            if(data.status==200) {
                alert(data.message);
 			    $('#modal_form_vaksin').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal_vaksin')[0].reset();
                unblock();
                refresh_list();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
               }
               $('#btnSave').text('Save');
               $('#btnSave').attr('disabled',false); 
               unblock();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        	unblock();
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false); 
        }
    });
}

function delete_vaksin(id) {
    if(confirm('Are you sure Delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('vaksin/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
                refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                 alert(data.message);
            }
        });
    }
}

function edit_vaksin(idtk) {
	$('.selectpicker').selectpicker();
    save_method = 'update';
    $('#form_modal_vaksin')[0].reset();
    $.ajax({
        url : "<?php echo site_url('vaksin/ajax_edit')?>/"+idtk,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id"]').val(data.id);
            $('[name="kd_perusahaan_modal"]').val(data.kd_perusahaan_modal);
            $('[name="nama_pegawai"]').val(data.nama_pegawai);
            $('[name="nip"]').val(data.nip);
            $('[name="sts_pegawai"]').val(data.sts_pegawai);
            $('[name="jns_kelamin"]').val(data.jns_kelamin);
            $('[name="kabupaten"]').val(data.kabupaten);
            $('[name="unit"]').val(data.unit);
            $('[name="nik"]').val(data.nik);
            $('[name="tgl_lahir"]').val(data.tgl_lahir);
            $('[name="no_hp"]').val(data.no_hp);
            $('[name="jml_keluarga"]').val(data.jml_keluarga);
            $('[name="nik_pasangan"]').val(data.nik_pasangan);
            $('[name="nama_pasangan"]').val(data.nama_pasangan);
            $('[name="nikanak_1"]').val(data.nikanak_1);
            $('[name="namaanak_1"]').val(data.namaanak_1);
            $('[name="nikanak_2"]').val(data.nikanak_2);
            $('[name="namaanak_2"]').val(data.namaanak_2);
            $('[name="nikanak_3"]').val(data.nikanak_3);
            $('[name="namaanak_3"]').val(data.namaanak_3);
            $('[name="nikanak_4"]').val(data.nikanak_4);
            $('[name="namaanak_4"]').val(data.namaanak_4);
            $('[name="nikanak_5"]').val(data.nikanak_5);
            $('[name="namaanak_5"]').val(data.namaanak_5);
            $('[name="tglvaksin_1"]').val(data.tglvaksin_1);
            $('[name="lokasivaksin_1"]').val(data.lokasivaksin_1);
            $('[name="tglvaksin_2"]').val(data.tglvaksin_2);
            $('[name="lokasivaksin_2"]').val(data.lokasivaksin_2);
            $('[name="tglvaksin_3"]').val(data.tglvaksin_3);
            $('[name="lokasivaksin_3"]').val(data.lokasivaksin_3);
            $('#anak_jml').val(data.anak_jml);
            $('#vaksin_jml').val(data.vaksin_jml);
            $('#img_1').html(data.img_1);
            $('#img_2').html(data.img_2);
            $('#img_3').html(data.img_3);
            $('#modal_form_vaksin').modal('show');
            $('.modal-title').text('Edit Vaksin');
            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
    });
}

function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            setTimeout(dt.ajax.reload, 0);
            return false;
        });
    });
    dt.ajax.reload();
};

function vaksin_default() {
	var vaksin_jml = $('#vaksin_jml').val();
	for(a=parseInt(vaksin_jml);a>1;a--){
		$('#vaksin_'+a).removeAttr("style").show();
	}
}

function anak_default() {
	var anak_jml = $('#anak_jml').val();

	for(a=parseInt(anak_jml);a>1;a--){
		$('#anak_'+a).removeAttr("style").show();
	}
}

function vaksin_dataadd() {
	var vaksin_jml = $('#vaksin_jml').val();

	if(parseInt(vaksin_jml)<3){
		var vaksin = parseInt(vaksin_jml)+1;
	}else{
		var vaksin = 3;
	}
	$('#vaksin_jml').val(vaksin);
	vaksin_default();
}

function anak_dataadd() {
	var anak_jml = $('#anak_jml').val();

	if(parseInt(anak_jml)<5){
		var anak = parseInt(anak_jml)+1;
	}else{
		var anak = 5;
	}
	$('#anak_jml').val(anak);
	anak_default();
}

</script>

<div class="modal fade" id="modal_form_vaksin" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Vaksin Form</h5>
            </div>
            <div class="modal-body form">
            <form action="#" id="form_modal_vaksin" class="form-horizontal">
        		<input type="hidden" value="0" name="id"/>
                <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
            	<label class="control-label col-sm-12">NIK KTP<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="nik" name="nik" placeholder="NIK KTP" 
                    class="form-control form-control-sm" type="text" 
                    required>
                    <span class="help-block"></span>
                </div>
            	<label class="control-label col-sm-12">Nama Pegawai<span style="color:red">*</span></label>
              	<div class="col-sm-12">
                	<input id="nama_pegawai" name="nama_pegawai" placeholder="Nama Pegawai" 
                	class="form-control form-control-sm" type="text" 
                	value="" required>
                  	<span class="help-block"></span>
              	</div>
            	<label class="control-label col-sm-12">Unit<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="unit" name="unit" placeholder="Unit" 
                    class="form-control form-control-sm" type="text" 
                    value="" required>
                    <span class="help-block"></span>
                </div>
              	<label class="control-label col-sm-12">Nomor Pegawai<span style="color:red">*</span></label>
                <div class="col-sm-12">
                    <input id="nip" name="nip" placeholder="Nomor Pegawai" 
                    class="form-control form-control-sm" type="text" 
                    value="" required>
                    <span class="help-block"></span>
                </div>
                <label class="control-label col-sm-12">Status Pegawai</label>
                <div class="col-sm-12">
                   <select name="sts_pegawai" data-live-search="true"
        			 class="form-control form-control-sm selectpicker" 
        			 data-style="btn-white btn-default">
                    <?php foreach ($sts_pegawai as $msp){ ?>
                        <option value="<?php echo $msp->msp_id; ?>">
                       		<?php echo $msp->msp_name; ?>
                        </option>
                    <?php } ?>
                    </select>
                    <span class="help-block"></span>
                </div>
                <label class="control-label col-sm-12">Jenis Kelamin</label>
                <div class="col-sm-12">
                   <select name="jns_kelamin" data-live-search="true"
            		 class="form-control form-control-sm selectpicker" 
            		 data-style="btn-white btn-default">
                        <option value="1">Laki-laki</option>
                        <option value="2">Perempuan</option>
                    </select>
                </div>
                <br>
            	<label class="control-label col-md-12">Tanggal Lahir<span style="color:red">*</span>
            	</label>
                <div class="col-sm-12">
                    <input id="tgl_lahir" name="tgl_lahir" placeholder="yyyy-mm-dd" 
                    class="form-control form-control-sm datepicker" autocomplete="off" 
                    type="text" onkeydown="return false" 
                    value="" required>
                    <span class="help-block"></span>
                </div>
                <label class="control-label col-sm-12">Kabupaten<span style="color:red">*</span></label>
              	<div class="col-sm-12">
                  	<select name="kabupaten" id="kabupaten" data-live-search="true"
        			 class="form-control form-control-sm selectpicker" 
        			 data-style="btn-white btn-default" >
                        <?php foreach ($kabupaten as $kab){ ?>
                            <option value="<?php echo $kab->mkab_id; ?>">
                            	<?php echo $kab->mkab_name; ?>
                            </option>
                        <?php } ?>
                    </select>
            		<span class="help-block"></span>
            	</div>
                <hr>
                <div id="vaksin_1">
                	<label class="control-label col-sm-12">Tanggal Vaksin Pertama</label>
                  	<div class="col-sm-12">
                    	<input id="tglvaksin_1" name="tglvaksin_1" placeholder="yyyy-mm-dd" 
                    	class="form-control form-control-sm datepicker" autocomplete="off" 
                    	type="text" onkeydown="return false" 	
                    	value="">
                        <span class="help-block"></span>
                  	</div>
                	<label class="control-label col-sm-12">Lokasi Vaksin Pertama</label>
                    <div class="col-sm-12">
                        <input id="lokasivaksin_1" name="lokasivaksin_1" placeholder="Lokasi Vaksin 1" 
                        class="form-control form-control-sm" type="text"
        				value="">
                        <span class="help-block"></span>
                    </div>
                    <div class="col-sm-12" id="img_1"></div>
        			<label class="control-label col-md-6" id="label-photo1">Upload Photo Pertama</label>
                    <div class="col-sm-12">
                        <input id="fotovaksin_1" name="fotovaksin_1" type="file">
                        <font size="1" color="#800000">* Filetype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <span class="help-block"></span>
                    </div>
        		</div>
        		<div id="vaksin_2" style="display:none;">
                	<label class="control-label col-sm-12">Tanggal Vaksin Kedua</label>
                  	<div class="col-sm-12">
                    	<input id="tglvaksin_2" name="tglvaksin_2" placeholder="yyyy-mm-dd" 
                    	class="form-control form-control-sm datepicker" autocomplete="off" 
                    	type="text" onkeydown="return false" 	
                    	value="">
                        <span class="help-block"></span>
                  	</div>
                	<label class="control-label col-sm-12">Lokasi Vaksin Kedua</label>
                    <div class="col-sm-12">
                        <input id="lokasivaksin_2" name="lokasivaksin_2" placeholder="Lokasi Vaksin 2" 
                        class="form-control form-control-sm" type="text"
        				value="">
                        <span class="help-block"></span>
                    </div>
        			<div class="col-sm-12" id="img_2"></div>
                    <label class="control-label col-md-6" id="label-photo2">Upload Photo Kedua</label>
                    <div class="col-sm-12">
                        <input id="fotovaksin_2" name="fotovaksin_2" type="file" class="required" required>
                        <font size="1" color="#800000">* Filetype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <span class="help-block"></span>
                    </div>
        		</div>
        		<div id="vaksin_3" style="display:none;">
                	<label class="control-label col-sm-12">Tanggal Vaksin Ketiga</label>
                  	<div class="col-sm-12">
                    	<input id="tglvaksin_3" name="tglvaksin_3" placeholder="yyyy-mm-dd" 
                    	class="form-control form-control-sm datepicker" autocomplete="off" 
                    	type="text" onkeydown="return false" 	
                    	value="">
                        <span class="help-block"></span>
                  	</div>
                	<label class="control-label col-sm-12">Lokasi Vaksin Ketiga</label>
                    <div class="col-sm-12">
                        <input id="lokasivaksin_3" name="lokasivaksin_3" placeholder="Lokasi Vaksin 3" 
                        class="form-control form-control-sm" type="text"
        				value="">
                        <span class="help-block"></span>
                    </div>
          			<div class="col-sm-12" id="img_3"></div>
                    <label class="control-label col-md-6" id="label-photo3">Upload Photo Ketiga</label>
                    <div class="col-sm-12">
                        <input id="fotovaksin_3" name="fotovaksin_3" type="file" class="required" required>
                        <font size="1" color="#800000">* Filetype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <span class="help-block"></span>
                    </div>
        		</div>
        		<div id="div_vaksin_jml">
        			<input type="hidden" id="vaksin_jml" name="vaksin_jml" value="1">
        		</div>
        		<div class="col-sm-3">
        			<button type="button" class="btn btn-sm btn-primary" onclick="vaksin_dataadd()">
        			<i class="fa fa-plus-circle" aria-hidden="true"></i>
        			Tambah Data Vaksin</button>
        		</div>
        		<hr>
        	  	<label class="control-label col-sm-12">Jumlah Keluarga Inti<span style="color:red">*</span>
            	</label>
                <div class="col-sm-12">
                    <input id="jml_keluarga" name="jml_keluarga" placeholder="Jumlah Keluarga Inti" 
                    class="form-control form-control-sm" type="number" 
                    value="0" required>
                    <span class="help-block"></span>
                </div>
                <label class="control-label col-sm-12">NIK Suami/Istri</label>
                <div class="col-sm-12">
                    <input id="nik_pasangan" name="nik_pasangan" placeholder="NIK Suami/Istri" 
                    class="form-control form-control-sm" type="text"
        			value="">
                    <span class="help-block"></span>
                </div>
                <label class="control-label col-sm-12">Nama Suami/Istri</label>
              	<div class="col-sm-12">
                	<input id="nama_pasangan" name="nama_pasangan" placeholder="Nama Suami/Istri" 
                	class="form-control form-control-sm" type="text"
                	value="">
                  	<span class="help-block"></span>
              	</div>
        		<div id="anak_1">
                	<label class="control-label col-sm-12">NIK Anak Pertama</label>
                    <div class="col-sm-12">
                        <input id="nikanak_1" name="nikanak_1" placeholder="NIK Anak Pertama" 
                        class="form-control form-control-sm" type="text"
            			value="">
                        <span class="help-block"></span>
                    </div>
                	<label class="control-label col-sm-12">Nama Anak Pertama</label>
                  	<div class="col-sm-12">
                    	<input id="namaanak_1" name="namaanak_1" placeholder="Nama Anak Pertama" 
                    	class="form-control form-control-sm" type="text"
                    	value="">
                      	<span class="help-block"></span>
                  	</div>
                </div>
        		<div id="anak_2" style="display:none;">
                	<label class="control-label col-sm-12">NIK Anak Kedua</label>
                    <div class="col-sm-12">
                        <input id="nikanak_2" name="nikanak_2" placeholder="NIK Anak Kedua" 
                        class="form-control form-control-sm" type="text"
            			value="">
                        <span class="help-block"></span>
                    </div>
                	<label class="control-label col-sm-12">Nama Anak Kedua</label>
                  	<div class="col-sm-12">
                    	<input id="namaanak_2" name="namaanak_2" placeholder="Nama Anak Kedua" 
                    	class="form-control form-control-sm" type="text"
                    	value="">
                      	<span class="help-block"></span>
                  	</div>
                </div>
        		<div id="anak_3" style="display:none;">
                	<label class="control-label col-sm-12">NIK Anak Ketiga</label>
                    <div class="col-sm-12">
                        <input id="nikanak_3" name="nikanak_3" placeholder="NIK Anak Ketiga" 
                        class="form-control form-control-sm" type="text"
            			value="">
                        <span class="help-block"></span>
                    </div>
                	<label class="control-label col-sm-12">Nama Anak Ketiga</label>
                  	<div class="col-sm-12">
                    	<input id="namaanak_3" name="namaanak_3" placeholder="Nama Anak Ketiga" 
                    	class="form-control form-control-sm" type="text"
                    	value="">
                      	<span class="help-block"></span>
                  	</div>
                </div>
        		<div id="anak_4" style="display:none;">
                	<label class="control-label col-sm-12">NIK Anak Keempat</label>
                    <div class="col-sm-12">
                        <input id="nikanak_4" name="nikanak_4" placeholder="NIK Anak Keempat" 
                        class="form-control form-control-sm" type="text"
            			value="">
                        <span class="help-block"></span>
                    </div>
                	<label class="control-label col-sm-12">Nama Anak Keempat</label>
                  	<div class="col-sm-12">
                    	<input id="namaanak_4" name="namaanak_4" placeholder="Nama Anak Keempat" 
                    	class="form-control form-control-sm" type="text"
                    	value="">
                      	<span class="help-block"></span>
                  	</div>
                </div>
        		<div id="anak_5" style="display:none;">
                	<label class="control-label col-sm-12">NIK Anak Kelima</label>
                    <div class="col-sm-12">
                        <input id="nikanak_5" name="nikanak_5" placeholder="NIK Anak Kelima" 
                        class="form-control form-control-sm" type="text"
            			value="">
                        <span class="help-block"></span>
                    </div>
                	<label class="control-label col-sm-12">Nama Anak Kelima</label>
                  	<div class="col-sm-12">
                    	<input id="namaanak_5" name="namaanak_5" placeholder="Nama Anak Kelima" 
                    	class="form-control form-control-sm" type="text"
                    	value="">
                      	<span class="help-block"></span>
                  	</div>
                </div>
          		<div id="div_anak_jml">
        			<input type="hidden" id="anak_jml" name="anak_jml" value="1">
        		</div>
        		<div class="col-sm-3">
        			<button type="button" class="btn btn-sm btn-primary" onclick="anak_dataadd()">
        			<i class="fa fa-plus-circle" aria-hidden="true"></i>
        			Tambah Data Anak</button>
        		</div>
        		<hr>
        		<div class="col-sm-3">
        			<button type="button" class="btn btn-lg btn-primary" id="btnSave" onclick="save()" >Simpan Perubahan</button>
            	</div>
            	</form>
			</div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

