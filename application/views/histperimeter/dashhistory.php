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
        <div class="row">
            	<div class="col-sm-4 form-group">
                    <select id="bulan" name="bulan" data-live-search="true" onchange="refresh_list()"
                    	class="form-control selectpicker " data-style="btn-white btn-default" >
                        
                        <option value="2" selected>3 month</option>
                        <option value="3">6 month</option>
                        <option value="4">12 month</option>
                    </select>
               	</div>
        </div>
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

        </div>
    </div>
</div>
<div class="row">
<div class="col-12 col-md-12 col-lg-12">
<div class="card">
          <!-- <div class="card-header">
            <h4>Check</h4>
          </div> -->
          <input type="hidden" id="mc_id" value="<?php echo $mc_id; ?>" />
          <div class="card-body"><!--  style="margin: 10px auto;" -->
            <div class="form-group">
                <div class="table-responsive">
                   <table id="tbl_cosmic_index" class="display table table-bordered" >
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Week</th>
                                <th>Nama Perusahaan</th>
                                <th>Cosmic Index</th>
                                <th>Pemenuhan Monitoring</th>
                                <th>Jumlah Perimeter</th>

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

<?php //var_dump($xcharts2);die; ?>
<script type="text/javascript">
$(document).ready(function() {
  $.noConflict();
    $("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();
    });

    $("#ExportCsv").on("click", function() {
        $('.buttons-csv').click();
    });

    var today = new Date();
    var dd = today.getDate();

    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();
    var bulan =  $('#bulan').val();

    if(dd<10)
    {dd='0'+dd;}

    if(mm<10)
    {mm='0'+mm;}
    today = yyyy+'-'+mm+'-'+dd;
    console.log(today);
    tes="<?php echo base_url().'histperimeter/ajax_get_cosmic_index_list'?>/<?php echo $mc_id; ?>/"+bulan;
    console.log(tes);

    function newexportaction(e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function (e, s, data) {
            data.start = 0;
            data.length = 2147483647;
            dt.one('preDraw', function (e, settings) {
                if (button[0].className.indexOf('buttons-copy') >= 0) {
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                    $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                    $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                    $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-print') >= 0) {
                    $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function (e, s, data) {
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });
                setTimeout(dt.ajax.reload, 0);
                return false;
            });
        });
        dt.ajax.reload();
    };
    $('title').html('Rangkuman Grafik Cosmic');
    tbl_cosmic_index = $('#tbl_cosmic_index').DataTable({
        "bDestroy": true,
        "responsive": true,
        "processing": true,
        "serverSide": false,
        "ordering": true,
        "dataSrc": "",
        "bPaginate": false,
        "searching": true,
        "paging": false,
        "info": true,
        "dom": 'Bfrtip',
        "buttons": [
            {
                "extend": 'csv',
                "text": '<i class="fa fa-file-text-o" style="color:green;"></i>&nbsp;&nbsp;CSV',
                "titleAttr": 'CSV',
                "className": 'hidden',
                "action": newexportaction
            },
            {
                "extend": 'excel',
                "text": '<i class="fa fa-file-excel-o" style="color:green;"></i>&nbsp;&nbsp;Excel',
                "titleAttr": 'Excel',
                "className": 'hidden',
                "action": newexportaction
            },
            {
                "text": '<i class="fa fa-download"></i>&nbsp;&nbsp;Download',
                "titleAttr": 'Download',
                "className": 'btn btn-primary ExportDialog',
                "action" : open_dialog
            },
        ],
        "ajax": {
            "url": "<?php echo base_url().'histperimeter/ajax_get_cosmic_index_list'?>/<?php echo $mc_id; ?>/"+bulan,
            "type": "POST"
        },
        "columnDefs": [
            { "orderable": true, "targets": 0, "className": "text-right", },
            { "orderable": true, "targets": 1,  "className": "text-left", },
            { "orderable": true, "targets": 2,  "className": "text-left", },
            { "orderable": true, "targets": 3,  "className": "text-right", },
            { "orderable": true, "targets": 4,  "className": "text-right", },
            { "orderable": true, "targets": 5,  "className": "text-right", },

       ],
    });

});
</script>
<script type="text/javascript">
console.log( $('#mc_id').val());
var mc_id = $('#mc_id').val();
var bulan = $('#bulan').val();
var chart2;
var chart3;
var options2 = <?php echo $xcharts2->renderOptions(); ?>;
$.getJSON("<?php echo base_url()."histperimeter/get_data_chart/".  $mc_id."/" ?>"+bulan, function(json2) {
    options2.xAxis.categories = json2[0]['data'];
    options2.series[0] = json2[1];
    options2.series[1] = json2[2];
    chart2 = new Highcharts.Chart(options2);
});



