<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COSMIC - List Kasus Covid</title>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
   
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
   <style>
   .datepicker{z-index:9999 !important}
   </style>
    </head> 
   
<style>
    .dataTables_scrollHeadInner { display: none; }
    table#table.dataTable tbody tr:hover {
      background-color: #ffa;
      cursor: pointer;
    }
     
    table#table.dataTable tbody tr:hover > .sorting_1 {
      background-color: #ffa;
      cursor: pointer;
    }

    .modal-footer2 {
        padding: 15px;
        text-align: center;
        border-top: 1px solid #e5e5e5;
    }
</style>

<body>
    <div class="row">
        <input type="hidden" name="company" id="company" value="<?php echo $company_id?>">
        <div class="col-sm-6">
            <div class="form-group">
				<select id="company" name="company" data-live-search="true" onchange="refresh_list()"
            	class="form-control selectpicker " data-style="btn-white btn-default" >
                <?php
            	foreach ($company->result() as $row) {
            	    if($p_mc_id!=NULL && $p_mc_id==$row->mc_id){
            	        $mc_selected='selected="selected"';
            	    }else{
            	        $mc_selected='';
            	    }
            	?>
                <option value="<?php echo $row->mc_id;?>" <?php echo $mc_selected;?>>
        			<?php echo $row->mc_name;?>
        	    </option>
        		<?php } ?>
            </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                    <button class="btn btn-success" onclick="add_rumahsinggah()">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Rumah Singgah</button>
            </div>
        </div>
    </div>
    <div class="row">
        
    </div>
    <div class="row col-sm-12">
        <div class="table-responsive">
            <table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Nama Rumah Singgah</th>
                        <th>Kota</th>
                        <th>Provinsi</th>
                        <th>Bayar</th>
                        <th>Aksi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
<script type="text/javascript">
function refresh_list() {
    datatables();
}


function cancel() {
    $('#form_modal')[0].reset();
    datatables();
}

function datatables(){
    var company =  $('#company').val();
    $('#kd_perusahaan').val(company);
    $('#kd_perusahaan_modal').val(company);
    table = $('#table').DataTable({ 
        "bDestroy": true,
        "responsive": true,
        "processing": true, 
        "serverSide": false, 
        "ordering": true,
        "dataSrc": "",
        "ajax": {
            "url": "<?php echo base_url().'rumahsinggah/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-center", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "orderable": true, "className": "text-left", },
            { "targets": 3, "orderable": true, "className": "text-left", },
            { "targets": 4, "orderable": true, "className": "text-left", },
            { "targets": 5, "visible": false, "className": "text-left hidden", },
            { "targets": 6, "orderable": false, "className": "text-right", },
        ]
    });
     
     $('#table tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        //alert( 'You clicked on '+data[0]+'\'s row' );
        window.location.href="<?php echo site_url('rumahsinggah/detail_rumahsinggah')?>/"+data[5];
    });
    //table.ajax.reload();
    //table.ajax.draw();
}

