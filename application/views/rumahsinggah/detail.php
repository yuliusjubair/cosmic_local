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
                onclick="edit_rumahsinggah('."'".$row->id."'".')">
                <i class="ace-icon fa fa-pencil bigger-120"></i>Edit</a>';
              }
              
              if($delete==1){
                echo '&nbsp;<a class="btn btn-lg btn-danger" href="javascript:void(0)" title="Delete"
                onclick="delete_rumahsinggah('."'".$row->id."'".')">
                <i class="ace-icon fa fa-trash bigger-120"></i>Delete</a>';
              }
              ?><br />
                </div>
              <div class="gallery gallery-md gutters-sm">'
                <div class="col-8 col-md-8 col-lg-12">
                    <?php $image1 = base_url('/upload/rumahsinggah/'.$row->mc_id.'/'.date('Y-m-d', strtotime($row->date_insert)).'/'.$row->file);
                    //$image1 = base_url().'assets/login/left_bg_font.png';
                    ?> 
                    <div class="gallery-item" data-image="<?php echo $image1?>" data-title="Image 1" href="<?php echo $image1?>" title="Image 1" 
                        style="background-image: url('<?php echo $image1?>');"></div>
                    
             
                </div>
               <div class="gallery-item gallery-hide" data-image="<?php echo $image1?>" data-title="Image 9" href="<?php echo $image1?>" title="Image 9" style="background-image: url('<?php echo $image1?>')"></div>
            </div>

             <div class="col-6 col-sm-4">
                    <h3><?php echo $row->nama_rumahsinggah?></h3>
                </div>
                <div class="col-6 col-sm-12">
                    <h6><b>alamat : <?php echo $row->alamat?></b></h6>
                </div>
                 <div class="col-6 col-sm-8">
                    <h7>Kapasitas : </h7><h6><?php echo $row->kapasitas?> Orang</h6>
                </div>
                 <div class="col-6 col-sm-8">
                    <h7>Ruangan Tersedia : </h7> <h6><?php echo $row->ruangan_available?> Ruangan</h6>
                </div>
                 <div class="col-6 col-sm-8">
                    <h7>Nama PIC : </h7> <h6><?php echo $row->pic_nik?></h6>
                </div>
                <div class="col-6 col-sm-8">
                    <h7>No Kontak PIC : </h7> <h6><?php echo $row->pic_kontak?></h6>
                </div>
                 <div class="col-8 col-sm-6">
                    <h7>
                    Kriteria Pasien yang diterima : </h7><br />
                    <h6>
                    <?php 
                    if(!empty($row->kriteria_id)){
                        $sql = $this->db->query("SELECT * FROM master_kriteria_orang
                          WHERE id in ($row->kriteria_id)
                          ORDER BY id ASC");
                          if($sql->num_rows()>0){
                            $no=1;
                            foreach ($sql->result() as $key => $value) {
                                echo $no.". ".$value->jenis."<br />";
                            $no++;
                            }
                          } 
                    }

                    ?>
                    </h6>
                </div>
                <div class="col-8 col-sm-6">
                    <h7>
                    Jenis Kasus : </h7><br />
                    <h6>
                    <?php 
                        if(!empty($row->jenis_kasus)){
                        $sql = $this->db->query("SELECT * FROM master_status_kasus
                          WHERE msk_id in ($row->jenis_kasus)
                          ORDER BY msk_id ASC");
                          if($sql->num_rows()>0){
                            $no=1;
                            foreach ($sql->result() as $key => $value) {
                                echo $no.". ".$value->msk_name2."<br />";
                            $no++;
                            }
                          } 
                      }
                    ?>
                    </h6>
                </div>
                 <div class="col-8 col-sm-10">
                    <h7>
                    Fasilitas : </h7><br />
                    <h6>
                    <?php 
                        if(!empty($row->fas_rumah_id)){
                        $sql = $this->db->query("SELECT * FROM master_fasilitas_rumah
                          WHERE id in ($row->fas_rumah_id)
                          ORDER BY id ASC");
                          if($sql->num_rows()>0){
                            $no=1;
                            foreach ($sql->result() as $key => $value) {
                                echo $no.". ".$value->jenis."<br />";
                            $no++;
                            }
                          } 
                         } 
                    ?>
                    </h6>
                </div>
                <!-- <div class="col-8 col-sm-10">
                    <?php
                        echo '<table>';
                        echo '<tr>';
                            $numrows = 6;
                            $col = 2;
                            for($i = 0; $i < $numrows; $i++) {
                            $name = "name";
                            $avatar = "avatar";
                            $link = "localhost";
                                if($i % $col == 0){
                                    echo '</tr><tr>';
                                }
                                echo '<td>'.$avatar.'</td>';
                                echo '<td><a href="'.$link.'">'.$name.'</a></td>';
                            }
                        echo '</tr>';
                        echo '</table>';
                        ?>
                </div> -->
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
                window.location.reload();
                // refresh_list();
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

let editChecked = '';
let splitStatusKasus = '';
let edit_faschecked = '';
let splitFas = '';
let edit_krichecked = '';
let splitKri = '';
function edit_rumahsinggah(id) {
    block();
    save_method = 'update';
    $('#form_modal')[0].reset();
    $.ajax({
        url : "<?php echo site_url('rumahsinggah/ajax_edit')?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="modal_id"]').val(data.data.ts_id);
            $('[name="id"]').val(data.data.id);
            $('[name="kd_perusahaan_modal"]').val(data.data.mc_id);
            $('[name="nama"]').val(data.data.nama_rumahsinggah);
            $('[name="alamat"]').val(data.data.alamat);
            $('[name="provinsi"]').val(data.data.prov_id);
            get_kota(data.data.prov_id, null);
            $('[name="kabupaten"]').val(data.data.kota_id);
            $('[name="keterangan"]').val(data.data.keterangan);
            $('[name="pic"]').val(data.data.pic_nik);
            $('[name="no_pic"]').val(data.data.pic_kontak);

            $('[name="kapasitas"]').val(data.data.kapasitas);
            $('[name="ruangan"]').val(data.data.ruangan_available);
            $('[name="biaya_membayar"]').val(data.data.membayar);
            //$('[name="fasilitas_rumah"]').val(data.data.fas_rumah_id);
            if(data.data.biaya_ygdiperlukan == "Membayar"){
                $('#membayar').prop('checked',true);
                $('#form-biaya-membayar').show();
            } else{
                $('#tidak-dipungut-biaya').prop('checked',true);
                $('#form-biaya-membayar').hide();
            }
            editChecked = '';
            data.status_kasus.forEach(function(row){
                editChecked += ` 
                    <div class="form-check">
                        <input class="form-check-input" id="check-${row.msk_id}" type="checkbox" value="${row.msk_id}" name="kasus[]">&nbsp;&nbsp;

                        <label class="form-check-label" for="defaultCheck1">
                            &nbsp;${row.msk_name2}
                        </label>
                    </div>
                `;
            });
            $('#edit_checked').html('');
            $('#edit_checked').html(editChecked);
            if(data.data.jenis_kasus != null && data.data.Jenis_kasus != ''){
                replaceStatusKasus = data.data.jenis_kasus.split("'").join("");
                splitStatusKasus = replaceStatusKasus.split(',');
                splitStatusKasus.forEach(function(row){
                    $(`#check-${row}`).prop("checked", true);
                });
            }

            //fasilitas rumah
            editFasChecked = '';
            data.fasilitas_rumah.forEach(function(row){
                editFasChecked += ` 
                    <div class="form-check">
                        <input class="form-check-input" id="check2-${row.id}" type="checkbox" value="${row.id}" name="fasilitas_rumah[]">&nbsp;&nbsp;

                        <label class="form-check-label" for="defaultCheck1">
                            &nbsp;${row.jenis}
                        </label>
                    </div>
                `;
            });
            $('#edit_faschecked').html('');
            $('#edit_faschecked').html(editFasChecked);
            if(data.data.fas_rumah_id != null && data.data.fas_rumah_id != ''){
                replaceStatusKasus = data.data.fas_rumah_id.split("'").join("");
                splitFas = replaceStatusKasus.split(',');
                splitFas.forEach(function(row){
                    $(`#check2-${row}`).prop("checked", true);
                });
            }

            //Kriteria Orang
            editKriChecked = '';
            data.kriteria.forEach(function(row){
                editKriChecked += ` 
                    <div class="form-check">
                        <input class="form-check-input" id="check3-${row.id}" type="checkbox" value="${row.id}" name="kriteria[]">&nbsp;&nbsp;

                        <label class="form-check-label" for="defaultCheck1">
                            &nbsp;${row.jenis}
                        </label>
                    </div>
                `;
            });
            $('#edit_krichecked').html('');
            $('#edit_krichecked').html(editKriChecked);
            if(data.data.kriteria_id != null && data.data.kriteria_id != ''){
                replaceStatus = data.data.kriteria_id.split("'").join("");
                splitKri = replaceStatus.split(',');
                splitKri.forEach(function(row){
                    $(`#check3-${row}`).prop("checked", true);
                });
            }
        
            
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit rumahsinggah');
            unblock();
            //refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax'+jqXHR.responseText);
        }
    });
}


$(document).ready(function() {
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

function delete_rumahsinggah(id) {
    if(confirm('Are you sure Delete this data?')) {
        $.ajax({
            url : "<?php echo site_url('rumahsinggah/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                alert(data.message);
                window.location.href='<?php echo base_url()."rumahsinggah"; ?>';
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
                            <label class="control-label col-sm-6">Perimeter in Charge<span style="color:red">*</span></label>
                            <div class="col-sm-12">
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
