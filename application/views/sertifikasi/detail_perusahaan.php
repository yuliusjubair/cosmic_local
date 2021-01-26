<?php $row = $rowx;?>
<div class="row">
<div class="col-12">
<div class="card">
      <div class="card-body"><!--  style="margin: 10px auto;" -->
         <div class="col-sm-10">
                <h3><?php echo $row->mc_name?></h3>
                <h7><a href="#" class="stretched-link"><?php echo $row->mc_website?></a></h7>
            </div>
            <div class="row container">
                <div class="col-12 col-md-4 col-lg-4">
                  <h6><b>Cosmic Index Minggu Ini
                    <br /><span style="color: #66ff99"><?php echo $cosmic_index_thisweek?> %</span></b></h6>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <h6>
                    Cosmic Index Minggu Lalu
                <br /><span style="color: red"><?php echo $cosmic_index_weekbefore?> %</span></h6>
                </div>
            </div>
            <div class="col-6 col-sm-6">
                <br />
            </div>
             <div class="col-6 col-sm-6">
                
            </div>
            <div class="row container">
                <div class="col-12 col-md-4 col-lg-4">
                  <h7><b>Kluster Industri</h7>
                    <h6><?php echo $row->jenis?></b></h6>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <h7>
                    Status
                  </h7>
                    <?php if($row->mc_flag==2):?>
                        <h6>Non BUMN </h6>
                    <?php else:?>
                        <h6>BUMN</h6>
                    <?php endif;?>    
                </div>
            </div>
             <div class="col-6 col-sm-6">
                <br />
            </div>
            <div class="row container">
                <div class="col-12 col-md-4 col-lg-4">
                  <h7>Nama Penanggung Jawab : </h7> <h6><?php echo $row->tbps_nama_pj?></h6>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <h7>Kontak Penanggung Jawab : </h7> <h6><?php echo $row->tbps_no_tlp_pj?></h6>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <h7>Email Penanggung Jawab : </h7> <h6><?php echo $row->tbps_email_pj?></h6>
                </div>
            </div>
             <div class="col-6 col-sm-6">
                <br />
            </div>
            <div class="row container">
            <div class="col-12 col-md-4 col-lg-4">
                <h7>Status Sertifikasi : </h7> 
                <h6>
                <?php 
                    if($row->tbps_status=="0"){
                        echo "Belum Disetujui";
                        $cek=0;
                    }elseif($row->tbps_status=="1"){
                        echo "Dalam Proses";
                        $cek=1;
                    }else{
                        echo "Batal";
                        $cek=0;
                    }
                ?></h6>
            </div>
            <?php if($cek==1)://Muncul Jika Proses Sudah disetujui?>
            <div class="col-12 col-md-6 col-lg-6">
                <h7>Status Proses Sertifikasi : </h7> 
                <h6>
                <?php 
                    echo $row->status;
                ?></h6>
            </div>
        <?php endif;?>
        </div>
            <div class="col-12 col-sm-4">
                <?php if($row->tbps_status=="0" || $row->tbps_status=="4"):?>
                    <button class="btn btn-primary" type="button" name="setujui" onclick="dialog_detail('<?php echo $row->mc_id?>','<?php echo $tbps_id?>')">Setujui Permohonan</button>
                <?php else:?>
                    <button class="btn btn-success" type="button" name="ubah" onclick="dialog_detail_ubah('<?php echo $row->mc_id?>','<?php echo $tbps_id?>')">Ubah Status</button>
                    <button class="btn btn-danger" type="button" name="batal" onclick="dialog_confirm(<?php echo $tbps_id?>)">Batal</button>
                <?php endif;?>
            </div>     

        </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-12">
<div class="card">
      <div class="card-body"><!--  style="margin: 10px auto;" -->
          <div class="table-responsive">
                    <table id="table" class="table table-hover" role="grid" aria-describedby="table-1_info">
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
</div>
</div>
<div class="row">
<div class="col-12">
<div class="card">
          <!-- <div class="card-header">
            <h4>Check</h4>
          </div> -->
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="table-responsive">
                   <table id="tabel_perimeter" class="display table table-hover" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID</th>
                                <th>Nama Perimeter</th>
                                <th>Alamat</th>
                                <th>Jumlah Level</th>
                                <th>Presentase Monitoring</th>
                                <!-- <th>Status Proses Atestasi</th>
                                -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
          </div>
        </div>
