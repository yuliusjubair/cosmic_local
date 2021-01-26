<?php header("Cache-Control: no-cache, must-revalidate");?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   .dataTables_scrollHead {
      height: 0;
    }
   </style>
<div class="container"> 
    <div class="row" id="list_summary"></div>
	<div class="row"><div class="col-6 form-group">
        <button class="btn btn-lg btn-primary" onclick='open_modal_download()'><i class="fa fa-download"></i>&nbsp;Download Rangkuman</button>
    </div></div>
    <div class="row">
    	<div class="col-md-4">
    		<div class="card card-hero">
    			<div class="card-header">
        			<div class="card-description">Jumlah Pegawai By Perusahaan</div>
        			<label style="font-size: 12px">Total : <span class="total_vaksinmc"></span></label>
        		</div>
                <div class="card-body p-0">
    				<div class="tickets-list">
                        <table id="tbl_dashvaksinmc" class="display table table-bordered">
                            <tbody>
                            </tbody>
                        </table>
                  	</div>
            	</div>
          	</div>
    	</div>
    	<div class="col-md-4">
    		<div class="card card-hero">
    			<div class="card-header">
        			<div class="card-description">Jumlah Pegawai By Provinsi</div>
        			<label style="font-size: 12px">Total : <span class="total_vaksinmpro"></span></label>
        		</div>
                <div class="card-body p-0">
    				<div class="tickets-list">
                        <table id="tbl_dashvaksinmpro" class="display table table-bordered">
                            <tbody>
                            </tbody>
                        </table>
                  	</div>
            	</div>
          	</div>
    	</div>
    	<div class="col-md-4">
    		<div class="card card-hero">
    			<div class="card-header">
        			<div class="card-description">Jumlah Pegawai By Kabupaten</div>
        			<label style="font-size: 12px">Total : <span class="total_vaksinmkab"></span></label>
        		</div>
                <div class="card-body p-0">
    				<div class="tickets-list">
                        <table id="tbl_dashvaksinmkab" class="display table table-bordered">
                            <tbody>
                            </tbody>
                        </table>
                  	</div>
            	</div>
          	</div>
    	</div>
    	<div class="col-md-4">
    		<div class="card card-hero">
    			<div class="card-header">
        			<div class="card-description">Jumlah Pegawai By Lokasi 1</div>
        			<label style="font-size: 12px">Total : <span class="total_vaksinlokasi1"></span></label>
        		</div>
                <div class="card-body p-0">
    				<div class="tickets-list">
                        <table id="tbl_dashvaksinlokasi1" class="display table table-bordered">
                            <tbody>
                            </tbody>
                        </table>
                  	</div>
            	</div>
          	</div>
    	</div>
    	<div class="col-md-4">
    		<div class="card card-hero">
    			<div class="card-header">
        			<div class="card-description">Jumlah Pegawai By Lokasi 2</div>
        			<label style="font-size: 12px">Total : <span class="total_vaksinlokasi2"></span></label>
        		</div>
                <div class="card-body p-0">
    				<div class="tickets-list">
                        <table id="tbl_dashvaksinlokasi2" class="display table table-bordered">
                            <tbody>
                            </tbody>
                        </table>
                  	</div>
            	</div>
          	</div>
    	</div>
    	<div class="col-md-4">
    		<div class="card card-hero">
    			<div class="card-header">
        			<div class="card-description">Jumlah Pegawai By Lokasi 3</div>
        			<label style="font-size: 12px">Total : <span class="total_lokasi3"></span></label>
        		</div>
                <div class="card-body p-0">
    				<div class="tickets-list">
                        <table id="tbl_dashvaksinlokasi3" class="display table table-bordered">
                            <tbody>
                            </tbody>
                        </table>
                  	</div>
            	</div>
          	</div>
    	</div>
    </div> 
</div>

<div class="modal fade" id="modal_form_dashboard" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Download File</h5>
            </div>
            <div class="modal-body form">
                <div class="text-center">
                    <button onclick="loader_rangkuman()" class="btn btn-primary" type="button" id="ExportExcel">
                    <i class="fa fa-file-text-o"></i>&nbsp;Format Excel</button>
                    <button onclick="loader_rangkuman()" class="btn btn-primary" type="button" id="ExportCsv">
                    <i class="fa fa-file-text-o"></i>&nbsp;Format CSV</button>
                    <div id="progress-bar" style="display:none;margin-top: 11px;">
                        <img src="<?php echo base_url(); ?>assets/images/busy.gif"/>&nbsp;Waiting For Download File...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function open_modal_download(){
	$('#modal_form_dashboard').modal('show');
}