//graphic jml perimeter
var options3= <?php echo $xcharts3->renderOptions(); ?>;
$.getJSON("<?php echo base_url()."histperimeter/get_data_chart_jml_perimeter/".  $mc_id."/" ?>"+bulan, function(json3) {
    options3.xAxis.categories = json3[0]['data'];
    options3.series[0] = json3[1];
    chart3 = new Highcharts.Chart(options3);
});

<?php echo $xcharts2->render("chart2"); ?>
<?php echo $xcharts3->render("chart3"); ?>

function refresh_list(){
  function newexportaction(e, dt, button, config) {
      var self = this;
      var oldStart = dt.settings()[0]._iDisplayStart;
      dt.one('preXhr', function (e, s, data) {
          data.start = 0;
          data.length = 2147483647;
          dt.one('preDraw', function (e, settings) {
              if (button[0].className.indexOf('buttons-copy') >= 0) {
                  $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
              } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                  $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                      $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                      $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
              } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                  $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                      $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                      $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
              } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                  $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                      $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                      $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
              } else if (button[0].className.indexOf('buttons-print') >= 0) {
                  $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
              }
              dt.one('preXhr', function (e, s, data) {
                  settings._iDisplayStart = oldStart;
                  data.start = oldStart;
              });
              setTimeout(dt.ajax.reload, 0);
              return false;
          });
      });
      dt.ajax.reload();
  };
  tbl_cosmic_index = $('#tbl_cosmic_index').DataTable({
      "bDestroy": true,
      "responsive": true,
      "processing": true,
      "serverSide": false,
      "ordering": true,
      "dataSrc": "",
      "bPaginate": false,
      "searching": true,
      "paging": false,
      "info": true,
      "dom": 'Bfrtip',
      "buttons": [
          {
              "extend": 'csv',
              "text": '<i class="fa fa-file-text-o" style="color:green;"></i>&nbsp;&nbsp;CSV',
              "titleAttr": 'CSV',
              "className": 'hidden',
              "action": newexportaction
          },
          {
              "extend": 'excel',
              "text": '<i class="fa fa-file-excel-o" style="color:green;"></i>&nbsp;&nbsp;Excel',
              "titleAttr": 'Excel',
              "className": 'hidden',
              "action": newexportaction
          },
          {
              "text": '<i class="fa fa-download"></i>&nbsp;&nbsp;Download',
              "titleAttr": 'Download',
              "className": 'btn btn-primary ExportDialog',
              "action" : open_dialog
          },
      ],
      "ajax": {
          "url": "<?php echo base_url().'histperimeter/ajax_get_cosmic_index_list'?>/<?php echo $mc_id; ?>/"+bulan,
          "type": "POST"
      },
      "columnDefs": [
          { "orderable": true, "targets": 0, "className": "text-right", },
          { "orderable": true, "targets": 1,  "className": "text-left", },
          { "orderable": true, "targets": 2,  "className": "text-left", },
          { "orderable": true, "targets": 3,  "className": "text-right", },
          { "orderable": true, "targets": 4,  "className": "text-right", },
          { "orderable": true, "targets": 5,  "className": "text-right", },

     ],
  });
  var mc_id = $('#mc_id').val();
  var bulan =  $('#bulan').val();

  var chart2;
  var chart3;
  var options2 = <?php echo $xcharts2->renderOptions(); ?>;
  $.getJSON("<?php echo base_url().'histperimeter/get_data_chart'?>/<?php echo $mc_id; ?>/"+bulan, function(json2) {
      options2.xAxis.categories = json2[0]['data'];
      options2.series[0] = json2[1];
      options2.series[1] = json2[2];
      chart2 = new Highcharts.Chart(options2);
  });



  //graphic jml perimeter
  var options3= <?php echo $xcharts3->renderOptions(); ?>;
  $.getJSON("<?php echo base_url().'histperimeter/get_data_chart_jml_perimeter'?>/<?php echo $mc_id; ?>/"+bulan, function(json3) {
      options3.xAxis.categories = json3[0]['data'];
      options3.series[0] = json3[1];
      chart3 = new Highcharts.Chart(options3);
  });

  <?php echo $xcharts2->render("chart2"); ?>
  <?php echo $xcharts3->render("chart3"); ?>
}
</script>