</div>
</div>
</div>
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
                    <input type="hidden" id="modal_mc_id"  name="modal_mc_id"/>
                     
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
<!--                            <label class="control-label col-md-3" id="label-page">Halaman ke:</label> -->
<!--                             <div class="col-md-6"> -->
<!--                                <input name="modal_page" id="modal_page" type="text"> -->
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

<!-- Bootstrap modal -->
<div class="modal fade modal_form_detail_ubah" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Detail Perusahaan</h3>
            </div>
            <div class="modal-body">
                <form action="#" id="form_detail_ubah" class="form-horizontal">
                    <!-- <input type="hidden" value="0" id="modal_id"  name="modal_id"/> --> 
                    <input type="hidden" value="" id="modal_mc_id_stat" name="modal_mc_id_stat"/>
                    <input type="hidden" value="" id="modal_tbps_id" name="modal_tbps_id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Nama Perusahaan</label>
                            <div class="col-md-12">
                                <input class="form-control" name="nama_perusahaan" id="nama_perusahaan" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                         </div>
                          <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Kluster Industri</label>
                            <div class="col-md-12">
                                <input class="form-control" name="cluster" id="cluster" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                         </div>
                          <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Alamat</label>
                            <div class="col-md-12">
                                <input class="form-control" name="cluster" id="cluster" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                         </div>
                          <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Petugas Penanggung Jawab</label>
                            <div class="col-md-12">
                                <input class="form-control" name="petugas" id="petugas" type="text">
                                <span class="help-block"></span>
                            </div>
                         </div>
                         <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Kontak Penanggung Jawab</label>
                            <div class="col-md-12">
                                <input class="form-control" name="kontak_petugas" id="kontak_petugas" type="text">
                                <span class="help-block"></span>
                            </div>
                         </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">STATUS PROSES SERTIFIKASI</label>
                            <div class="col-md-12">
                                <select class="custom-select" id="inputGroupSelect04" name="status">
                                    <option value="">-Choose-</option>
                                    <?php foreach($master_status as $status):
                                        if($status->id == $row->mc_status_sertifikasi){
                                            $sel = "selected";
                                        }else{
                                            $sel = "";
                                        }
                                        ?>
                                        <option <?php echo $sel?> value="<?php echo $status->id?>"><?php echo $status->status?></option>
                                    <?php endforeach;?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                         </div>
                         
                        <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Estimasi Pengerjaan</label>
                            <div class="col-md-12">
                                <input class="form-control" name="estimasi" id="estimasi" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                         </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="update_status_detail()" class="btn btn-md btn-primary">Save</button>
                <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<div class="modal fade modal_form_detail" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Detail Perusahaan</h3>
            </div>
            <div class="modal-body">
                <form action="#" id="form_detail" class="form-horizontal">
                    <!-- <input type="hidden" value="0" id="modal_id"  name="modal_id"/> --> 
                    <input type="hidden" value="" id="modal_mc_id_stat" name="modal_mc_id_stat"/>
                    <input type="hidden" value="" id="modal_tbps_id2" name="modal_tbps_id2"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Nama Perusahaan</label>
                            <div class="col-md-12">
                                <input class="form-control" name="nama_perusahaan" id="nama_perusahaan" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                         </div>
                          <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Kluster Industri</label>
                            <div class="col-md-12">
                                <input class="form-control" name="cluster" id="cluster" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                         </div>
                         
                        <!--  <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Alamat</label>
                            <div class="col-md-12">
                                <input class="form-control" name="alamat" id="alamat" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                         </div> -->
                         <div class="form-group">
                
                            <div class="col-sm-12">
                               <label class="control-label col-sm-12"><input type="checkbox" class="check1" name="check1" value="1"> Setujui Permohonan<span style="color:red">*</span></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Petugas Penanggung Jawab</label>
                            <div class="col-md-12">
                                <input class="form-control" name="petugas" id="petugas" type="text">
                                <span class="help-block"></span>
                            </div>
                         </div>
                         <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Kontak Penanggung Jawab</label>
                            <div class="col-md-12">
                                <input class="form-control" name="kontak_petugas" id="kontak_petugas" type="text">
                                <span class="help-block"></span>
                            </div>
                         </div>
                         <div class="form-group">
                            <label class="control-label col-md-6" id="label-protokol">Estimasi Pengerjaan</label>
                            <div class="col-md-12">
                                <input class="form-control" name="estimasi" id="estimasi" type="text">
                                <span class="help-block"></span>
                            </div>
                         </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSavex" onclick="update_status()" class="btn btn-md btn-primary">Save</button>
                <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_confirm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form">
                <form action="#" id="form_modal_batal" class="form-horizontal">
                    <input type="hidden" value="0" id="modal_id_mc_idbatal" name="modal_id_mc_idbatal"/>
                   
                    <div class="form-body d-flex justify-content-center">
                        <span class="name">Yakin Ingin Batalkan Proses ?</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                 <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="cancel()">TIDAK</button>
                <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="save_batal()" >YA</button>
               
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script type="text/javascript">
    $(function () {
  $('[data-toggle="popover"]').popover()
});