function download_rangkuman(){
    var myurl1='<?php echo base_url().'dashvaksin/excel_dashvaksin'; ?>';
    window.location = myurl1;
}

function download_csv(){
    var myurl2='<?php echo base_url().'dashvaksin/csv_dashvaksin'; ?>';
    window.location = myurl2;
}

$(document).ready(function() {
	get_list_summary();
	get_dashvaksinmc();
	get_dashvaksinmpro();
	get_dashvaksinmkab();
	get_dashvaksinlokasi1();
	get_dashvaksinlokasi2();
	get_dashvaksinlokasi3();
    $("#ExportExcel").on("click", function() {
        download_rangkuman();
    });

    $("#ExportCsv").on("click", function() {
        download_csv();
    });
});

function get_list_summary(){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>dashvaksin/ajax_list_summary",
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

function get_dashvaksinmc(){
	tbl_dashvaksinmc = $('#tbl_dashvaksinmc').DataTable({ 
		"responsive": true,
        "processing": true, 
        "serverSide": true, 
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": false, 
        "paging": false, 
        "info": false,
        "scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashvaksin/ajax_vaksin_byperusahaan'?>",
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": false, "className": "text-right", },

        ],
        "order": [],
        drawCallback:function(settings)
        {
         $('.total_vaksinmc').html(settings.json.recordsTotal);
        }
    });
}

function get_dashvaksinmpro(){
	tbl_dashvaksinmc = $('#tbl_dashvaksinmpro').DataTable({ 
		"responsive": true,
        "processing": true, 
        "serverSide": true, 
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": false, 
        "paging": false, 
        "info": false,
        "scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashvaksin/ajax_vaksin_byprovinsi'?>",
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": false, "className": "text-right", },

        ],
        drawCallback:function(settings)
        {
         $('.total_vaksinmpro').html(settings.json.recordsTotal);
        }
    });
}

function get_dashvaksinmkab(){
	tbl_dashvaksinmc = $('#tbl_dashvaksinmkab').DataTable({ 
		"responsive": true,
        "processing": true, 
        "serverSide": true, 
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": false, 
        "paging": false, 
        "info": false,
        "scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashvaksin/ajax_vaksin_bykabupaten'?>",
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": false, "className": "text-right", },

        ],
        drawCallback:function(settings)
        {
         $('.total_vaksinmkab').html(settings.json.recordsTotal);
        }
    });
}


function get_dashvaksinlokasi1(){
	tbl_dashvaksinlokasi1 = $('#tbl_dashvaksinlokasi1').DataTable({ 
		"responsive": true,
        "processing": true, 
        "serverSide": true, 
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": false, 
        "paging": false, 
        "info": false,
        "scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashvaksin/ajax_vaksin_bylokasi1'?>",
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": false, "className": "text-right", },

        ],
        "order": [],
        drawCallback:function(settings)
        {
         $('.total_vaksinlokasi1').html(settings.json.recordsTotal);
        }
    });
}

function get_dashvaksinlokasi2(){
	tbl_dashvaksinlokasi2 = $('#tbl_dashvaksinlokasi2').DataTable({ 
		"responsive": true,
        "processing": true, 
        "serverSide": true, 
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": false, 
        "paging": false, 
        "info": false,
        "scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashvaksin/ajax_vaksin_bylokasi2'?>",
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": false, "className": "text-right", },

        ],
        "order": [],
        drawCallback:function(settings)
        {
         $('.total_vaksinlokasi2').html(settings.json.recordsTotal);
        }
    });
}

function get_dashvaksinlokasi3(){
	tbl_dashvaksinlokasi3 = $('#tbl_dashvaksinlokasi3').DataTable({ 
		"responsive": true,
        "processing": true, 
        "serverSide": true, 
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": false, 
        "paging": false, 
        "info": false,
        "scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashvaksin/ajax_vaksin_bylokasi3'?>",
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": false, "className": "text-right", },

        ],
        "order": [],
        drawCallback:function(settings)
        {
         $('.total_vaksinlokasi3').html(settings.json.recordsTotal);
        }
    });
}
</script>