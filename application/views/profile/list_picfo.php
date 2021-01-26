<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    </head> 
    <style type="text/css">
        input[type="text"].til_l67_text:invalid + [for="til_167"] {
          color: red;
        }
        input[type="text"].til_l67_text:valid + [for="til_167"] {
          display: none;
        }
        label[for="til_167"]:after {
          content: "'should add max 16 characters'";
        }
    </style>
<body>
<div class="row">
  	<?php $this->load->view('company_select');?>
     <div class="col-sm-3 form-group">
    <?php if($create==1){ 
    echo '<div class="form-group">
            <button class="btn btn-success" onclick="add_manageusers()">
            <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah PIC/FO</button>
          </div>'; 
    }
    ?>
    </div>
</div>
<div class="row">
	<div class="col-sm-12 form-group">
    	<table id="table" class="table table-hover" cellspacing="0">
            <thead>
                <tr>
                	<th>Username</th>
                	<th>Nama</th>
                	<th>Role</th>
                    <th>Reset Password</th>
                	<th>Detail</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Users Form</h5>
            </div>
            <div class="modal-body form">
                <form  method="POST" action="<?php echo base_url()?>profile/add_picfo" class="form-horizontal" id="form_modal" class="form-horizontal">
                    <input type="hidden" id="company_id" name="company_id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="til_l67 control-label col-sm-6">Username/NIK<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="modal_username" min="1" minlength="10" 
                                 maxlength="16" 
                                 autocomplete="off" 
                                 pattern="^[0-9a-zA-Z_.-]{5,100}" 
                                 name="modal_username" placeholder="Username" class="til_l67_text form-control" type="text" required><label for="til_167"></label>
                                <span class="til_167 help-block"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">Nama<span style="color:red">*</span></label>
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
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-md btn-primary" id="btnSave">Save</button>
                <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
var param_mc_id = '<?php echo $mc_id;?>';
var save_method;
var table;
var base_url = '<?php echo base_url();?>';

$(document).ready(function() {
    $("#btnSave").prop("disabled","disabled");
    $("#modal_username").keyup(function(i){
        len=$(this).val().length;
        console.log(len);
        if(len>=16)
        {
          $("#btnSave").prop("disabled",false);
        }else{
          $("#btnSave").prop("disabled","disabled");
        }
	});

    if(param_mc_id!=''){
		var company = param_mc_id;
	}else{
		var company = $('#company').val();
	}

    table = $('#table').DataTable({ 
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": false,
        "ajax": {
            "url": "<?php echo base_url().'profile/ajaxuser_resetlist'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
        	 { "targets": 0, "className": "text-center"},
        	 { "targets": 1, "className": "text-center"},
        	 { "targets": 2, "className": "text-center"},
        	 { "targets": 3, "className": "text-center"},
        ],
    });
});

function refresh_list() {
var company =  $('#company').val();
var table = $('#table').DataTable({ 
                "serverSide": true,
                "processing": true,
                "bDestroy": true,
                "ordering": false,
                "ajax": {
                    "url": "<?php echo base_url().'profile/ajaxuser_resetlist'?>/"+company,
                    "type": "POST"
                },
                "columnDefs": [
                 	 { "targets": 0, "className": "text-center"},
                	 { "targets": 1, "className": "text-center"},
                	 { "targets": 2, "className": "text-center"},
                	 { "targets": 3, "className": "text-center"},
                ],
            });
        table.ajax.reload();
        // table.ajax.draw();
}

function add_manageusers() {
    save_method = 'add';
    $('#form_modal')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Users');
    var company = $('#company').val();
    $('#company_id').val(company);
}

function reload_table() {
    table.ajax.reload(null,false); 
}

function reset_password(id) {
    if(confirm('Apa Anda Yakin Reset Passsword untuk User ini ?')) {
        $.ajax({
            url : "<?php echo site_url('profile/ajax_reset')?>/"+id,
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

function delete_user(id) {
	if(confirm('Yakin melakukan penghapusan User ini ?')) {
        $.ajax({
            url : "<?php echo site_url('profile/ajax_deleteuser')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
				alert(data.message);
				refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
             	alert(data.message);
             	refresh_list();
            }
        });
	}
}
</script>
</body>
</html>