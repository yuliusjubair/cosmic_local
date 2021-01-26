<?php header("Cache-Control: no-cache, must-revalidate");?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   </style>
   <div class="row">
    <?php $this->load->view('company_select');?>
   </div>
	<div class="row">
        <div class="col-sm-12">
        <div class="table-responsive">
        	<table id="table_bumn" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                    	<th>BUMN</th>
                    	<th>Foto</th>
                    	<th>Edit/Delete</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
   	</div>
    <div class="row" style="margin:2% 0% 2% 0%;"></div>
    <div class="row">
    <div class="col-sm-12">
	<div class="table-responsive">
        <table id="table" class="table table-hover" cellspacing="0">
            <thead>
                <tr>
                	<th>Username</th>
                	<th>Name</th>
                	<th>Foto</th>
                	<th>Edit/Delete</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    </div>
</div>
<script type="text/javascript">
var param_mc_id = '<?php echo $mc_id;?>';
var save_method;
var table;
var base_url = '<?php echo base_url();?>';
function datatable_bumn(){
	var company =  $('#company').val();
	table_bumn = $('#table_bumn').DataTable({ 
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": false,
        "ajax": {
            "url": "<?php echo base_url().'profile/ajaxbumn_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "className": "text-center", "width": "30%" },
            { "targets": 1, "className": "text-center", "width": "40%" },
            { "targets": 2, "className": "text-center", "width": "30%" },
        ],
    });
}

function datatable(){
    var company =  $('#company').val();
    table = $('#table').DataTable({ 
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": false,
        "ajax": {
            "url": "<?php echo base_url().'profile/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "className": "text-left", "width": "15%" },
            { "targets": 1, "className": "text-left", "width": "30%" },
            { "targets": 2, "className": "text-center", "width": "25%" },
            { "targets": 3, "className": "text-center", "width": "20%" },
            //{ "targets": 4, "className": "text-center", "width": "10%" },
        ],
    });
}

$(document).ready(function() {
	$(function () {       
	    $('input[type=file]').change(function () {
	        var val = $(this).val().toLowerCase(),
	            regex = new RegExp("(.*?)\.(jpg|png)$");
	        if (!(regex.test(val))) {
	            $(this).val('');
	            alert('Filtype yang diperbolehkan jpg dan png');
	        }

	        if(this.files[0].size/1024 > 250){
	        	$(this).val('');
	            alert('File lebih dari 250 kb');
		    }

	        var _URL = window.URL || window.webkitURL;
            var file, img;
            if ((file = this.files[0])) {
                img = new Image();
                var objectUrl = _URL.createObjectURL(file);
                img.onload = function () {
                    if(this.width!=this.height){
                    	$('[name="foto_profile"]').val('');
        	            alert('Dimensi Foto harus 1:1');
                    }
                };
                img.src = objectUrl;
            }       
	    });

	});  

	if(param_mc_id!=''){
		var company = param_mc_id;
	}else{
		var company = $('#company').val();
	}

	datatable_bumn();
	datatable();
});

function refresh_list() {
	datatable_bumn();
	datatable();
}