var table;
var company = '<?php echo $row->mc_id?>';
$(document).ready(function() {
    $("#btnSavex").prop("disabled",true);
    $("#petugas").prop("disabled",true);
    $("#kontak_petugas").prop("disabled",true);
    $("[data-toggle=popover]").popover();
    get_protokol();
    get_perimeter();

    $('.check1').change(function() {
        if($(this).is(":checked")) {
            $("#btnSavex").prop("disabled",false);
            $("#petugas").prop("disabled",false);
            $("#kontak_petugas").prop("disabled",false);
        }else{
            $("#btnSavex").prop("disabled",true );
            $("#petugas").prop("disabled",true );
            $("#kontak_petugas").prop("disabled",true );
        }
    });
});
function cancel() {
$('#form_modal')[0].reset();
//datatables();
}

function dialog_confirm(id){
    $('[name="modal_id_mc_idbatal"]').val(id);
    $('#modal_form_confirm').modal('show');
    $('.modal-title').text('');
}

function dialog_detail($mc_id, $tbps_id){
    /*$('#form_detail')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('.modal_form_detail').modal('show'); // show bootstrap modal*/
    block();
    save_method = 'update';
    //$('#form_modal')[0].reset();
    $.ajax({
        url : "<?php echo site_url('sertifikasi/get_data_detail_sertifikasi')?>/"+$mc_id+"/"+$tbps_id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            $('[name="nama_perusahaan"]').val(data.company.mc_name);
            $('[name="cluster"]').val(data.company.jenis);
            $('[name="modal_tbps_id2"]').val(data.company.tbps_id);
            // $('[name="jml"]').val(data.perimeter.v_jml);
            // $('[name="alamat"]').val(data.company.mc_alamat);
            $('[name="modal_mc_id_stat"]').val($mc_id);
            
            $('[name="petugas"]').val(data.company.tbps_nama_pj);
            $('[name="kontak_petugas"]').val(data.company.tbps_no_tlp_pj);
            $('[name="estimasi"]').val(data.company.tbps_estimasi);

            if(data.company.tbps_status==1){
                $(`.check1`).prop("checked", true);
            }else{
                $(`.check1`).prop("checked", false);
            }
            /*
            
            $('[name="status"]').val(data.data.status);*/
            
            $('.modal_form_detail').modal('show');
            unblock();
            //refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax'+jqXHR.responseText);
            unblock();
        }
    });
}

function dialog_detail_ubah($mc_id, $tbps_id){
    block();
    save_method = 'update';
    //$('#form_modal')[0].reset();
    $.ajax({
        url : "<?php echo site_url('sertifikasi/get_data_detail_sertifikasi')?>/"+$mc_id+"/"+$tbps_id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            $('[name="nama_perusahaan"]').val(data.company.mc_name);
            $('[name="cluster"]').val(data.company.jenis);
            $('[name="modal_mc_id_stat"]').val($mc_id);
            $('[name="modal_tbps_id"]').val(data.company.tbps_id);
            
            $('[name="petugas"]').val(data.company.tbps_nama_pj);
            $('[name="kontak_petugas"]').val(data.company.tbps_no_tlp_pj);
            $('[name="status"]').val(data.company.tbps_status_pengajuan_id);
            $('[name="estimasi"]').val(data.company.tbps_estimasi);

            if(data.company.tbps_status==1){
                $(`.check1`).prop("checked", true);
            }else{
                $(`.check1`).prop("checked", false);
            }
            /*
            
            $('[name="status"]').val(data.data.status);*/
            
            $('.modal_form_detail_ubah').modal('show');
            unblock();
            //refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax'+jqXHR.responseText);
            unblock();
        }
    });
}

