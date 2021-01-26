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
<?php if ($group == 1){?>
<div class="row col-md-4" >
    <select name="group_company" id="group_company" data-live-search="true" onchange="refresh_list()"
     class="form-control selectpicker"
     data-style="btn-white btn-default">
        <option value="3" >Semua</option>
        <option value="1" selected >BUMN</option>
        <option value="2" >Non BUMN</option>
    </select>
</div>
<?php } ?>
<br>
<div class="row" id="list_summary" style="line-height: 2;"></div>
<div class="row">
<div class="col-12 col-md-12 col-lg-12">
<div class="card">
	<div class="card-body"><!--  style="margin: 10px auto;" -->
		<div class="form-group">
            <div class="table-responsive">
               <table id="tbl_report_protokol" class="display table table-bordered" >
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Perusahaan</th>
                            <th>Jumlah Laporan</th>
                            <th>Jumlah Laporan Selesai</th>
                            <th>Presentase Penanganan</th>
                            <th></th>
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
</div>
<script type="text/javascript">
$(document).ready(function() {
	var group_company =  $('#group_company').val();
	datatablesx();
	get_list_summary();

    $("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();
    });

    $("#ExportCsv").on("click", function() {
        $('.buttons-csv').click();
    });

    var today = new Date();
    var dd = today.getDate();

    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    if(dd<10)
    {dd='0'+dd;}

    if(mm<10)
    {mm='0'+mm;}
    today = yyyy+'-'+mm+'-'+dd;
    console.log(today);
    $('title').html('Report Protokol');
});

function get_list_summary(){
	var group_company =  $('#group_company').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>dashreport/ajax_list_summary/"+group_company,
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

function refresh_list(){
	get_list_summary();
	datatablesx();
}

function datatablesx(){
	var group_company =  $('#group_company').val();
    tbl_report_protokol = $('#tbl_report_protokol').DataTable({
        "bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": false,
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
            "url": "<?php echo base_url().'dashreport/ajax_reportall'?>/"+group_company,
            "type": "POST"
        },
        "columnDefs": [
            { "orderable": true, "targets": 0, "className": "text-right", },
            { "orderable": true, "targets": 1,  "className": "text-left", },
            { "orderable": true, "targets": 2,  "className": "text-right", },
            { "orderable": true, "targets": 3,  "className": "text-right", },
            { "orderable": true, "targets": 4,  "className": "text-right", },
            { "orderable": true, "targets": 5,  "className": "text-right", },
       ],
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
