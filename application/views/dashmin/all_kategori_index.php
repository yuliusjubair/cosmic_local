<style>
    .dataTables_scrollHeadInner { display: none; }
</style>

<div class="row">
<div class="col-12 col-md-12 col-lg-12">
<div class="card">
          <!-- <div class="card-header">
            <h4>Check</h4>
          </div> -->
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="table-responsive">
                   <table id="tbl_cosmic_index" class="display table table-bordered" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Perusahaan</th>
                                <th>Kluster Industri</th>
                                <th>Cosmic Index</th>
                                <th>Pemenuhan Protokol</th>
                                <th>Pemenuhan Checklist Monitoring</th>
                                <th>Pemenuhan Evidence</th>
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
    str_table = '<?php echo base_url().'dashmin/ajax_cosmicindexall'?>/';
  } else {

    str_table ='<?php echo base_url().'dashmin/ajax_cosmicindexall?group_company='?>'+group_company;
  }
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
    $('title').html('Cosmic Index Perusahaan');
    tbl_cosmic_index = $('#tbl_cosmic_index').DataTable({
        "bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": false,
        "ordering": true,
        "dataSrc": "",
        "bPaginate": false,
        "searching": true,
        "paging": false,
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
            "url": str_table,
            "type": "POST"
        },
        "columnDefs": [
            { "orderable": true, "targets": 0, "className": "text-right", },
            { "orderable": true, "targets": 1,  "className": "text-left", },
            { "orderable": true, "targets": 2,  "className": "text-left", },
            { "orderable": true, "targets": 3,  "className": "text-right", },
            { "orderable": true, "targets": 4,  "className": "text-right", },
            { "orderable": true, "targets": 5,  "className": "text-right", },
            { "orderable": true, "targets": 6,  "className": "text-right", },
            { "orderable": false, "targets": 7,  "className": "text-left", },
       ],
    });

});
</script>
