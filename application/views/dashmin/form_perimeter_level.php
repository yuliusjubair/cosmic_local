<style>
    .dataTables_scrollHeadInner { display: none; }
   /* table#tabel.dataTable tbody tr:hover {
      background-color: #ffa;
      cursor: pointer;
    }
     
    table#tabel.dataTable tbody tr:hover > .sorting_1 {
      background-color: #ffa;
      cursor: pointer;
    }*/

    td.details-control {
        text-align:center;
        color:forestgreen;
        cursor: pointer;
    }
    tr.shown td.details-control {
        text-align:center; 
        color:red;
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
                                <th>Nama Perimeter</th>
                                <th>Percentage</th>
                                <th>PIC</th>
                                <th>FO</th>
                                <th></th>
                                <th>ID</th>
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
    $('title').html('Detail Perimeter');
    tabel = $('#tabel').DataTable({
       "bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "paging": false,
        "searching": true,
        "info": true,
        "dom": 'Bfrtip',
        "select":"single",
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
            "url": "<?php echo base_url().'dashmin/get_data_perimeterlevel/'?>"+"<?php echo $id?>",
            "type": "POST"
        },
       "columns": [
                
                 { "data": "0" },
                 { "data": "1" },
                 { "data": "2" },
                 { "data": "3" },
                  {
                     "className": 'details-control',
                     "orderable": false,
                     "data": null,
                     "defaultContent": '',
                     "render": function () {
                         return '<i class="fa fa-plus-square" aria-hidden="true"></i>';
                     },
                     width:"15px"
                 },
                 {"data":"4","visible":false, "className":"hidden"}
             ],
    });

     $('#tabel tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
         var tdi = tr.find("i.fa");
         var row = tabel.row(tr);
 
        if (row.child.isShown()) {
                 // This row is already open - close it
                 row.child.hide();
                 tr.removeClass('shown');
                 tdi.first().removeClass('fas fa-minus-square');
                 tdi.first().addClass('fas fa-plus-square');
             }
             else {
                 // Open this row
                 row.child(format(row.data())).show();
                 tr.addClass('shown');
                 tdi.first().removeClass('fas fa-plus-square');
                 tdi.first().addClass('fas fa-minus-square');
             }
    } );
});

function format ( d ) {
    
    var row='';
    /*row+='<table id="showme" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<th>Cluster Ruangan</th>'+
            '<th>Jumlah Ruangan</th>'+
            '<th>Status</th>'+
        '</tr>';*/
      
    /*return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Full name:</td>'+
            '<td>'+d.name+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Extension number:</td>'+
            '<td>'+d.extn+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Extra info:</td>'+
            '<td>And any further details here (images etc)...</td>'+
        '</tr>'+
    '</table>';*/
    /*$.ajax({
        url : "<?php echo site_url('dashmin/detail_tabel_perimeter')?>/"+d[4],
        type: "POST",
        dataType : 'html',
        success: function(data) {
            $('.shown').append(data);
            //row+="'"+data+"'";
            console.log(data);
        }
    });
    return row;  */

    var div = $('<div/>')
        .addClass( 'loading' )
        .text( 'Loading...' );
 
    $.ajax( {
        url: "<?php echo site_url('dashmin/detail_tabel_perimeter')?>/"+d[4],
        dataType: 'html',
        success: function ( json ) {
            div
                .html( json )
                .removeClass( 'loading' );
        }
    } );
 
    return div;

}
</script>
