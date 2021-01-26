<style>
    .dataTables_scrollHeadInner { display: none; }
     table#tbl_perimeterbyprovinsi.dataTable tbody tr:hover {
      background-color: #ffa;
      cursor: pointer;
    }

    table#tbl_perimeterbyprovinsi.dataTable tbody tr:hover > .sorting_1 {
      background-color: #ffa;
      cursor: pointer;
    }
</style>
<div class="row">
	<div class="col-12 col-md-12 col-lg-12">
		<div class="card">
              <div class="card-body">
                <div class="form-group">
                    <select id="level_company" name="level_company" data-live-search="true" onchange="refresh_list()"
                        class="form-control col-4" data-style="btn-white btn-default" >
                      <option value="1" selected> BUMN LEVEL 1</option>
                      <option value="2"> BUMN LEVEL 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="">
                       <table id="tbl_byperusahaan" class=" table  table-hover dataTable "  role="grid" aria-describedby="table-1_info">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Nama Perusahaan</th>
                                    <th>Total Kasus</th>
                                    <th>Konfirmasi</th>
                                    <th>Selesai</th>
                                    <th>Meninggal</th>
                                    <th>Suspek</th>
                                    <th>Kontak Erat</th>
                                    <th>Last Update</th>
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
  var level_company  = $('#level_company').val();
  var str_table ="<?php echo base_url().'dashkasus/ajax_perusahaan_all?level_company='; ?>"+level_company;

    $("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();
    });

    $("#ExportCsv").on("click", function() {
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
    $('title').html('Kasus By Perusahaan');
    tbl_byprovinsi = $('#tbl_byperusahaan').DataTable({
    	 "destroy": true,
       "language": {
          "emptyTable": "No data available"
       },
        "responsive": true,
        "processing": true,
        "serverSide": false,
        "ordering": true,
        "dataSrc": "",
        //"bPaginate": false,
        "searching": true,
        "paging": false,
      //  "info": false,
      //  "scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": str_table,
            "type": "POST"
        },
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
        "order": [],
        "columnDefs": [
       		{ "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "orderable": true, "className": "text-right", },
            { "targets": 3, "orderable": true, "className": "text-right", },
            { "targets": 4, "orderable": true, "className": "text-right", },
            { "targets": 5, "orderable": true, "className": "text-right", },
            { "targets": 6, "orderable": true, "className": "text-right", },
            { "targets": 7, "orderable": true  , "className": "text-right", },
            { "targets": 8, "orderable": true, "className": "text-left", },
        ]
    });
});


function refresh_list(){
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

	var level_company  = $('#level_company').val();
  //console.log(level_company);
	var str_table ="<?php echo base_url().'dashkasus/ajax_perusahaan_all?level_company='; ?>"+level_company;
//console.log(str_table);
	tbl_byprovinsi = $('#tbl_byperusahaan').DataTable({
		"destroy": true,
    "language": {
       "emptyTable": "No data available"
    },
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": true,
        "dataSrc": "",
        //"bPaginate": false,
        "searching": true,
        "paging": false,
      //  "info": false,
        //"scrollY": '50vh',
        "scrollCollapse": true,
        "ajax": {
            "url": str_table,
            "type": "POST"
        },
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
        "order": [],
        "columnDefs": [
          { "targets": 0, "orderable": false, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "orderable": true, "className": "text-right", },
            { "targets": 3, "orderable": true, "className": "text-right", },
            { "targets": 4, "orderable": true, "className": "text-right", },
            { "targets": 5, "orderable": true, "className": "text-right", },
            { "targets": 6, "orderable": true, "className": "text-right", },
            { "targets": 7, "orderable": true  , "className": "text-right", },
            { "targets": 8, "orderable": true, "className": "text-left", },
        ]
    });
}
</script>
