<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/amsify.suggestags.css">
<script src="<?php echo base_url(); ?>assets/js/jquery.amsify.suggestags.js"></script>


<!-- untuk slider range-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/ion/ion.rangeSlider.min.css?1576771917297">  
<!-- <script src="https://demos.jquerymobile.com/1.4.2/js/jquery.js"></script>
-->
<script src="<?php echo base_url(); ?>assets/ion/ion.rangeSlider.min.js?1576771917297"></script>
<style>
   .datepicker{z-index:9999 !important}
   
   .ui-slider .ui-btn-inner {
        padding: 4px 0 0 0 !important;
    }

    .irs-max{
        display: none;
    }
     
    .ui-slider-popup {
        position: absolute !important;
        width: 64px;
        height: 64px;
        text-align: center;
        font-size: 36px;
        padding-top: 14px;
        z-index: 100;
        opacity: 0.8;
    }
   </style>
    
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

    <div class="row">
        <div class="col-3">
            <div class="card card-statistic-1">
                <div class="card-wrap">
                    <div class="card-header">Total Rumah Singgah<br></div>
                    <b><div class="card-body" id="card_1"><?php echo $count?></div></b>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php //echo $comp_name ?>
    </div>
    <div class="row col-sm-12">
        <div class="table-responsive">
            <table id="table" class="table table-hover" cellspacing="0">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Nama Rumah Singgah</th>
                        <th>Kota</th>
                        <th>Nama BUMN</th>
                        <th>Kapasitas</th>
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

function datatables(kota, bumn, kapasitas){
    // var company =  $('#company').val();
    var company =  '';
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
            "url": "<?php echo base_url().'dashrs/ajax_list'?>/"+company,
            "type": "POST",
            'data': {
               'kota': kota,
               'bumn': bumn,
               'kapasitas' : kapasitas
            },
        },
        
        "dom": 'Bfrtip',
         "buttons": [
             {
                 "text": '<i class="fa fa-search"></i>&nbsp;&nbsp;Filter Pencarian',
                 "titleAttr": 'Download',
                 "className": 'btn btn-primary text-right',
                 "action" : open_dialog_filter
             },
         ],
        "columnDefs": [
            { "targets": 0, "orderable": false, "className": "text-right", },
            { "targets": 1, "orderable": true, "className": "text-left", },
            { "targets": 2, "orderable": true, "className": "text-left", },
            { "targets": 3, "orderable": true, "className": "text-left", },
            { "targets": 4, "orderable": true, "className": "text-left", },
            { "targets": 5, "visible": false, "className": "text-left hidden", },
            { "targets": 6, "orderable": false, "className": "text-left", },
        ],
       
    });
     
     $('#table tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        //alert( 'You clicked on '+data[0]+'\'s row' );
        window.location.href="<?php echo site_url('dashrs/detail_rumahsinggah')?>/"+data[5];
    });
    //table.ajax.reload();
    //table.ajax.draw();
}

function open_dialog_filter(){
    $('#modal_form_filter').modal('show');
}

function terapkan(){
    var kota = $("#kota").val();
    var nama_bumn = $("#nama_bumn").val();
    var kapasitas = $("#kapasitas").val();
    $('#modal_form_filter').modal('hide');
    datatables(kota, nama_bumn, kapasitas);
}

function reset(){
    datatables();
}

$(document).ready(function() {
    $('.irs-max').hide();
    $("#kapasitas").ionRangeSlider({
        type: "double",
        skin: "big",
        //grid: true,
        min: 0,
        max: 1500,
        from: 0,
        to: 500,
        postfix: ' orang',
    });

    datatables();

$('textarea[name="kota"]').amsifySuggestags({
        suggestions: <?php echo $kota ?>,
        classes: ['bg-info'],
        whiteList: true,
        afterAdd : function(value) {
            console.info(value);
        },
        afterRemove : function(value) {
            console.info(value);
        },
    });

$('textarea[name="nama_bumn"]').amsifySuggestags({
        suggestions: <?php echo $comp_name ?>,
        classes: ['bg-info'],
        whiteList: true,
        afterAdd : function(value) {
            console.info(value);
        },
        afterRemove : function(value) {
            console.info(value);
        },
    });
});
</script>

<div class="modal fade" id="modal_form_filter" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Filter Pencarian</h5>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-sm-6">Kota</label>
                        <div class="col-sm-12">
                            <!-- <input id="kota" name="kota" placeholder="Input Nama Kota" class="form-control" type="text" value="Apple"> -->
                            <textarea style="margin-top: 0px; margin-bottom: 0px; height: 89px;" id="kota" name="kota" placeholder="Input Nama Kota" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6">Nama BUMN</label>
                        <div class="col-sm-12">
                            <!-- <input id="nama_bumn" name="nama_bumn" placeholder="Input Nama BUMN" class="form-control" type="text"> -->
                            <textarea style="margin-top: 0px; margin-bottom: 0px; height: 89px;" id="nama_bumn" name="nama_bumn" placeholder="Input Nama BUMN" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6">Kapasitas</label>
                        <div class="col-sm-12">
                            <input id="kapasitas" name="kapasitas" placeholder="Input Kapasitas" class="form-control" type="text">
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-primary" id="btnSave" onclick="terapkan()" >Terapkan Filter</button>
                        <button type="button" class="btn btn-md btn-danger" data-dismiss="modal" onclick="reset()">Reset</button>
                    </div>
                </div>

            
           
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>