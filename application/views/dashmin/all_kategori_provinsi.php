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
<div class="col-8 col-md-8 col-lg-8">
<div class="card">
          <!-- <div class="card-header">
            <h4>Check</h4>
          </div> -->
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="">
                   <table id="tbl_perimeterbyprovinsi" class="display table table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
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
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  var group_company = "<?php echo $group_company;?>";
  var str_table;
  console.log(group_company);
  if(group_company=== ''){
    str_table = '<?php echo base_url().'dashmin/ajax_perimeter_byprovinsi_all_page2'?>/';
  } else {
    str_table ='<?php echo base_url().'dashmin/ajax_perimeter_byprovinsi_all_page2?group_company='?>'+group_company;
  }

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
    $('title').html('Perimeter By Provinsi');
    tbl_perimeterbyprov = $('#tbl_perimeterbyprovinsi').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": false,
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": true,
        "paging": false,
        "info": false,
        "scrollY": '50vh',
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
        { "visible":false, "targets": 0, "orderable": true, "className": "text-left hidden", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "orderable": true, "className": "text-right", },
        ]
    });

    //onclick TR
    $('#tbl_perimeterbyprovinsi tbody').on('click', 'tr', function () {
        var data = tbl_perimeterbyprov.row( this ).data();
        block();
        /*alert( 'You clicked on '+data[0]+'\'s row' );*/
        window.location.href="<?php echo site_url('dashmin/FormPerusahaanPerimeterProv')?>/"+data[0]+"/"+data[1];
    });
});
</script>