$(document).ready(function() {
	var group = <?php echo $group;?>;
	if(group ==1){
        $.ajax({
            url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=');?>"+1,
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
	}
    $('#form-biaya-membayar').hide();
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
    datatables();

    $("#provinsi").change(function(){ 
        $("#kabupaten").hide();
        get_kota($("#provinsi").val(), $("#kabupaten").val());
    });
    $('#biaya').on('change', function() {
        let biaya = $("input[name='biaya']:checked").val();
        if(biaya == 'Membayar'){
            $('#form-biaya-membayar').show();
        } else {
            $('#form-biaya-membayar').hide();
        }
    });

});

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


function add_rumahsinggah() {
    save_method = 'add';
    $('#form-biaya-membayar').hide();
    $('#form_modal')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Rumah Singgah');
}


function save() {
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;
     block();
    if(save_method == 'add') {
        url = "<?php echo site_url('rumahsinggah/ajax_add')?>";
    } else {
        url = "<?php echo site_url('rumahsinggah/ajax_update')?>";
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
                refresh_list();
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                $('#btnSave').text('Tambah Rumah Singgah');
                //$('#btnSave').attr('disabled',false); 
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
                <form action="#" id="form_modal" class="form-horizontal">
                    <input type="hidden" value="0" id="modal_id" name="modal_id"/>
                    <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
                    <input type="hidden" value="0" name="id">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">Nama Rumah Singgah<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="nama" name="nama" placeholder="Nama Rumah Singgah" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6">Alamat<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="alamat" name="alamat" placeholder="Alamat" class="form-control" type="text" required>
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
                            <label class="control-label col-sm-4">Kota<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="kabupaten" id="kabupaten" >
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6">Kapasitas Ruangan<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="kapasitas" name="kapasitas" placeholder="Jumlah Kapasitas Ruangan" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6">Ruangan Tersedia<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="ruangan" name="ruangan" placeholder="Ruangan Tersedia" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                         
                        <div class="form-group">
                            <label class="control-label col-sm-6">Fasilitas Rumah<span style="color:red">*</span></label>
                           
                            <div class="col-sm-12 edit_faschecked" id="edit_faschecked">
                                    <?php foreach ($fasilitas_rumah as $row){ ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?php echo $row->id; ?>" name="fasilitas_rumah[]">&nbsp;&nbsp;
                                        <label class="form-check-label" for="defaultCheck1">
                                          &nbsp;<?php echo $row->jenis; ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-7">Biaya yang diperlukan / malam<span style="color:red">*</span></label>
                            <div class="col-sm-12 biaya" id="biaya">
                                <div class="form-radio">
                                    <input class="form-radio-input" id="tidak-dipungut-biaya" type="radio" value="Tidak dipungut biaya" name="biaya" checked>&nbsp;
                                    <label class="form-radio-label" for="defaultRadio1">
                                        &nbsp;Tidak dipungut biaya
                                    </label>
                                </div>
                                <div class="form-radio">
                                    <input class="form-radio-input" id="membayar" type="radio" value="Membayar" name="biaya">&nbsp;
                                    <label class="form-radio-label" for="defaultRadio1">
                                        &nbsp;Membayar
                                    </label>
                                </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="form-biaya-membayar">
                            <label class="control-label col-sm-6">Biaya<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="biaya-membayar" name="biaya_membayar" placeholder="Rp .." class="form-control" type="number" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Jenis Kasus<span style="color:red">*</span></label>
                            <div class="col-sm-12 edit_checked" id="edit_checked">
                                    <?php foreach ($status_kasus->result() as $row){ ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?php echo $row->msk_id; ?>" name="kasus[]">&nbsp;&nbsp;
                                        <label class="form-check-label" for="defaultCheck1">
                                          &nbsp;<?php echo $row->msk_name2; ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-8">Kriteria Pasien yang diterima<span style="color:red">*</span></label>
                            <div class="col-sm-12 edit_krichecked" id="edit_krichecked">
                                    <?php foreach ($kriteria->result() as $row){ ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?php echo $row->id; ?>" name="kriteria[]">&nbsp;&nbsp;
                                        <label class="form-check-label" for="defaultCheck1">
                                          &nbsp;<?php echo $row->jenis; ?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <!--  <div class="form-group">
                            <label class="control-label col-sm-4">Kriteria oang yang dapat ditampung<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select name="kriteria_orang" id="kriteria_orang" class="form-control" >
                                    <?php foreach ($kriteria->result() as $itempic){ ?>
                                        <option value="<?php echo $itempic->jenis; ?>"><?php echo $itempic->id; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="control-label col-sm-6">Keterangan Lain-lain<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="keterangan" name="keterangan" placeholder="Keterangan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="photo-preview1" style="display:none;">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-12">
                                (No photo)
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo1">Upload Photo<span style="color:red">*</span></label>
                            <div class="col-md-12">
                                <input id="modal_foto_rumahsinggah" name="modal_foto_rumahsinggah" type="file">
                                <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <!-- <div class="form-group" id="photo-preview1" style="display:none;">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-12">
                                (No photo)
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo1">Upload Photo<span style="color:red">*</span></label>
                            <div class="col-md-12">
                                <input id="modal_foto_rumahsinggah2" name="modal_foto_rumahsinggah2" type="file">
                                <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6">Perimeter in Charge<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <!-- <select name="pic" id="pic" class="form-control" >
                                    <?php foreach ($pic as $itempic){ ?>
                                        <option value="<?php echo $itempic->username; ?>"><?php echo $itempic->first_name; ?></option>
                                    <?php } ?>
                                </select> -->
                                <input id="pic" name="pic" placeholder="Nama PIC" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6">No Kontak PIC<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="no_pic" name="no_pic" placeholder="No Kontak PIC" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer2">
                <center>
                    <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="save()" >Tambah Rumah Singgah</button>
                </center>
                <!-- <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button> -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>
