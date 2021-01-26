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
<body>
<?php $this->load->view('company_select');?>
<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
        	<table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                    	<th>BUMN</th>
                    	<th>Username</th>
                    	<th>Reset Password</th>
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

$(document).ready(function() {
   	
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
            "url": "<?php echo base_url().'profile/ajaxbumn_resetlist'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
           	 { "targets": 0, "className": "text-center", "width": "30%" },
        	 { "targets": 1, "className": "text-center", "width": "40%" },
        	 { "targets": 2, "className": "text-center", "width": "30%" },
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
                    "url": "<?php echo base_url().'profile/ajaxbumn_resetlist'?>/"+company,
                    "type": "POST"
                },
                "columnDefs": [
                	 { "targets": 0, "className": "text-center", "width": "30%" },
                	 { "targets": 1, "className": "text-center", "width": "40%" },
                	 { "targets": 2, "className": "text-center", "width": "30%" },
                ],
            });
        table.ajax.reload();
        // table.ajax.draw();
}

function reload_table() {
    table.ajax.reload(null,false); 
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
</script>
</body>
</html>