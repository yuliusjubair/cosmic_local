<style>
    .dataTables_scrollHeadInner { display: none; }
    table#tabel.dataTable tbody tr:hover {
      background-color: #ffa;
      cursor: pointer;
    }
     
    table#tabel.dataTable tbody tr:hover > .sorting_1 {
      background-color: #ffa;
      cursor: pointer;
    }
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
                   <table id="tabel" class="display table table-hover" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID</th>
                                <th>Nama Perimeter</th>
                                <th>Jumlah Level</th>
                                <th>Nama Perusahaan</th>
                                <!-- <th>Provinsi</th> -->
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
    //ganti breadcump
    $('.text_breadcump').html('<?php echo str_replace('%20', ' ', $name);?>');
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
    $('title').html('Kategori Perimeter');
    tabel = $('#tabel').DataTable({
        "bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": false,
        "ordering": true,
        "dataSrc": "",
        "bPaginate": false,
        "searching": true,
        "paging": false,
        "info": false,
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
            "url": "<?php echo base_url().'dashmin/getDataByPerusahaanPerimeterProv/'?>"+"<?php echo $id?>",
            "type": "POST"
        },
        "columnDefs": [
            { "orderable": false, "targets": 0, "className": "text-right", },
            { "visible":false, "orderable": true, "targets": 1,  "className": "text-left hidden", },
            { "orderable": false, "targets": 2,  "className": "text-left", },
            { "orderable": false, "targets": 3,  "className": "text-right", },
            { "orderable": false, "targets": 4,  "className": "text-left", },
            { "orderable": false, "targets": 5,  "className": "text-left", },
        
       ],
    });

    //onclick TR
    $('#tabel tbody').on('click', 'tr', function () {
        var data = tabel.row( this ).data();
        /*alert( 'You clicked on '+data[0]+'\'s row' );*/
        block();
        window.location.href="<?php echo site_url('dashmin/FormPerimeterLevel')?>/"+data[1];
    });

});
</script>
