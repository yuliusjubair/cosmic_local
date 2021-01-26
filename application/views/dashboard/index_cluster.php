<?php header("Cache-Control: no-cache, must-revalidate");?>

  <div class="row">
      <div class="col-12">
        
            <div class="table-responsive">
              <div id="table-1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-hover dataTable no-footer" id="table_company" role="grid" aria-describedby="table-1_info">
                <thead>
                   <tr>
                        <th class="ui-th-column ui-th-ltr ui-state-default" style="text-align:center;">No</th>
                        <th style="text-align:center;">Nama Lengkap</th>
                        <th style="text-align:center;" nowrap>Persentase (%)</th>
                        <th style="text-align:center;">Dokumen Belum Upload</th>
                        <th style="text-align:center;">Sektor</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
                </div>
             </div>
            
      </div>
    </div>

<script type="text/javascript">
function link_protokol(mc_id){
	window.open('<?php echo base_url()."protokol/index/"; ?>'+mc_id);
}

$(document).ready(function() {
    $("#ExportExcel").on("click", function() {
        //table_company.button( '.buttons-excel' ).trigger();
        $('.buttons-excel').click();   
    });

    $("#ExportCsv").on("click", function() {
        //table_company.button( '.buttons-csv' ).trigger();
        $('.buttons-csv').click();   
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
    $('title').html('Progress Input Perusahaan');
    var table_company;
	table_company = $('#table_company').DataTable({ 
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
            "url": "<?php echo site_url('Dashboard/get_dashboard_protokol_cluster')?>",
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
       	 { "orderable": true, "targets": 0, "className": "text-right",  "width": "10%", },
       	 { "orderable": true, "targets": 1, "className": "text-left", "width": "35%", },
       	 { "orderable": true, "targets": 2, "className": "text-right", "width": "20%", },
       	 { "orderable": true, "targets": 3, "className": "text-left", "width": "40%", },
       	 { "orderable": true, "targets": 4, "className": "text-left", "width": "10%", },
       ],
       
    });
    
});
</script>
