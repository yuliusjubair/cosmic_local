<?php header("Cache-Control: no-cache, must-revalidate");?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   </style>

   <div class="row">
    <?php $this->load->view('company_select');?>
   </div>
    <div class="row" id="list_summary" style="line-height: 2; margin:1% 0% 1% -1%"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
            <table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>No. Laporan</th>
                        <th>Nama Perimeter</th>
                        <th>Lantai</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/easyautocomplete/easy-autocomplete.min.css">
<script src="<?php echo base_url('assets/easyautocomplete/jquery.easy-autocomplete.min.js')?>"></script>
<script type="text/javascript">
$(document).ready(function() {

	var param_mc_id = '<?php echo $p_mc_id;?>';

    if(param_mc_id!=''){
    	var company = param_mc_id;
    }else{
    	var company = $('#company').val();
    }
    $('#company').val(company);
   	get_list_summary();
    datatables();
    list_picfo();

    $("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();
    });

    $("#ExportCsv").on("click", function() {
        $('.buttons-csv').click();
    });
});


function list_picfo(){
	var company =  $('#company').val();
    var xhReq = new XMLHttpRequest();
    xhReq.open("GET", '<?php echo base_url("/reportprotokol/autocomplete_picfo/"); ?>'+"/"+company, false);
    xhReq.send(null);
    var picfo = JSON.parse(xhReq.responseText);

    var options = {
    	data: picfo,
    	adjustWidth: false,
        dataType: "json",
        getValue: function (element) {
          	return element.first_name;
    	},
    	list: {
    		match: {
        		enabled: true
      		}
    	}
    };
    $("#penanggungjawab").easyAutocomplete(options);
}

function get_list_summary(){
    var company =  $('#company').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportprotokol/ajax_list_summary/"+company,
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
    list_picfo();
}


function datatables(){
    var company =  $('#company').val();
    $('#kd_perusahaan').val(company);
    $('#kd_perusahaan_modal').val(company);
    table = $('#table').DataTable({
    	"bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": true,
        "dataSrc": "",
        "bPaginate": false,
        "searching": true,
        "paging": true,
        "info": true,
        "dom": 'Bfrtip',
        "buttons": [
            {
                "extend": 'csv',
                "text": '<i class="fa fa-file-text-o" style="color:green;"></i>&nbsp;&nbsp;CSV',
                "titleAttr": 'CSV',
                "className": 'hidden',
                "action": newexportaction
            },
            {
                "extend": 'excel',
                "text": '<i class="fa fa-file-excel-o" style="color:green;"></i>&nbsp;&nbsp;Excel',
                "titleAttr": 'Excel',
                "className": 'hidden',
                "action": newexportaction
            },
            {
                "text": '<i class="fa fa-download"></i>&nbsp;&nbsp;Download',
                "titleAttr": 'Download',
                "className": 'btn btn-primary ExportDialog',
                "action" : open_dialog
            },
        ],
        "ajax": {
            "url": "<?php echo base_url().'reportprotokol/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-right", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "orderable": true, "className": "text-left", },
            { "targets": 3, "orderable": true, "className": "text-left", },
            { "targets": 4, "orderable": true, "className": "text-left", },
            { "targets": 5, "orderable": false, "className": "text-left", },
        ]
    });

    // table.ajax.reload();
}

