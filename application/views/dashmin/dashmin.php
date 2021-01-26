<?php header("Cache-Control: no-cache, must-revalidate");?>
     <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
    <style>
        .sorting_disabled { display: none; }
        .dataTables_length { display: none; }
       /* .row-flex {
          display: flex;
          flex-wrap: wrap;
        }*/
        /*.content {
          height: 100%;
          padding: 20px 25px 10px;
        }*/
    </style>
    <?php if ($group == 1){?>
  <div class="row">
    <div class="col-sm-4 form-group">
          <select id="group_company" name="group_company" data-live-search="true" onchange="refresh_list()"
            class="form-control selectpicker " data-style="btn-white btn-default" >
              <option value="3" >Semua</option>
              <option value="1" selected >BUMN</option>
              <option value="2" >Non BUMN</option>
          </select>
      </div>
  </div>
    <?php }?>
  <div class="row row-flex" id="dashboard_head"></div>

	<div class="row"><div class="col-6 form-group">
        <button class="btn btn-lg btn-primary" onclick='open_modal_download()'><i class="fa fa-download"></i>&nbsp;Download Rangkuman</button>
    </div></div>
<div class="row">
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">

          <div class="card-description">Kategori Perimeter
            <span class="pull-right" style="font-size:12px !important;">
                <a href="#" class="link"  onclick="link_perimeter()"> Lihat Semua</a>
            </span>
        </div>
            <label style="font-size: 12px">Total : <span class="total_kategori"></span></label>
        </div>
        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_perimeterbykategori" class="display table table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
        <div class="col-md-4">
          <div class="card card-hero">
               <div class="card-header">

                  <div class="card-description">Perimeter Provinsi
                    <span class="pull-right" style="font-size:12px !important;">
                        <a href="#" class="link"  onclick="link_provinsi()"> Lihat Semua</a>
                    </span>
                    </div>
                        <label style="font-size: 12px">Total : <span class="total_provinsi"></span></label>
                </div>

            <div class="card-body p-0">
              <div class="tickets-list">
                <table id="tbl_perimeterbyprovinsi" class="display table table-bordered" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">

                  <div class="card-description">BUMN Cosmic Index
                    <span class="pull-right" style="font-size:12px !important;">
                        <a href="#" class="link" onclick="link_cosmic_index()"> Lihat Semua</a>
                    </span>
                    </div>
                        <label style="font-size: 12px">&nbsp; <span class=""></span></label>
                </div>

        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_perimeterbycosmicindex" class="display table table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody >
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    </div>

<div class="row">
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">
          <div class="card-description">Protokol Terbaru</div>
        </div>
        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_protokol_terbaru" class="display table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Perusahaan</th>
                        <th>Data</th>
                        <th>Tgl Update</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
        <div class="col-md-4">
          <div class="card card-hero">
               <div class="card-header">
                <div class="card-description">Kegiatan Terbaru</div>
               </div>
            <div class="card-body p-0">
              <div class="tickets-list">
                <table id="tbl_kegiatan_terbaru" class="display table table-hover" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Perusahaan</th>
                            <th>Data</th>
                            <th>Tgl Update</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">
           <div class="card-description">Kasus Terbaru</div>
        </div>

        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_kasus_terbaru" class="display table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>Perusahaan</th>
                        <th>Data</th>
                        <th>Tgl Update</th>
                    </tr>
                </thead>
                <tbody >
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

  <div class="modal fade" id="modal_form_dashboard" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Download File</h5>
            </div>
            <div class="modal-body form">
                <div class="text-center">
                    <button onclick="loader_rangkuman()" class="btn btn-primary" type="button" id="ExportExcel"><i class="fa fa-file-text-o"></i>&nbsp;Format Excel</button>

                    <button onclick="loader_rangkuman()" class="btn btn-primary" type="button" id="ExportCsv"><i class="fa fa-file-text-o"></i>&nbsp;Format CSV</button>

                     <div id="progress-bar" style="display:none;margin-top: 11px;">
                        <img src="<?php echo base_url(); ?>assets/images/busy.gif"/>&nbsp;Waiting For Download File...
                    </div>

                </div>



            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">


