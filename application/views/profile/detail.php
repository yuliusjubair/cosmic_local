<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/css/chocolat.css">
<script src="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
<div class="row">
<div class="col-8 col-md-8 col-lg-8">
<div class="card">
          <!-- <div class="card-header">
            <h4>Check</h4>
          </div> -->
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="row pull-right">
              <?php 
              if($update==1){
                echo '<a class="btn btn-lg btn-primary" href="javascript:void(0)" title="Edit"
                onclick="edit_nik('."'".$row->id."'".')">
                <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
              }
              $jml_mpm = $this->profile_model->get_perimeterbynik($row->username)->result();
             
              if($delete==1 && count($jml_mpm)==0){
                echo '&nbsp;<a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete"
                onclick="delete_user('."'".$row->id."'".')">
                <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
              }
              ?><br />
                </div>
              <div class="gallery gallery-md gutters-sm">'
                <div class="col-8 col-md-8 col-lg-12">
                    <?php $image1 = base_url('/upload/profile/'.$row->foto);
                    //$image1 = base_url().'assets/login/left_bg_font.png';
                    ?> 
                    <div class="gallery-item" data-image="<?php echo $image1?>" data-title="Image 1" href="<?php echo $image1?>" title="Image 1" 
                        style="background-image: url('<?php echo $image1?>');"></div>
                    
             
                </div>
               <div class="gallery-item gallery-hide" data-image="<?php echo $image1?>" data-title="Image 9" href="<?php echo $image1?>" title="Image 9" style="background-image: url('<?php echo $image1?>')"></div>
            </div>

             <div class="col-6 col-sm-12">
                    <h7>NIK :</h7> <h6><?php echo $row->username?></h6>
                </div>
                 <div class="col-6 col-sm-12">
                    <h6><b>Nama Lengkap: <?php echo $row->first_name?></b></h6>
                </div>
                <div class="col-6 col-sm-12">
                    <h6><b>Email : <?php echo $row->email?></b></h6>
                </div>
                 <div class="col-6 col-sm-8">
                    <h7>Nama Lengkap : </h7><h6><?php echo $row->first_name?></h6>
                </div>
                <div class="col-8 col-sm-12">
                    <h6>Perimeter : </h6>
                    <?php
                    $data = $this->profile_model->get_perimeterbynik($row->username);
                        echo '<table>';
                        echo '<tr>';
                            $numrows = 6;
                            $col = 2;
                            for($i = 0; $i < $data->num_rows(); $i++) {
                            $rowx = $data->result_array();
                            $name = $rowx[$i]['v_mpm'];
                                if($i % $col == 0){
                                    echo '</tr><tr>';
                                }
                                echo '<td>'.$name.'</td>';
                                //echo '<td><a href="'.$link.'">'.$name.'</a></td>';
                            }
                        echo '</tr>';
                        echo '</table>';
                        ?>
                </div>
          </div>
        </div>
</div>
</div>
<script type="text/javascript">
function cancel() {
$('#form_modal')[0].reset();
//datatables();
}

function save() {
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;
     block();
    if(save_method == 'add') {
        url = "<?php echo site_url('profile/ajax_add')?>";
    } else {
        url = "<?php echo site_url('profile/ajax_update_nik')?>";
    }

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
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false); 
                //$('#form_modal')[0].reset();
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
    $('#form-biaya-membayar').hide();
}

function edit_nik(id) {
    block();
    save_method = 'update';
    $('#form_modal')[0].reset();
    $.ajax({
        url : "<?php echo site_url('profile/ajax_edit')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="nik"]').val(data.username);            
            $('[name="nik_asli"]').val(data.username);            
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit User PIC/FO');
            unblock();
            //refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax'+jqXHR.responseText);
        }
    });
}


$(document).ready(function() {
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
        }).on('changeDate', function(e){
        $(this).datepicker('hide');
    });
});

function delete_user(id) {
	var name= '<?php echo $row->first_name?>';
	if(confirm('Yakin melakukan penghapusan User '+name+'?')) {
        $.ajax({
            url : "<?php echo site_url('profile/ajax_deleteuser')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
				alert(data.message);
				window.location.href = '<?php echo base_url('profile/picfo'); ?>';
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
                <h5 class="modal-title">Form Input Data</h5>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_modal" class="form-horizontal" onsubmit="save();return false;">
                    <input type="hidden" value="0" id="modal_id" name="modal_id"/>
                    <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
                    <input type="hidden" value="0" name="id">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">NIK<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="nik" name="nik" placeholder="NIK User" class="form-control" type="text" required>
                                <input id="nik_asli" name="nik_asli" placeholder="NIK User" class="form-control" type="hidden" required>
                                <span class="help-block"></span>
                            </div>
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