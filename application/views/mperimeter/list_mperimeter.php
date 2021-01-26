<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COSMIC - Monitoring Perimeter</title>
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <style>
    .datepicker{
        z-index:9999 !important
    }
    .ui-autocomplete {
        position:absolute;
        cursor:default;
    }
    </style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/jquery-ui-1.12.1.custom/jquery-ui.css"/>


    </head>
<body>
    <div class="row">
    <?php $this->load->view('company_select');?>
    <div class="col-lg-2 pull-right form-group">
        <button class='btn btn-success' type='button'
          onclick="tambah_perimeter()" ><span class='white' ><i class='ace-icon fa fa-plus-circle '>
        </i> Tambah Perimeter </span></button>
    </div>
   </div>
    <div class="row">
    	<div class="col-sm-12">
        <div class="table-responsive">
    	   <table id="table" class="table table-hover" cellspacing="0">
            <thead>
                <tr>
                	<th>No. </th>
                    <th>Region</th>
                    <th>Perimeter</th>
                    <th>Jumlah Lantai</th>
                    <th>Provinsi</th>
          					<th>Kabupaten</th>
                    <th>Detail</th>
          					<th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>
		</div>
	</div>
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var company =  $('#company').val();
    console.log("<?php echo base_url().'mperimeter/get_region_autocomplete'?>/"+company+"?");
    $( "#region" ).autocomplete({
      source: "<?php echo base_url().'mperimeter/get_region_autocomplete'?>/"+company+"?"
    });
    $("#provinsi").change(function(){
      $("#kabupaten").hide();
      get_kota($("#provinsi").val(), $("#kabupaten").val());
    });
    $("#kd_perusahaan").val(company);
  });

    </script>
<script type="text/javascript">
$(document).ready(function() {

	$('input[type=file]').val('');
	var company =  $('#company').val();
	$('#kd_perusahaan').val(company);

	var company =  $('#company').val();


    table = $('#table').DataTable({
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": true,
        "dataSrc": "",
        "ajax": {
            "url": "<?php echo base_url().'mperimeter/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
       	 {
        	"orderable": false,
            "targets": 0,
            "className": "text-right",
       	 },
       	 {
        	"orderable": true,
            "targets": 1,
            "className": "text-left",
       	 },
       	 {
       		"orderable": true,
            "targets": 2,
            "className": "text-left",
      	 },
       	 {
      		"orderable": true,
            "targets": 3,
            "className": "text-left",
      	 },
       	 {
      		"orderable": true,
            "targets": 4,
            "className": "text-left",
   	 	 },
       	 {
            "orderable": true,
             "targets": 5,
             "className": "text-center",
		 },
       ],
    });

});

function refresh_list() {
	var company =  $('#company').val();
	$('#kd_perusahaan').val(company);
	var table = $('#table').DataTable({
	    "destroy": true,
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": true,
        "dataSrc": "",
        "ajax": {
            "url": "<?php echo base_url().'mperimeter/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
          	 {
           		"orderable": false,
                "targets": 0,
                "className": "text-right",
          	 },
          	 {
           		"orderable": true,
                "targets": 1,
                "className": "text-left",
          	 },
          	 {
                "orderable": true,
                "targets": 2,
                "className": "text-left",
         	 },
          	 {
                "orderable": true,
                "targets": 3,
                "className": "text-left",
         	 },
          	 {
                "orderable": true,
                "targets": 4,
                "className": "text-left",
      	 	 },
           	 {
                "orderable": true,
                "targets": 5,
                "className": "text-center",
     		 },
          ],
            });
	table.ajax.reload();
	// table.ajax.draw();
}

function detail_perimeter(id) {
    $.ajax({
        url : "<?php echo site_url('mperimeter/ajax_delete')?>/"+id,
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

function delete_perimeter(id) {
    if(confirm('Are you sure delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('mperimeter/delete_perimeter')?>/"+id,
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

function tambah_perimeter() {
    save_method = 'add';
    $('#form_modal1')[0].reset();
    $('#modal_perimeter').modal('show');
    $('.modal-title').text('Tambah Perimeter');
    refresh_list();

}

function get_kota(provinsi_id, kabupaten_id){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>kasus/kabupaten",
        data: {id_provinsi : provinsi_id},
        dataType: "json",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#kabupaten").html(response.list_kabupaten).show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function save() {
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;
     block();
    if(save_method == 'add') {
        url = "<?php echo site_url('mperimeter/ajax_add')?>";
    } else {
        url = "<?php echo site_url('mperimeter/ajax_update')?>";
    }

    var formData = new FormData($('#form_modal1')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            if(data.status) {
              console.log(data);
                alert(data.message);
                $('#modal_perimeter').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal1')[0].reset();
                refresh_list();
                //console.log(data.id_perimeter);

                if(data.id_perimeter) {
                      window.open('<?php echo site_url('mperimeter/form_perimeter/')?>/'+data.id_perimeter, '_blank');
                }
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal1')[0].reset();
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
<div class="modal fade " id="modal_perimeter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Edit Perimeter</h5>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_modal1" class="form-horizontal">
                    <input type="hidden" value="0" name="id_perimeter_level"/>
                    <input type="hidden" value="" id="id_perimeter_level_modal" name="id_perimeter_level_modal"/>
                    <input type="hidden" value="" id="kd_perusahaan" name="kd_perusahaan" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Region</label>
                            <div class="col-sm-12">
                                <input id="region" name="region" placeholder="Region" class="form-control" type="text" >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Perimeter</label>
                            <div class="col-sm-12">
                                <input id="perimeter" name="perimeter" placeholder="Perimeter" class="form-control" type="text" >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Kategori Perimeter<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="kat_perimeter" id="kat_perimeter" >
                                  <?php foreach ($kategori_perimeter as $kat){ ?>
                                      <option value="<?php echo $kat->mpmk_id; ?>"><?php echo $kat->mpmk_name; ?></option>
                                  <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4">Provinsi<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select name="provinsi" id="provinsi" class="form-control">
                                <?php foreach ($provinsi as $pro){ ?>
                                    <option value="<?php echo $pro->mpro_id; ?>"><?php echo $pro->mpro_name; ?></option>
                                <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Kota/Kabupaten<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="kabupaten" id="kabupaten" >
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-sm-4">Alamat<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <textarea id="alamat" name="alamat" placeholder="Keterangan" class="form-control" rows="4" style="height:100%;" required></textarea>

                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Longitude</label>
                            <div class="col-sm-12">
                                <input id="longitude" name="longitude" placeholder="Longitude" class="form-control" type="text" >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Latitude</label>
                            <div class="col-sm-12">
                                <input id="latitude" name="latitude" placeholder="Perimeter" class="form-control" type="text" >
                                <span class="help-block"></span>
                            </div>
                        </div>




                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-primary" id="btnSave" onclick="save()" >Save</button>
                <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
