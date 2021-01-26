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
                <h6> Cosmic Index Minggu Lalu
                	<br /><span style="color: red">
                	<?php echo $cosmic_index_weekbefore?> %</span>
                </h6>
            </div>
		</div>
        <div class="col-6 col-sm-6"><br/></div>
     	<div class="col-6 col-sm-6"></div>
        <div class="row container">
            <div class="col-12 col-md-4 col-lg-4">
              	<h7><b>Kluster Industri</h7>
                <h6><?php echo $row->jenis?></b></h6>
        	</div>
        	<div class="col-12 col-md-6 col-lg-6">
              	<h7>Status</h7>
                <?php if($row->mc_flag==2):?>
                    <h6>Non BUMN </h6>
                <?php else:?>
                    <h6>BUMN</h6>
                <?php endif;?>    
            </div>
        </div>
		<div class="col-6 col-sm-6"><br/></div>
            <div class="row container">
                <div class="col-12 col-md-4 col-lg-4">
                  <h7>Nama Penanggung Jawab :</h7><h6><?php echo $row->tbpa_nama_pj?></h6>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <h7>Kontak Penanggung Jawab :</h7><h6><?php echo $row->tbpa_no_tlp_pj?></h6>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <h7>Email Penanggung Jawab :</h7><h6><?php echo $row->tbpa_email_pj?></h6>
                </div>
            </div>
            <div class="col-6 col-sm-6"><br /></div>
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
    <div class="card-body">
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
                            <th>Status Proses Atestasi</th>
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

<script type="text/javascript">
var table;
var company = '<?php echo $row->mc_id?>';
var tbpa_id = '<?php echo $tbpa_id?>';
$(document).ready(function() {
    get_protokol();
    get_perimeter();
});
function cancel() {
	$('#form_modal')[0].reset();
}

function dialog_confirm(id){
    $('[name="modal_id_tbspa"]').val(id);
    $('#modal_form_confirm').modal('show');
    $('.modal-title').text('');
}

function dialog_detail($mc_id, $mpm_id, $tbspa_id){
    block();
    save_method = 'update';
    $.ajax({
        url : "<?php echo site_url('dashatestasi/get_data_detail_partner')?>/"+$mc_id+"/"+$mpm_id+"/"+$tbspa_id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            $('[name="mpm_id"]').val(data.perimeter.mpm_id);
            $('[name="mpm_name"]').val(data.perimeter.mpm_name);
            $('[name="nama_perusahaan"]').val(data.perimeter.mc_name);
            $('[name="nama_perusahaan"]').val(data.perimeter.mc_name);
            $('[name="cluster"]').val(data.perimeter.mpmk_name);
            $('[name="jml"]').val(data.perimeter.v_jml);
            $('[name="alamat"]').val(data.perimeter.mpm_alamat);
            $('[name="modal_mc_id_stat"]').val($mc_id);
            $('[name="modal_tbspa_id"]').val($tbspa_id);
            $('[name="petugas"]').val(data.tbspa.tbpa_nama_pj);
            $('[name="kontak_petugas"]').val(data.tbspa.tbpa_no_tlp_pj);
            $('[name="estimasi"]').val(data.tbspa.tbspa_estimasi);

            if(data.tbspa.tbspa_status==1){
                $(`.check1`).prop("checked", true);
            }else{
                $(`.check1`).prop("checked", false);
            }
            $('.modal_form_detail').modal('show');
            unblock();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax'+jqXHR.responseText);
            unblock();
        }
    });
}

function get_protokol() {
    table = $('#table').DataTable({ 
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "ordering": false,
        "bPaginate":false,
        "searching":false,
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
            "url": "<?php echo base_url().'dashatestasi/getPerimeterByPerusahaan/'?>"+company+"/"+tbpa_id,
            "type": "POST"
        },
        "columnDefs": [
            { "orderable": false, "targets": 0, "className": "text-right", },
            { "visible":false, "orderable": true, "targets": 1,  "className": "text-left hidden", },
            { "orderable": false, "targets": 2,  "className": "text-left", },
            { "orderable": false, "targets": 3,  "className": "text-right", },
            { "orderable": false, "targets": 4,  "className": "text-left", },
            { "visible":false, "orderable": false, "targets": 6,  "className": "text-left hidden", },
        ],
       	drawCallback: function() {
        	$('[data-toggle="popover"]').popover({html:true});
		}
    });
}

function reload_table() {
    table.ajax.reload(null,false); 
}
</script>
<style type="text/css">
.name{
    font-size: 16px;
    font-weight: bold;
}
</style>