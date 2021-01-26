<?php header("Cache-Control: no-cache, must-revalidate");?>
     <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
     <style> 
        .dataTables_scrollHeadInner { display: none; }
    </style>
   
        <div class="row" id="dashboard_head"></div>
    	<div class="row" id="dashboard_head2"></div>
<div class="row">
    <div class="rows col-sm-12">    
        <div class="card card-hero">
            <div class="card-header">
                <div style="clear: left;height:270px;" id="container2" class="col-sm-12 text-center">
                    If you're reading this, it's not working.
                </div>
            </div>
        </div>
    </div>
    <div class="rows col-sm-12">    
        <div class="card card-hero">
            <div class="card-header">
                <div style="clear: left;height:270px;" id="container3" class="col-sm-12 text-center">
                    If you're reading this, it's not working.
                </div>
            </div>
        </div>
    </div>

    <div class="rows col-sm-12" style="margin:2% 0% 0% -1%;">
        <div class="col-sm-4 form-group">
            <select id="kasus" name="kasus" data-live-search="true" onchange="refresh_list()"
                class="form-control selectpicker " data-style="btn-white btn-default" >
                <?php 
                foreach ($status_kasus->result() as $row) { 
                ?>
                <option value="<?php echo $row->msk_id;?>" >
                    <?php echo $row->msk_name2;?>
                </option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
   
<div class="row">    
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">
          <!-- <div class="card-icon">
            <i class="far fa-question-circle"></i>
          </div> -->
          <div class="card-description">Perusahaan
			<span class="pull-right" style="font-size:12px !important;">
            	<a href="#" class="link"  onclick="link_perusahaan()"> Lihat Semua</a>
        	</span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="tickets-list">
           <table id="tbl_perusahaan" class="display table table-bordered" cellspacing="0">
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
          <div class="card-description">Provinsi
          	<span class="pull-right" style="font-size:12px !important;">
            	<a href="#" class="link"  onclick="link_provinsi()"> Lihat Semua</a>
        	</span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_provinsi" class="display table table-bordered" cellspacing="0">
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
          <div class="card-description">Kota
			<span class="pull-right" style="font-size:12px !important;">
            	<a href="#" class="link"  onclick="link_kabupaten()"> Lihat Semua</a>
        	</span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_kabupaten" class="display table table-bordered" cellspacing="0">
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
    
  </div>
<?php //var_dump($xcharts2);die; ?>
<script type="text/javascript">
function link_perusahaan(){
	var kasus = $('#kasus').val();
    var myurl='<?php echo base_url().'dashkasus/all_perusahaan/'; ?>'+kasus;
    window.location = myurl;
}

function link_provinsi(){
	var kasus = $('#kasus').val();
    var myurl='<?php echo base_url().'dashkasus/all_provinsi/'; ?>'+kasus;
    window.location = myurl;
}

function link_kabupaten(){
	var kasus = $('#kasus').val();
    var myurl='<?php echo base_url().'dashkasus/all_kabupaten/'; ?>'+kasus;
    window.location = myurl;
}

$(document).ready(function() {
    $.noConflict();
    var kasus =  $('#kasus').val();
    $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashkasus/ajax_dashboard_head'?>",     
        dataType: "html",               
        success: function(response){                    
           	$("#dashboard_head").html(response);
    		var positif =  $("#card_3").html();
    		var sembuh =  $("#card_4").html();
     		var meninggal = $("#card_5").html();
    		var fatality_rate = Number((meninggal*100/positif).toFixed(0));
    		var recovery_rate = Number((sembuh*100/positif).toFixed(0));
           	$("#dashboard_head2").html('<div class="col-lg col-md-6 col-sm-6 col-6">'
           	            +'<div class="card card-statistic-1">'
           	            +'<div class="card-wrap">'
           	            +'<div class="card-header">Fatality Rate<br></div>'
           	            +'<b><div class="card-body" >'+fatality_rate+' %</div></b>'
           	            +'</div></div></div>'
           	         	+'<div class="col-lg col-md-6 col-sm-6 col-6">'
           	            +'<div class="card card-statistic-1">'
           	            +'<div class="card-wrap">'
           	            +'<div class="card-header">Recovery Rate<br></div>'
           	            +'<b><div class="card-body" >'+recovery_rate+' %</div></b>'
           	            +'</div></div></div>');
        }
    });
   
    tbl_perusahaan = $('#tbl_perusahaan').DataTable({ 
        "destroy": true,
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
            "url": "<?php echo base_url().'dashkasus/ajax_company'?>/"+kasus,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });

    tbl_provinsi = $('#tbl_provinsi').DataTable({ 
        "destroy": true,
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
            "url": "<?php echo base_url().'dashkasus/ajax_provinsi'?>/"+kasus,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });

    tbl_kabupaten = $('#tbl_kabupaten').DataTable({ 
        "destroy": true,
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
            "url": "<?php echo base_url().'dashkasus/ajax_kabupaten'?>/"+kasus,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });
});

function refresh_list() {
    var kasus =  $('#kasus').val();
    tbl_perusahaan = $('#tbl_perusahaan').DataTable({ 
        "destroy": true,
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
            "url": "<?php echo base_url().'dashkasus/ajax_company'?>/"+kasus,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });

    tbl_provinsi = $('#tbl_provinsi').DataTable({ 
        "destroy": true,
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
            "url": "<?php echo base_url().'dashkasus/ajax_provinsi'?>/"+kasus,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });

    tbl_kabupaten = $('#tbl_kabupaten').DataTable({ 
        "destroy": true,
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
            "url": "<?php echo base_url().'dashkasus/ajax_kabupaten'?>/"+kasus,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });
}

var chart2;
var options2 = <?php echo $xcharts2->renderOptions(); ?>;
$.getJSON("<?php echo base_url()."dashkasus/get_data_chart"?>", function(json2) {
    options2.xAxis.categories = json2[0]['data'];
    options2.series[0] = json2[1];
    options2.series[1] = json2[2];
    options2.series[2] = json2[3];
    chart2 = new Highcharts.Chart(options2);    
});


var chart3;
var options3 = <?php echo $xcharts3->renderOptions(); ?>;
$.getJSON("<?php echo base_url()."dashkasus/get_data_chart_pertgl"?>", function(json3) {
	options3.xAxis.categories = json3[0]['data'];
	options3.series[0] = json3[1];
	options3.series[1] = json3[2];
	options3.series[2] = json3[3];
    chart3 = new Highcharts.Chart(options3);    
});

<?php echo $xcharts2->render("chart2"); ?>
<?php echo $xcharts3->render("chart3"); ?>
</script>