function add_protokol(protokol_id) {
    var company =  $('#company').val();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal

    $('#modal_id').val(0);
    $('#modal_mc_id').val('<?php echo $row->mc_id?>');
    $('#modal_mpt_id').val(protokol_id);

    $('.modal-title').text('Add Protokol'); // Set Title to Bootstrap modal title
    $('#label-protokol').text('Upload Protokol'); // label photo upload
}

function get_protokol(){
    table = $('#table').DataTable({ 
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": false,
        bPaginate:false,
        searching:false,
        "ajax": {
            "url": "<?php echo base_url().'protokol/ajax_list'?>/"+company,
            "type": "POST"
        },
        "columnDefs": [
             { "targets": 0, "className": "text-right", "width": "5%" },
             { "targets": 1, "className": "text-left", "width": "50%" },
             { "targets": 2, "className": "text-center", "width": "15%" },
             { "targets": 3, "visible":false, "width": "15%", "className":"hidden" },
             { "targets": 4, "className": "text-center", "width": "15%" },
        ],
    });
}

function get_perimeter(){
    $('#tabel_perimeter').DataTable({
        "bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": false,
        "ordering": true,
        "dataSrc": "",
        "bPaginate": false,
        "searching": true,
        "paging": false,
        "info": false,
        "ajax": {
            "url": "<?php echo base_url().'sertifikasi/getPerimeterByPerusahaan/'?>"+company,
            "type": "POST"
        },
        "columnDefs": [
            { "orderable": false, "targets": 0, "className": "text-right", },
            { "visible":false, "orderable": true, "targets": 1,  "className": "text-left hidden", },
            { "orderable": true, "targets": 2,  "className": "text-left", },
            { "orderable": true, "targets": 2,  "className": "text-left", },
            { "orderable": true, "targets": 3,  "className": "text-left", },
            { "orderable": true, "targets": 4,  "className": "text-left", },
           //{ "orderable": false, "targets": 5,  "className": "text-left", },
        
       ],
       drawCallback: function() {
            $('[data-toggle="popover"]').popover({html:true});
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
            get_protokol();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);
            $('#modal_form').removeClass('show');
            $('#progressBar').attr('aria-valuenow', 0).css('width', 0 + '%').text(0 + '%');
            $("#progress-bar").hide();
            $("#form").show();
            get_protokol();
        }
    });
}

function update_status() {
    block();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true); 
    var url;
        url = "<?php echo site_url('sertifikasi/update_status_pengajuan')?>";
    event.preventDefault();
    var formData = new FormData($('#form_detail')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            alert(data.message);
            $('#modal_form_detail').modal('hide');
            window.location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);
            $('#modal_form_detail').removeClass('show');
        }
    });
}

function update_status_detail() {
    block();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true); 
    var url;
        url = "<?php echo site_url('sertifikasi/update_status_pengajuan_detail')?>";
    event.preventDefault();
    var formData = new FormData($('#form_detail_ubah')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            alert(data.message);
            $('#modal_form_detail').modal('hide');
            window.location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);
            $('#modal_form_detail').removeClass('show');
        }
    });
}

function save_batal() {
    block();
    $('#btnSave').text('Saving...');
    $('#btnSave').attr('disabled',true); 
    var url;
        url = "<?php echo site_url('sertifikasi/update_status_batal')?>";
    event.preventDefault();
    var formData = new FormData($('#form_modal_batal')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
                alert(data.message);
                $('#modal_form_confirm').modal('hide');
                window.location.reload();
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('save');
            $('#btnSave').attr('disabled',false);
            $('#modal_form_detail').removeClass('show');
        }
    });
}
</script>
<style type="text/css">
    .name{
        font-size: 16px;
        font-weight: bold;
    }
</style>