function open_modal_download(){
	$('#modal_form_dashboard').modal('show');
}

function download_rangkuman(){
	var group_company  = $('#group_company').val();
    var myurl='<?php echo base_url().'dashmin/excel_rangkuman_all?group_company='; ?>'+group_company;
    window.location = myurl;
}

function download_csv(){
	var group_company  = $('#group_company').val();
    var myurl='<?php echo base_url().'dashmin/csv_rangkuman_all?group_company='; ?>'+group_company;
    window.location = myurl;
}

function link_perimeter(){
  	var group_company  = $('#group_company').val();
    var myurl='<?php echo base_url().'dashmin/all_kategori_perimeter?group_company='; ?>'+group_company;
    window.location = myurl;
}

function link_provinsi(){
  	var group_company  = $('#group_company').val();
    var myurl='<?php echo base_url().'dashmin/all_kategori_provinsi?group_company='; ?>'+group_company;
    window.location = myurl;
}

function link_cosmic_index(){
  	var group_company  = $('#group_company').val();
    var myurl='<?php echo base_url().'dashmin/all_kategori_index?group_company='; ?>'+group_company;
    window.location = myurl;
}

function protokol_terbaru(group_company){
  	$('#tbl_protokol_terbaru').dataTable().fnDestroy();
    $('#tbl_protokol_terbaru').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": false,
        //"ordering": false,
        "paging": true,
        "dataSrc": "",
        //"bPaginate": false,
        "searching": false,
        "scrollY": '50vh',
        "order": [[ 3, "desc" ]],
        "pageLength": 10,
        "sDom": "lfrti",
        "info": false,
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashmin/protokol_terbaru?group_company='?>"+group_company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "visible": false, "className": "text-left hidden", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "orderable": true, "className": "text-left", },
            { "targets": 3, "type":"date", "orderable": true, "className": "text-left", }
        ]
    });
}

function kegiatan_terbaru(group_company){
  	$('#tbl_kegiatan_terbaru').dataTable().fnDestroy();
    $('#tbl_kegiatan_terbaru').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": false,
        //"ordering": false,
        "dataSrc": "",
        //"bPaginate": false,
        "paging": true,
        "searching": false,
        "sDom": "lfrti",
        "info": false,
        "scrollY": '50vh',
        "order": [[ 2, "desc" ]],
        "pageLength": 10,
        "scrollCollapse": true,
        "ajax": {
            "url": "<?php echo base_url().'dashmin/kegiatan_terbaru?group_company='?>"+group_company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "type":"date", "orderable": true, "className": "text-left", }
        ]
    });
}

function kasus_terbaru(group_company){
    $('#tbl_kasus_terbaru').dataTable().fnDestroy();
    $('#tbl_kasus_terbaru').DataTable({
        "responsive": true,
        "processing": true,
        "serverSide": false,
        //"ordering": false,
        "dataSrc": "",
        //"bPaginate": false,
        "searching": false,
        "paging": true,
        "scrollY": '50vh',
        "order": [[ 2, "desc" ]],
        "pageLength": 10,
        "scrollCollapse": true,
        "sDom": "lfrti",
        "info": false,
        "ajax": {
            "url": "<?php echo base_url().'dashmin/kasus_terbaru?group_company='?>"+ group_company,
            "type": "POST"
        },
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2,"type":"date", "orderable": true, "className": "text-left", }
        ]
    });
}

function refresh_list(){
  var group_company  = $('#group_company').val();
  console.log(group_company);
  console.log("<?php echo base_url().'dashmin/ajax_dashboard_head?group_company='?>"+ group_company);
  $.ajax({
      type: "GET",
      url: "<?php echo base_url().'dashmin/ajax_dashboard_head?group_company='?>"+ group_company,
      dataType: "html",
      success: function(response){
         $("#dashboard_head").html(response);
      }
  });
  protokol_terbaru(group_company);
  kegiatan_terbaru(group_company);
  kasus_terbaru(group_company);
  perimeterbykategori(group_company);
  perimeterbyprovinsi(group_company);
  perimeterbycosmicindex(group_company);
}

