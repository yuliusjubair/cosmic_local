	<div class="row">
        <div class="col-sm-6">
        <?php if($create==1){ 
		echo '<div class="form-group">
                <button class="btn btn-success" onclick="add_manageusers()">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Users</button>
              </div>'; 
        }
		?>
        </div>
	</div>
<div class="row">
	<div class="table-responsive">
		<div class="col-sm-12">
        <table id="table_musers" class="table table-hover data">
            <thead>
                <tr>
                    <th style="text-align:center;">No</th>
                    <th style="text-align:center;">Username</th>
                    <th style="text-align:center;">Nama</th>
        			<th style="text-align:center;">Group</th>
        			<th style="text-align:center;">Edit/Delete</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </table>
        </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	datatables();
});

function add_manageusers() {
    save_method = 'add';
    $('#form_modal_popup')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form_popup').modal('show');
    $('.modal-title_popup').text('Add Users');
}

function role(id){
	if( id == 2){
		$('#modal_company').removeAttr('hidden',true);
		$('#modal_sektor').attr('hidden',true);
		$('#modal_company').val(0);
	    $('#modal_sektor').val(0);
	}else if( id == 5){
		$('#modal_company').attr('hidden',true);
		$('#modal_sektor').removeAttr('hidden',true);
		$('#modal_company').val(0);
	    $('#modal_sektor').val(0);
	}else {
		$('#modal_company').attr('hidden',true);
		$('#modal_sektor').attr('hidden',true);
		$('#modal_company').val(0);
	    $('#modal_sektor').val(0);
	 }
}

function save() {
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('admin/ajax_manageusers_add')?>";
    } else {
        url = "<?php echo site_url('admin/ajax_manageusers_update')?>";
    }

    var formData = new FormData($('#form_modal_popup')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            //alert(data.message);
            if(data.status) {
                $('#modal_form_popup').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                refresh_list();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false); 
                refresh_list();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false); 
        }
    });
}

function delete_manageusers(id) {
    if(confirm('Are you sure Delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('admin/ajax_manageusers_delete')?>/"+id,
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

function datatables(){
    var table_musers;
    table_musers = $('#table_musers').DataTable({ 
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
            "url": "<?php echo site_url('admin/ajax_manageusers_list')?>",
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
       	 { "orderable": false, "targets": 0, "className": "text-right" },
       	 { "orderable": false, "targets": 1, "className": "text-left" },
       	 { "orderable": false, "targets": 2, "className": "text-center" },
       	 { "orderable": false, "targets": 3, "className": "text-center" },
       	 { "orderable": false, "targets": 4, "className": "text-center" },
       ],
    });
    table_musers.ajax.reload();


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
    }
}

function edit_manageusers(idx) {
    $('.help-block').empty();
    save_method = 'update';
    $('#form_modal_popup')[0].reset();
    $.ajax({
        url : "<?php echo site_url('admin/ajax_manageusers_edit')?>/"+idx,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
        	//role(data.v_group_id);
            $('[name="modal_id"]').val(data.v_user_id);
            $('[name="modal_username"]').val(data.v_username);
            $('[name="modal_groups"]').val(data.v_group_id);
            $('[name="modal_password"]').val('');
            $('[name="modal_name"]').val(data.v_first_name);
            $('[name="modal_company"]').val(data.v_mc_id);
            $('[name="modal_sektor"]').val(data.v_ms_id);
            $('#modal_form_popup').modal('show');
            $('.modal-title_popup').text('Edit Users');
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
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
}
</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_popup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title_popup">Users Form</h5>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_modal_popup" class="form-horizontal">
                    <input type="hidden" value="0" id="modal_id" name="modal_id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">Username<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="modal_username" name="modal_username" placeholder="Username" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
					<div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">Password<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="modal_password" name="modal_password" placeholder="Password" class="form-control" type="password" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
					<div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">Name<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="modal_name" name="modal_name" placeholder="Name" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Role<span style="color:red">*</span></label>
                        <div class="col-sm-12">
                            <select name="modal_groups" id="modal_groups" class="form-control"  onchange="role(this.value)">
                            <?php foreach ($mst_group as $mg){ ?>
                                <option value="<?php echo $mg->id; ?>"><?php echo $mg->description; ?></option>
                            <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Company</label>
                        <div class="col-sm-12">
                            <select name="modal_company" id="modal_company" class="form-control">
                            <option value="0">--- Company ---</option>
                            <?php foreach ($mst_company as $mc){ ?>
                                <option value="<?php echo $mc->mc_id; ?>"><?php echo $mc->mc_name; ?></option>
                            <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Sektor</label>
                        <div class="col-sm-12">
                            <select name="modal_sektor" id="modal_sektor" class="form-control">
                            <option value="0">--- Sektor ---</option>
                            <?php foreach ($mst_sektor as $ms){ ?>
                                <option value="<?php echo $ms->ms_id; ?>"><?php echo $ms->ms_name; ?></option>
                            <?php } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="save()" >Save</button>
                <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>