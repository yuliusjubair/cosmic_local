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
   /*.datepicker{z-index:9999 !important}*/
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
</style>

<body>
    <div class="row">
        <div class="col-sm-12 pull-left">
            <div class="form-group">
                    <button class="btn btn-success" onclick="add_pengumuman()">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data</button>
            </div>
        </div>
    </div>
    <div class="row">
        
    </div>
    <div class="row">
        <div class="table-responsive">
            <table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Berakhir</th>
                        <th>Status</th>
                        <th>File Upload</th>
                        <th>Aksi</th>
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
    table = $('#table').DataTable({ 
        "bDestroy": true,
        "responsive": true,
        "processing": true, 
        "serverSide": false, 
        "ordering": true,
        "dataSrc": "",
        "ajax": {
            "url": "<?php echo base_url().'pengumuman/ajax_list'?>/",
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-right", },
            { "targets": 1, "orderable": false, "className": "text-left", },
            { "targets": 2, "orderable": false, "className": "text-center", },
            { "targets": 3, "orderable": false, "className": "text-left", },
            { "targets": 4, "orderable": false, "className": "text-left", },
            { "targets": 5, "visible": true, "className": "text-left hidden", },
            { "targets": 6, "orderable": false, "className": "text-center", },
        ]
    });
     
     /*$('#table tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        block();
        window.location.href="<?php echo site_url('pengumuman/detail_pengumuman')?>/"+data[5];
    });*/
    //table.ajax.reload();
    //table.ajax.draw();
}

$(document).ready(function() {
   $('input[type=file]').change(function () {
        var val = $(this).val().toLowerCase(),
            regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
        if (!(regex.test(val))) {
            $(this).val('');
            alert('File yang diperbolehkan jpg, jpeg atau png');
        }
/*
        if(this.files[0].size/1024/1024 > 10){
            $(this).val('');
            alert('File size max 10Mb');
        }*/
    });

   var date = new Date();
   var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
        startDate: '-0m',
        minDate: 0,
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

function add_pengumuman() {
    save_method = 'add';
    $('#form-biaya-membayar').hide();
    $('#form_modal')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Pengumuman');
}


function save() {
    var nama = $('#nama').val();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true);
    var url;
     block();
    if(save_method == 'add') {
        url = "<?php echo site_url('pengumuman/ajax_add')?>";
    } else {
        url = "<?php echo site_url('pengumuman/ajax_update')?>";
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
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false); 
                $('#form_modal')[0].reset();
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

function edit_pengumuman(id) {
    block();
    save_method = 'update';
    $('#form_modal')[0].reset();
    $.ajax({
        url : "<?php echo site_url('pengumuman/ajax_edit')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="modal_id"]').val(data.data.id);
            $('[name="id"]').val(data.data.id);
            $('[name="judul"]').val(data.data.judul);
            $('[name="deskripsi"]').val(data.data.deskripsi);
            $('[name="modal_tgl"]').val(data.data.end_date);
            $('[name="status"]').val(data.data.status);
            
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Pengumuman');
            unblock();
            //refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax'+jqXHR.responseText);
        }
    });
}

function delete_pengumuman(id) {
    if(confirm('Are you sure Delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('pengumuman/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
                window.location.href='<?php echo base_url()."pengumuman"; ?>';
                //refresh_list();
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
                <form action="#" id="form_modal" class="form-horizontal">
                    <input type="hidden" value="0" id="modal_id" name="modal_id"/>
                    <input type="hidden" value="" id="kd_perusahaan_modal" name="kd_perusahaan_modal"/>
                    <input type="hidden" value="0" name="id">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">Judul<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="judul" name="judul" placeholder="Judul" class="form-control" type="text" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6">Deskripsi<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <textarea style="margin-top: 0px; margin-bottom: 0px; height: 116px;" id="deskripsi" name="deskripsi" placeholder="Deskripsi" class="form-control" type="text" required></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-6">Tanggal Berakhir Pengumuman<span style="color:red">*</span><span id="tgl_kasus"></span></label>
                            <div class="col-sm-12">
                                <input id="modal_tgl" name="modal_tgl" placeholder="yyyy-mm-dd" class="form-control" type="date" min="<?php echo date('Y-m-d')?>" onkeydown="return false">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-6">Status<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select name="status" id="status" class="form-control">
                                    <option value="Aktif">Aktif</option>
                                    <option value="NonAktif">NonAktif</option>
                                </select>
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
                                <input id="modal_foto_pengumuman" name="modal_foto_pengumuman" type="file" accept="image/png, image/jpeg, image/jpg">
                                <font size="1" color="#800000">* Filtype yang diperbolehkan jpg, png dan max 2Mb</font>
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
</body>
</html>
