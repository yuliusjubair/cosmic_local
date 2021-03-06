<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>COSMIC - Monitor Kesalahan Input</title>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   </style>
    </head>
<body>
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
<div class="row">

    <div class="col-sm-12">
    	<div class="table-responsive">
            <table id="table" class="table table-hover" cellspacing="0">
                <thead>
                	<!--<tr><th colspan="7" style="text-align:center;">NIK & PIC Sama</th></tr>-->
                    <tr>
                    	<th>No. </th>
                        <th>Perusahaan</th>
                        <th>Region</th>
                        <th>Perimeter</th>
                        <th>Level</th>
                        <th>PIC</th>
                        <th>FO</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();
    });

    $("#ExportCsv").on("click", function() {
        $('.buttons-csv').click();
    });
    $("#group_company").on("change", function() {
      var group_company = "<?php echo $group_company;?>";
      var str_table;

      if(group_company=== ''){
        str_table = '<?php echo base_url().'salah/get_slh'?>/';
      } else {
        str_table ='<?php echo base_url().'salah/get_slh?group_company='?>'+group_company;
      }

      var company =  $('#company').val();
        table = $('#table').DataTable({
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
                "url": "<?php echo base_url().'salah/get_slh'?>/",
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
              { "targets": 1, "orderable": true, "className": "text-left", },
              { "targets": 2, "orderable": true, "className": "text-left", },
              { "targets": 3, "orderable": true, "className": "text-left", },
              { "targets": 4, "orderable": true, "className": "text-left", },
              { "targets": 5, "orderable": true, "className": "text-left", },
              { "targets": 6, "orderable": true, "className": "text-left", },
          ]
        });
    });



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
	$('title').html('Monitoring Kesalahan Input ');
  var group_company = "<?php echo $group_company;?>";
  var str_table;

  if(group_company=== ''){
    str_table = '<?php echo base_url().'salah/get_slh'?>/';
  } else {
    str_table ='<?php echo base_url().'salah/get_slh?group_company='?>'+group_company;
  }

	var company =  $('#company').val();
    table = $('#table').DataTable({
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
            "url": "<?php echo base_url().'salah/get_slh'?>/",
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
         	{ "targets": 1, "orderable": true, "className": "text-left", },
         	{ "targets": 2, "orderable": true, "className": "text-left", },
         	{ "targets": 3, "orderable": true, "className": "text-left", },
         	{ "targets": 4, "orderable": true, "className": "text-left", },
         	{ "targets": 5, "orderable": true, "className": "text-left", },
         	{ "targets": 6, "orderable": true, "className": "text-left", },
    	]
    });
});


</script>
</body>
</html>
