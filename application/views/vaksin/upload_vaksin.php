<?php header("Cache-Control: no-cache, must-revalidate");?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   </style>
<div class="container"> 
    <div class="row">
        <div class="form-group col-sm-6" style="margin-left:-15px">
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
    <div class="row"  style="margin-left:-30px">
        <div class="col-auto">
             <?php echo $this->session->flashdata('notif') ?>
    		 <?php if($create==1){ 
    			echo '<button type="button" onclick="open_modal()" id="btnUpload" class="btn btn-primary">
    			<i class="ace-icon fa fa-upload bigger-120"></i> Upload</button>
                </div>
                ';
            }?> 
            <div class="col">
              	<button class="" style="border:0px solid black; background-color: transparent;white-space: nowrap; color:blue; margin-top: 7px" type="button"
              	onclick="window.open('<?php echo base_url().'uploads/protokol/example/Example_Input_BUMN_-_Vaksin_Pegawai.xlsx'; ?>');"
                formtarget='_self'><i class='ace-icon fa fa-download bigger-120'>
                </i> Contoh File Vaksin
                </button>
            </div>
     	</div>   
	</div>
	<?php echo $this->session->flashdata('notif') ?>
<br>
<br>
	<div class="row">
        <div class="col-sm-12">
        	<h5>Data Upload Excel Vaksin Terakhir</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
            <table id="table_tmp" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>NIP</th>
                        <th>Kota</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal_form_upload" role="dialog" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h5 class="modal-title">Upload File Vaksin</h5>
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
</div>
<script type="text/javascript">
function open_modal(){
    $('#modal_form_upload').modal('show');
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
	$('.selectpicker').selectpicker();
	$('input[type=file]').val('');
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

    datatables();
    $("#ExportExcel").on("click", function() {
        //table_company.button( '.buttons-excel' ).trigger();
        $('.buttons-excel').click();   
    });

    $("#ExportCsv").on("click", function() {
        //table_company.button( '.buttons-csv' ).trigger();
        $('.buttons-csv').click();   
    });
    
});

function refresh_list() {
    datatables();
}

function datatables(){
    var company =  $('#company').val();
    $('#kd_perusahaan').val(company);
    $('#kd_perusahaan_modal').val(company);
    table_tmp = $('#table_tmp').DataTable({ 
       "destroy": true,
       "language": {
           "emptyTable": "No data available"
       },
	   "responsive": true,
       "processing": true, 
       "serverSide": true, 
       "ordering": true,
       "dataSrc": "",
       "ajax": {
    	   "url": "<?php echo base_url().'vaksin/ajax_list_tmp'?>/"+company,
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
       //"buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
       "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-right", },
            { "targets": 1, "orderable": false, "className": "text-left", },
            { "targets": 2, "orderable": false, "className": "text-left", },
            { "targets": 3, "orderable": false, "className": "text-left", },
            { "targets": 4, "orderable": false, "className": "text-left", },
            { "targets": 5, "orderable": false, "className": "text-left", },
        ]
    });

    table_tmp.ajax.reload();
}

function save_excel() {
    var company =  $('#company').val();
    $('#kd_perusahaan').val(company);
    var url ="<?php echo site_url('vaksin/ajax_exceladd')?>";
    var formData = new FormData($('#form')[0]);
    block();
    $('#modal_form_upload').modal('hide');
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            alert(data.message);
            setTimeout(function(){
            	unblock();
            }, 500);
            $('input[type=file]').val('');
            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            setTimeout(function(){
                unblock();
            }, 500);
            $('input[type=file]').val('');
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
