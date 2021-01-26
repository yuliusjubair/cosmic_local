<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COSMIC - Upload Perimeter</title>
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    </head>
<body>
<div class="container-fluid">
    <div class="row">
		<?php $this->load->view('company_select');?>
		<div class="col-sm-6" style="margin:0% 0% 0% -2%;">
            <div class="form-group col-sm-12">
                <button class='btn btn-lg btn-warning btn-default' type='button'
                	onclick="window.open('<?php echo base_url().'uploads/protokol/example/Example_of_Perimeter_Input_Template.xlsx'; ?>');"
                	formtarget='_self'><span class='white'><i class='ace-icon fa fa-download bigger-120'>
                </i> Example of Perimeter Input Template </span></button>
			</div>
			<div id="progress-bar" style="display:none;">
        		<div class="col-xs-12">
             		<img src="<?php echo base_url(); ?>assets/images/busy.gif"/>Loading...
                </div>
           	</div>
        </div>
		<div class="col-sm-6" style="margin:0% 0% 0% -1%;display:block;">
			<div class="form-group col-sm-12">
            <?php echo $this->session->flashdata('notif') ?>
            <?php if($create==1) {?>
            <form action="#" id="form" class="form-horizontal">
              <input type="hidden" value="" id="kd_perusahaan" name="kd_perusahaan"/>
                <label>Upload Excel Perimeter</label>
                <input type="file" id="file_import" name="file_import" class="form-control">
                <font size="1" color="#800000">* Filtype yang diperbolehkan xlsx, xls dan max 10Mb</font>
                <div><button type="button" onclick="save()" id="btnUpload" class="btn btn-lg btn-success">
                <i class='ace-icon fa fa-upload bigger-120'></i> Upload</button></div>
           	 </form>
           	 <?php } ?>
             </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
        	<table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                    	<th>No. </th>
                        <th>Perimeter</th>
                        <th>NIK PIC</th>
                        <th>PIC</th>
                        <th>Level</th>
                        <th>NIK FO</th>
                        <th>FO</th>
                        <th>Status</th>
						            <th>Keterangan Status</th>
                        <th>Created</th>
                        <th>Provinsi</th>
                        <th>Kota</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	$('input[type=file]').val('');
	var company =  $('#company').val();
	$('#kd_perusahaan').val(company);
    $('input[type=file]').change(function () {
        var val = $(this).val().toLowerCase(),
            regex = new RegExp("(.*?)\.(xlsx|xls)$");
        if (!(regex.test(val))) {
            $(this).val('');
            alert('Filtype yang diperbolehkan xlsx atau xls');
        }

        if(this.files[0].size/1024/1024 > 10){
        	$(this).val('');
        	alert('File size max 10Mb');
        }
    });
	var company =  $('#company').val();
    table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": false,
        "ajax": {
            "url": "<?php echo base_url().'perimeter/ajax_list'?>/"+company,
            "type": "POST"
        },
    });

    $('#group_company').change(function(){
      block();

             var id=$(this).val();
               console.log(id);
             $.ajax({
                 url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=');?>"+id,
                 method : "POST",
                 async : true,
                 dataType : 'json',
                 success: function(data){
                     $('#company').html('');
                     var html = '';
                     var i;
                     for(i=0; i<data.length; i++){
                         html += '<option value='+data[i].mc_id+'>'+data[i].mc_name+'</option>';
                         console.log(data[i].mc_name);
                     }
                     $('#company').append(html);
                     refresh_list();
                     $('#company').selectpicker('refresh');
                     unblock();
                 }
             });
             return false;
         });
});

function refresh_list() {
	var company =  $('#company').val();
	$('#kd_perusahaan').val(company);
	var table = $('#table').DataTable({
	                "serverSide": true,
	                "processing": true,
	                "bDestroy": true,
	                "ordering": false,
	                "ajax": {
	                    "url": "<?php echo base_url().'perimeter/ajax_list'?>/"+company,
	                    "type": "POST"
	                },
	            });
	table.ajax.reload();
	// table.ajax.draw();
}

function save() {
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var company =  $('#company').val();
	$('#kd_perusahaan').val(company);
    var url ="<?php echo site_url('perimeter/ajax_add')?>";
    var formData = new FormData($('#form')[0]);

    $("#progress-bar").show();
    $("#form_div").hide();
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
        	alert(data.message);
			$('input[type=file]').val('');
            $("#progress-bar").hide();
            $("#form_div").show();
			refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            $("#progress-bar").hide();
            $("#form_div").show();
            refresh_list();
        }
    });
}
</script>
</body>
</html>
