<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COSMIC - Riwayat Pengajuan</title>
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <style>
    .datepicker{
        z-index:9999 !important
    }
    .ui-autocomplete {
        position:absolute;
        cursor:default;
    }
    </style>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/jquery-ui-1.12.1.custom/jquery-ui.css"/>
    </head>
<body>
	<div class="row">
	  <?php $this->load->view('company_select');?>
    </div>
    <div class="row" id="list_card"></div>
    <div class="row">
    	<div class="col-sm-12">
        <div class="table-responsive">
    	   <table id="table_list" class="table table-hover" cellspacing="0">
            <thead>
                <tr>
                	<th>No</th>
                    <th>Nama Layanan</th>
                    <th>Penyedia Layanan</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>
		</div>
	</div>
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
var table_list;

$(document).ready(function() {
	var company =  $('#company').val();
    get_list_card();
    refresh_table();
});

function get_list_card(){
    var group_company=$('#group_company').val();
	var company=$('#company').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url(); ?>riwayatpengajuan/ajax_card_riwayatpengajuan/"+company,
        dataType: "html",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#list_card").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function refresh_table() {
	var company =  $('#company').val();
	var table_list = $('#table_list').DataTable({
    	"bDestroy": true,
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "ajax": {
            "url": "<?php echo site_url().'riwayatpengajuan/list_riwayatpengajuan/'; ?>"+company,
            "type": "POST"
        },
        "lengthMenu": [[10, 100, -1], [10, 100, "All"]],
        "pageLength": 10,
        "columnDefs": [
            { "orderable": true, "targets": 0, "className": "text-right", },
            { "orderable": true, "targets": 1,  "className": "text-left", },
            { "orderable": true, "targets": 2,  "className": "text-left", },
            { "orderable": true, "targets": 3,  "className": "text-left", },
            { "orderable": true, "targets": 4,  "className": "text-left", },
            { "orderable": true, "targets": 5,  "className": "text-left", },
            { "visible": true, "orderable": true, "targets": 6,  "className": "text-left hidden" },
       ]
	});
    $('#table_list tbody').on('click', 'tr', function () {
    	var datax = table_list.row(this).data();
        if(datax){
            block();
            var urlsite = "<?php echo site_url() ?>";
            window.location.href=urlsite+datax[6];
            unblock();
        }
    });
}

function refresh_list() {
	var company =  $('#company').val();
    $("#kd_perusahaan").val(company);
    get_list_card();
    refresh_table();
}

</script>
</body>
</html>