function get_list_summary(){
    var company =  $('#company').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>reportprotokol/ajax_list_summary/"+company,
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

function open_selesai_lapor(){
	var sh = $('#div_selesai_lapor').css('display');
	//alert(sh);
    if (sh=='none') {
    	$('#div_selesai_lapor').show();
    } else {
		$('#div_selesai_lapor').hide();
    }
}

function edit_report(idtr) {
    save_method = 'update';
    $('#form_modal_report')[0].reset();
    $.ajax({
        url : "<?php echo site_url('reportprotokol/ajax_edit')?>/"+idtr,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id"]').val(data.id);
            $('[name="kd_perusahaan_modal"]').val(data.kd_perusahaan_modal);
            $('#no_laporan').html(data.no_laporan);
            $('#perimeter').html(data.perimeter);
            $('#perimeter_level').html(data.perimeter_level);
            $('#tgl_lapor').html(data.tgl_lapor);
            $('#status').html(data.status);
            $('#laporan').html(data.laporan);
            $('[name="penanggungjawab"]').val(data.penanggungjawab);
            if(data.close==1){
        		$('#modal_ceklis').attr('checked',true);
            }else{
            	$('#modal_ceklis').removeAttr('checked',true);
            }
            $('#img_1').html(data.img_1);
            $('#img_2').html(data.img_2);
            $('#img_tl_1').html(data.img_tl_1);
            $('#img_tl_2').html(data.img_tl_2);
            $('#modal_form_report').modal('show');
            $('.modal-title').text('Edit Report');
            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
    });
}


function save() {
	block();
    var url = "<?php echo site_url('reportprotokol/ajax_update')?>";

    var formData = new FormData($('#form_modal_report')[0]);
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
                $('#modal_form_report').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal_report')[0].reset();
                refresh_list();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal_report')[0].reset();
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
</script>
<div class="modal fade" id="modal_form_report" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Laporan Protokol Form</h5>
            </div>
            <div class="modal-body form">
            <form action="#" id="form_modal_report" class="form-horizontal">
        		<input type="hidden" value="0" name="id"/>
                <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
              	<div class="col-sm-12 form-group">
    				<label class="control-label">No Laporan</label><br>
    				<label class="control-label">
    				<b><span id="no_laporan" name="no_laporan"></span></b>
    				</label>
                </div>
                <div class="col-sm-12 form-group">
    				<label class="control-label">Nama Perimeter</label><br>
    				<label class="control-label">
    				<b><span id="perimeter"></span></b>
    				</label>
                </div>
                <div class="col-sm-12 form-group">
    				<label class="control-label">Perimeter Level</label><br>
    				<label class="control-label">
    				<b><span id="perimeter_level"></span></b>
    				</label>
                </div>
               	<div class="col-sm-12 form-group">
    				<label class="control-label">Tanggal dilaporkan</label><br>
                    <label class="control-label">
    				<b><span id="tgl_lapor"></span></b>
    				</label>
                </div>
				<div class="col-sm-12 form-group">
    				<label class="control-label">Status</label><br>
                    <label class="control-label">
    				<b><span id="status"></span></b>
    				</label>
                </div>
				<div class="col-sm-12 form-group">
    				<label class="control-label">Laporan atau Keluhan</label><br>
                    <label class="control-label">
    				<b><span id="laporan"></span></b>
    				</label>
                </div>
<!--     			<div class="col-sm-12" id="img_1"></div> -->
<!--            		<div class="col-sm-12" id="img_2"></div> -->
                <div class="col-sm-12 form-group">
                	<button type="button" class="btn btn-lg btn-primary" id="btnOpenSelesaiLapor" onclick="open_selesai_lapor()" >Selesaikan Laporan</button>
        		</div>
        		<hr>
        		<div id="div_selesai_lapor" style="display:none;">
        			<div class="col-sm-12 form-group">
        				<label class="control-label">Penanggung jawab laporan</label>
                        <input id="penanggungjawab" name="penanggungjawab"
                         type="text" >
                	</div>
                	<div class="col-sm-12 form-group">
                		<div class="col-sm-12 form-group" id="img_tl_1"></div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" id="label-photo1">Bukti</label>
                            <div class="col-sm-12">
                                <input id="modal_foto_tl" name="modal_foto_tl" type="file">
                                <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
                                <span class="help-block"></span>
                            </div>
                        </div>
                     </div>
                     <div class="col-sm-12 form-group">
                     	<div class="col-sm-12 form-group" id="img_tl_2"></div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" id="label-photo1">Bukti 2</label>
                            <div class="col-sm-12">
                                <input id="modal_foto_tl2" name="modal_foto_tl2" type="file">
                                <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                  		<input type="checkbox" class="form-group col-sm-2" id="modal_ceklis" name="modal_ceklis">
                        <label class="col-sm-10 " for="modal_ceklis" style="font-size:11px;">Checklis : Laporan sudah diselesaikan</label>
                        <span class="help-block"></span>
                	</div>
                	<hr>
                </div>

        		<div class="col-sm-12 form-group">
        			<button type="button" class="btn btn-lg btn-primary" id="btnSave" onclick="save()" >Simpan Perubahan</button>
            	</div>
            	</form>
			</div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
