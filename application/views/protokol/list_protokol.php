<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COSMIC - Upload Protokol</title>
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    </head>
    <style type="text/css">
        .dataTables_wrapper .dataTables_paginate .paginate_button {
         background: none;
      color: black!important;
      border-radius: 4px;
      border: 1px solid #828282;
    }
    </style>
<body>
<div class="row">
<?php $this->load->view('company_select');?>
<!--     </div> -->
<!--     <div class="row"> -->
       	<div class="col-lg-2 form-group">
            <button class='btn btn-lg btn-warning btn-default' type='button'
            	onclick="window.open('https://drive.google.com/drive/folders/1S2rM1Sxb-Jfj9ZOwpIb9YHLIADKJb4gi?usp=sharing');"
            	formtarget='_self'><span class='white'><i class='ace-icon fa fa-download bigger-120'>
            </i> Example of Covid-19 Protocol </span></button>
        </div>
	</div>
    <div class="row">
    <div class="col-sm-12">
    <div class="table-responsive">
	<table id="table" class="table table-hover dataTable no-footer" role="grid" aria-describedby="table-1_info">
        <thead>
            <tr>
            	<th>No. </th>
                <th>CovidSafe Protocol</th>
                <th>File</th>
                <th>Action</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
    </div>
</div>

<script type="text/javascript">
var param_mc_id =  $('#company').val();
var save_method;
var table;
var base_url = '<?php echo base_url();?>';
var mc_id = '<?php echo $mc_id;?>';

$(document).ready(function() {
	$(function () {
	    $('input[type=file]').change(function () {
	        var val = $(this).val().toLowerCase(),
	            regex = new RegExp("(.*?)\.(pdf)$");
	        if (!(regex.test(val))) {
	            $(this).val('');
	            alert('Filtype yang diperbolehkan pdf');
	        }

	        if(this.files[0].size/1024/1024 > 30){
	        	$(this).val('');
	            alert('File lebih dari 30 Mb');
		    }
	    });

      $('#group_company').change(function(){
               var id=$(this).val();
               console.log(id);
               alert(id);
               $.ajax({
                   url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=');?>"+id,
                   method : "POST",
                   async : true,
                   dataType : 'json',
                   success: function(data){
                       var html = '';
                       var i;
                       for(i=0; i<data.length; i++){
                           html += '<option value='+data[i].mc_id+'>'+data[i].mc_name+'</option>';
                           console.log(data[i].mc_name);
                       }
                       $('#company').html(html);
                   }
               });
               return false;
           });

	});


	if(param_mc_id!=''){
		var company = param_mc_id;
	}else{
		var company = $('#company').val();
	}
console.log(company);
    table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": false,
        "ajax": {
            "url": "<?php echo base_url().'protokol/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
        	 { "targets": 0, "className": "text-right", "width": "5%" },
        	 { "targets": 1, "className": "text-left", "width": "50%" },
        	 { "targets": 2, "className": "text-center", "width": "15%" },
        	 { "targets": 3, "className": "text-center", "width": "15%" },
        	 { "targets": 4, "className": "text-center", "width": "15%" },
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
                    "url": "<?php echo base_url().'protokol/ajax_list'?>/"+company,
                    "type": "POST"
                },
                "columnDefs": [
                 	 { "targets": 0, "className": "text-right", "width": "5%" },
                	 { "targets": 1, "className": "text-left", "width": "50%" },
                	 { "targets": 2, "className": "text-center", "width": "15%" },
                	 { "targets": 3, "className": "text-center", "width": "20%" },
                	 { "targets": 4, "className": "text-center", "width": "15%" },
                ],
            });
        table.ajax.reload();
        // table.ajax.draw();
}

function add_protokol(protokol_id) {
	var company =  $('#company').val();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal

    $('#modal_id').val(0);
    $('#modal_mc_id').val(company);
    $('#modal_mpt_id').val(protokol_id);

    $('.modal-title').text('Add Protokol'); // Set Title to Bootstrap modal title
    $('#label-protokol').text('Upload Protokol'); // label photo upload
}

function edit_protokol(id) {
	//alert(id);
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('protokol/ajax_edit')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#modal_id').val(id);
            $('#modal_mc_id').val(data.tbpt_mc_id);
            $('#modal_mpt_id').val(data.tbpt_mpt_id);
            $('#modal_page').val(data.tbpt_page);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Protokol');

            if(data.photo) {
                $('#label-photo').text('Change Protokol');
            } else {
                $('#label-photo').text('Upload Protokol');
            }
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
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('protokol/ajax_add')?>";
    } else {
        url = "<?php echo site_url('protokol/ajax_update')?>";
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
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);
            $('#progressBar').attr('aria-valuenow', 0).css('width', 0 + '%').text(0 + '%');
            $("#progress-bar").hide();
            $("#form").show();
            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);
            $('#modal_form').removeClass('show');
            $('#progressBar').attr('aria-valuenow', 0).css('width', 0 + '%').text(0 + '%');
            $("#progress-bar").hide();
            $("#form").show();
            refresh_list();
        }
    });
}

function delete_protokol(id) {
    if(confirm('Are you sure delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('protokol/ajax_delete')?>/"+id,
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
                <h3 class="modal-title">Protokol Form</h3>
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
                    <input type="hidden" value="0" id="modal_id"  name="modal_id"/>
                    <input type="hidden" value="" id="modal_mc_id" name="modal_mc_id"/>
                    <input type="hidden" value="" id="modal_mpt_id" name="modal_mpt_id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-protokol">Upload Protokol</label>
                            <div class="col-md-6">
                                <input name="protokol_file" id="protokol_file" type="file" multiple="true">
                                <font size="1" color="#800000">* Filtype yang diperbolehkan pdf dan max 30Mb</font>
                                <span class="help-block"></span>
                             </div>
                         </div>
<!--                          <div class="form-group"> -->
<!--                         	<label class="control-label col-md-3" id="label-page">Halaman ke:</label> -->
<!--                             <div class="col-md-6"> -->
<!--                             	<input name="modal_page" id="modal_page" type="text"> -->
<!--                             </div> -->
<!--                         </div> -->
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