$(document).ready(function() {
  $('#group_company option[value="1"]').prop('selected', true);
  //  $("#group_company").val(1);
    var group_company  = $('#group_company').val();
    console.log("<?php echo base_url().'dashmin/ajax_perimeter_bykategori_all?group_company='?>"+ group_company);
    $.noConflict();
    $("#ExportExcel").on("click", function() {
        download_rangkuman();
    });

    $("#ExportCsv").on("click", function() {
        download_csv();
    });
    protokol_terbaru(group_company);
    kegiatan_terbaru(group_company);
    kasus_terbaru(group_company);
    $.ajax({
        type: "GET",
        url: "<?php echo base_url().'dashmin/ajax_dashboard_head?group_company='?>"+ group_company,
        dataType: "html",
        success: function(response){
           $("#dashboard_head").html(response);
        }
    });
     perimeterbykategori(group_company);
     perimeterbyprovinsi(group_company);
     perimeterbycosmicindex(group_company);


});

function perimeterbykategori(group_company){
  $('#tbl_perimeterbykategori').dataTable().fnDestroy();
  tbl_perimeterbykategori = $('#tbl_perimeterbykategori').DataTable({
      "responsive": true,
      "processing": true,
      "serverSide": true,
      "ordering": false,
      "dataSrc": "",
      "bPaginate": false,
      "searching": false,
      "paging": false,
      "info": false,
      "scrollY": '50vh',
      "scrollCollapse": true,
      "ajax": {
          "url": "<?php echo base_url().'dashmin/ajax_perimeter_bykategori_all?group_company='?>"+ group_company,
          "type": "POST"
      },
      "order": [],
      "columnDefs": [
          { "targets": 0, "orderable": true, "className": "text-left", },
          { "targets": 1, "orderable": true, "className": "text-right", },
      ],
      drawCallback:function(settings)
      {
       $('.total_kategori').html(settings.json.recordsTotal+" Unit");
      }
  });
}

 function perimeterbyprovinsi(group_company){
    $('#tbl_perimeterbyprovinsi').dataTable().fnDestroy();
       tbl_perimeterbyprovinsi = $('#tbl_perimeterbyprovinsi').DataTable({
           "responsive": true,
           "processing": true,
           "serverSide": true,
           "ordering": false,
           "dataSrc": "",
           "bPaginate": false,
           "searching": false,
           "paging": false,
           "info": false,
           "scrollY": '50vh',
           "scrollCollapse": true,
           "ajax": {
               "url": "<?php echo base_url().'dashmin/ajax_perimeter_byprovinsi_all?group_company='?>"+ group_company,
               "type": "POST"
           },
           "order": [],
           "columnDefs": [
               { "targets": 0, "orderable": false, "className": "text-left", },
               { "targets": 1, "orderable": false, "className": "text-right", },
           ],
           drawCallback:function(settings)
           {
            $('.total_provinsi').html(settings.json.recordsTotal+" Unit");
           }
       });

 }

 function perimeterbycosmicindex(group_company){
   $('#tbl_perimeterbycosmicindex').dataTable().fnDestroy();
       tbl_perimeterbycosmicindex = $('#tbl_perimeterbycosmicindex').DataTable({
           "responsive": true,
           "processing": true,
           "serverSide": true,
           "ordering": false,
           "dataSrc": "",
           "bPaginate": false,
           "searching": false,
           "paging": false,
           "info": false,
           "scrollY": '50vh',
           "scrollCollapse": true,
           "ajax": {
               "url": "<?php echo base_url().'dashmin/ajax_cosmicindexall_depan?group_company='?>"+ group_company,
               "type": "POST"
           },
           "order": [],
           "columnDefs": [
               { "targets": 0, "orderable": true, "className": "text-left", },
               { "targets": 1, "orderable": true, "className": "text-right", },
           ]
       });
 }
</script>
