<div class="container">
    <div class="rows" >
        <div class="col-sm-4">
        	<div id="div_company">
            <select name="group_company" id="group_company" data-live-search="true" onchange="refresh_list()"
             class="form-control selectpicker"
             data-style="btn-white btn-default">
                <option value="3" >Semua</option>
                <option value="1" selected >BUMN</option>
                <option value="2" >Non BUMN</option>
            </select>
            </div>
        </div>
    </div>
    <br>
	<div class="rows">
		<div class="col-sm-12">
        <table id="table_user" style="width:100%;overflow:auto; !important" 
        class="table table-striped table-bordered data">
            <thead>
                <tr>
                    <th style="text-align:center;">No</th>
                    <th style="text-align:center;">Nama BUMN</th>
                    <th style="text-align:center;">Username</th>
        			<th style="text-align:center;">Reset</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </table>
        </div>
	</div>
</div>
<script type="text/javascript">
function refresh_list(){
	datatables();
}

function datatables(){
	var group_company =  $('#group_company').val();
	table_user = $('#table_user').DataTable({
        "bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": false,
        "ordering": true,
        "dataSrc": "",
        "bPaginate": true,
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
        	"url": "<?php echo site_url().'admin/ajax_list/?group_company='?>"+group_company,
            "type": "POST"
        },
        "columnDefs": [
       	 { "orderable": true, "targets": 0, "className": "text-right"},
       	 { "orderable": true, "targets": 1, "className": "text-left" },
       	 { "orderable": true, "targets": 2, "className": "text-center"},
       	 { "orderable": true, "targets": 3, "className": "text-center"},
       ],
    });
}

$(document).ready(function() {
	refresh_list();
});

function reset_password(id, fs) {
    if(confirm('Apa Anda Yakin Reset Passsword untuk User '+fs+' ?')) {
        $.ajax({
            url : "<?php echo site_url('admin/ajax_reset')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
             	alert(data.message);
                refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	 alert(data.message);
            }
        });
    }
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