function edit_profile(id, type) {
	var company = $('#company').val();
	$('#modal_mc_id').val(company);
	$('#modal_type').val(type);
    $('#form')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
    $('.help-block').empty(); 
    $('#modal_id').val(id);
    $.ajax({
        url : "<?php echo site_url('profile/ajaxall_edit')?>/"+id+"/"+type,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
//             $('[name="modal_email"]').val(data.email);
//             $('[name="modal_hp"]').val(data.no_hp);
//             $('[name="modal_divisi"]').val(data.divisi);
            $('#modal_form').modal('show'); 
            $('.modal-title').text('Edit Profile'); 

            if(data.foto) {
                $('#label-photo').text('Change Photo'); // label photo upload
                if(type=='BUMN'){
                	$('#photo-preview div').html('<img src="'+base_url+'uploads/foto_bumn/'+data.foto+'" class="img-responsive"  width="200" height="200">'); // show photo
                }else{
                	$('#photo-preview div').html('<img src="'+base_url+'uploads/profile/'+data.foto+'" class="img-responsive"  width="200" height="200">'); // show photo
                }
            } else {
                $('#label-photo').text('Upload Photo'); // label photo upload
                $('#photo-preview div').text('(No photo)');
            }
            $('#photo-preview').show();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

function reload_table() {
    table.ajax.reload(null,false); 
}

function save() {
	var type = $('#modal_type').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true); 

    //alert(type);
    if(type=='BUMN'){
    	var url  = "<?php echo site_url('profile/ajaxbumn_update')?>";
    }else{
    	var url  = "<?php echo site_url('profile/ajax_update')?>";
    }
    
    $("#progress-bar").show();
    $("#form").hide();
    event.preventDefault();
    var formData = new FormData($('#form')[0]);
    $.ajax({
    	xhr : function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e){
             var percent = Math.round((e.loaded / e.total) * 100);
             $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
            });
            return xhr;
        },
         
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            if(data.status) {
            	alert(data.message);
                $('#modal_form').modal('hide');
                reload_table();
            } else {
            	alert(data.message);
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            $('#progressBar').attr('aria-valuenow', 0).css('width', 0 + '%').text(0 + '%');
            $("#progress-bar").hide();
            $("#form").show();
            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            $('#modal_form').removeClass('show');
            $('#progressBar').attr('aria-valuenow', 0).css('width', 0 + '%').text(0 + '%');
            $("#progress-bar").hide();
            $("#form").show();
            refresh_list();
        }
    });
}

function reset_password(id, fs) {
    if(confirm('Apa Anda Yakin Reset Passsword untuk User '+fs+' ?')) {
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

function delete_bumnprofile(id) {
    if(confirm('Are you sure delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('profile/ajaxbumn_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
             	alert(data.message);
                reload_table();
                refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	 alert(data.message);
            }
        });
    }
}

function delete_profile(id) {
    if(confirm('Are you sure delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('profile/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
             	alert(data.message);
                reload_table();
                refresh_list();
            },
            error: function (jqXHR, textStatus, errorThrown) {
            	 alert(data.message);
            }
        });
    }
}
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Profile Form</h3>
            </div>
            <div class="modal-body form">
				<div class="container" id="progress-bar" style="display:none;">
    				<div class="col-xs-12">
                        <div class="progress">
                            <div id="progressBar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                <span class="sr-only">0% Complete</span>
                            </div>
                        </div>                
                    </div>
                </div>
                <form action="#" id="form" class="form-horizontal" style="display:block;">
                    <input type="hidden" value="" id="modal_id" name="modal_id"/>
                    <input type="hidden" value="" id="modal_mc_id" name="modal_mc_id"/>
                    <input type="hidden" value="" id="modal_type" name="modal_type"/>
                    <div class="form-body">
<!-- 						<div class="form-group"> -->
<!--                             <label class="control-label col-sm-4">Email</label> -->
<!--                             <div class="col-sm-8"> -->
<!--                                 <input id="modal_email" name="modal_email" placeholder="Email" class="form-control" type="text" required> -->
<!--                                 <span class="help-block"></span> -->
<!--                             </div> -->
<!--                         </div> -->
<!--                         <div class="form-group"> -->
<!--                             <label class="control-label col-sm-4">Mobile No.</label> -->
<!--                             <div class="col-sm-8"> -->
<!--                                 <input id="modal_hp" name="modal_hp" placeholder="Mobile No." class="form-control" type="text" required> -->
<!--                                 <span class="help-block"></span> -->
<!--                             </div> -->
<!--                         </div> -->
<!--                         <div class="form-group"> -->
<!--                             <label class="control-label col-sm-4">Division</label> -->
<!--                             <div class="col-sm-8"> -->
<!--                                 <input id="modal_divisi" name="modal_divisi" placeholder="Division" class="form-control" type="text" required> -->
<!--                                 <span class="help-block"></span> -->
<!--                             </div> -->
<!--                         </div> -->
 						<div class="form-group" id="photo-preview" style="display:none;">
                            <label class="control-label col-md-4">Photo</label>
                            <div class="col-md-8">
                                (No photo)
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" id="label-photo">Upload Photo </label>
                            <div class="col-md-8">
                                <input name="foto_profile" type="file">
                                <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 250kb</font>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-md btn-primary">Save</button>
                <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>