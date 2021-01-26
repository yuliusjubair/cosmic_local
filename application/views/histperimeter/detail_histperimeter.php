<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/css/chocolat.css">
<script src="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
<div class="row">
	<div class="col-12 col-md-12 col-lg-12">
		<div class="card">
			<div class="form-group" style="margin: 8px">
			<article class="article article-style-c">
			<h4><?php echo $det_histperimeter->mpm_name; ?>
				
			<?php 
				if($det_histperimeter->status_tutup=="2"){
				    echo '<span style="color:red;"><font size="3">(Level Tutup)</font></span>';
				
			?>
				<a class="pull-right btn btn-lg btn-primary" href="javascript:void(0)" title="Verifikasi" onclick="open_dialog('<?php echo $det_histperimeter->mpml_id?>')">
			                Buka Perimeter
			    </a>
			<?php } ?>
			</h4>
			<h5><?php echo $det_histperimeter->mpml_name; ?></h5>
			<p><?php echo $det_histperimeter->mpml_ket; ?></p>
			
			<?php 
				if($det_histperimeter->status_tutup!=2){
				    echo '<div id="detail_monitoring"></div>';
				}
			?>
		</article>
	</div>
	</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	get_detail_monitoring();
});

function get_detail_monitoring(){
	var nik = "<?php echo $nik; ?>";
	var mpml_id = "<?php echo $mpml_id; ?>";
    var url = "<?php echo site_url('histperimeter/ajax_monitoring_mpml')?>/"+nik+'/'+mpml_id;
    $.ajax({ 
        type: "GET",
        url: url,     
        dataType: "html",               
        success: function(response){ 
        	$('#detail_monitoring').html(response);
        }
    });
}

function open_dialog(mpml_id){
    $('[name="modal_id"]').val(mpml_id);
    $('.name').html("Apa Anda Yakin Ingin Buka Perimter ?");
    $('#modal_form').modal('show');
    $('.modal-title').text('');
}

function save() {
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;
    block();
    url = "<?php echo site_url('histperimeter/open_perimeter')?>";
    var formData = new FormData($('#form_modal')[0]);
    $.ajax({
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
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal')[0].reset();
                window.location.reload();
                // refresh_list();
            } 
         unblock();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            unblock();
        }
    });
}


</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form">
                <form action="#" id="form_modal" class="form-horizontal">
                    <input type="hidden" value="0" id="modal_id" name="modal_id"/>
                    <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
                    <input type="hidden" value="0" name="id">
                    <input type="hidden" value="" name="username" id="username">
                    <input type="hidden" name="email" id="email">
                    <div class="form-body d-flex justify-content-center">
                        <span class="name"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                 <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Batalkan</button>
                <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="save()" >Buka Perimeter</button>
               
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
