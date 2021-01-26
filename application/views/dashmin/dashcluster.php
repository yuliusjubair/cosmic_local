<?php header("Cache-Control: no-cache, must-revalidate");?>
     <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
    <style> 
        .dataTables_scrollHeadInner { display: none; }
        .row-flex {
          display: flex;
          flex-wrap: wrap;
        }
        .content {
          height: 100%;
          padding: 20px 25px 10px;
        }
    </style>
        
        <div class="row row-flex" id="dashboard_head"></div>

	<div class="rows"><div class="col-sm-12 form-group">
        <button class="btn btn-lg btn-primary" onclick='open_modal_download()'><i class="fa fa-download"></i>&nbsp;Download Rangkuman</button>
    </div></div>
<div class="row">
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">
         
          <div class="card-description">Kategori Perimeter
          <!--  
          <span class="pull-right" style="font-size:12px !important;">
               <a href="<?php //echo base_url(); ?>dashmin/all_kategori_perimeter" class="link"> Lihat Semua</a>
          </span>
          -->
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
                    <!--
                    <span class="pull-right" style="font-size:12px !important;">
                        <a href="<?php echo base_url(); ?>dashmin/all_kategori_provinsi" class="link"> Lihat Semua</a>
                    </span>
                    -->
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
         	<!--
            <span class="pull-right" style="font-size:12px !important;">
                <a href="<?php // echo base_url(); ?>dashmin/all_kategori_index" class="link"> Lihat Semua</a>
            </span>
            -->
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
                    <button onclick="loader()" class="btn btn-primary" type="button" id="ExportExcel"><i class="fa fa-file-text-o"></i>&nbsp;Format Excel</button>

                    <button onclick="loader()" class="btn btn-primary" type="button" id="ExportCsv"><i class="fa fa-file-text-o"></i>&nbsp;Format CSV</button>

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
    var myurl='<?php echo base_url().'dashcluster/excel_rangkuman_all'; ?>';
    window.location = myurl;
}


function download_csv(){
    var myurl='<?php echo base_url().'dashcluster/csv_rangkuman_all'; ?>';
    window.location = myurl;
}

$(document).ready(function() {
    $.noConflict();
    $("#ExportExcel").on("click", function() {
        download_rangkuman();
    });

    $("#ExportCsv").on("click", function() {
        download_csv();
    });
    
    $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashcluster/ajax_dashboard_head'?>",     
        dataType: "html",               
        success: function(response){                    
           $("#dashboard_head").html(response);
        }
    });
     
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
            "url": "<?php echo base_url().'dashcluster/ajax_perimeter_bykategori_all'?>/",
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
            "url": "<?php echo base_url().'dashcluster/ajax_perimeter_byprovinsi_all'?>/",
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
            "url": "<?php echo base_url().'dashcluster/ajax_cosmicindexall'?>/",
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });
});
</script>