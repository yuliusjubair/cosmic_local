<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>COSMIC - Monitoring Perimeter</title>
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    </head>
    <style type="text/css">
      .loader {
          width: 60px;
        }

        .loader-wheel {
          animation: spin 1s infinite linear;
          border: 2px solid rgba(30, 30, 30, 0.5);
          border-left: 4px solid #fff;
          border-radius: 50%;
          height: 50px;
          margin-bottom: 10px;
          width: 50px;
        }

        .loader-text {
          color: #000;
          font-family: arial, sans-serif;
        }

        .loader-text:after {
          content: 'Loading';
          animation: load 2s linear infinite;
        }

        @keyframes spin {
          0% {
            transform: rotate(0deg);
          }
          100% {
            transform: rotate(360deg);
          }
        }

        @keyframes load {
          0% {
            content: 'Loading';
          }
          33% {
            content: 'Loading.';
          }
          67% {
            content: 'Loading..';
          }
          100% {
            content: 'Loading...';
          }
        }
    </style>
<body>
<div class="row">
<?php $this->load->view('company_select');?>
</div>
<div class="row">
		<div class="col-sm-4 form-group">
            <select id="week" name="week" data-live-search="true" onchange="refresh_list()"
            	class="form-control  " data-style="btn-white btn-default" >
            	<?php
            	foreach ($week->result() as $row) {
            	?>
                <option value="<?php echo $row->v_awal;?>" >
        			<?php echo 'Week '.$row->v_rownum.' ('.$row->tgl.')';?>
        	    </option>
        		<?php } ?>
            </select>
       	</div>
       	<div class="col-sm-4-offset form-group">
            <!--h4><b><span>Pemenuhan Monitoring </span><span id="pemenuhan_monitoring"></span><span> % </span></b></h4 -->
            <!--a href="#" class="btn btn-primary"> <i class="fa fa-download"></i>&nbsp;&nbsp;Download</a -->

            <a class="btn btn-md btn-white btn-default" href="javascript:void(0)" title="Lihat Rangkuman Grafik"
                      onclick="clickDetail()">
                      &nbsp;&nbsp;Lihat Rangkuman Grafik</a>

        </div>
</div>

<div class= "row">
  <diV class ="col-sm-12 form-group">
    <div class="card">
                  <div class="card-header">
                    <h3 id="mc_name"><?php echo $mc_name;?></h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span class="-success" id="pg_cosmic_index" style = "font-size: 30px; color=green!important;"></span></b>
                  </div>

                  <div class="card-body">
                    <div class="loader">
                      <div class="loader-wheel"></div>
                      <div class="loader-text"></div>
                    </div>
                    <div class="from-group" style="margin-bottom:20px!important;">
                    <label style="font-size:17px">Pemenuhan Standar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(<span class="-success" id="ps_pemenuhan_standar" style = "font-size: 15px; color=green!important;"></span>)</b></label>
                    <div class="progress mb-6" data-height="10" style="height: 50px !important; margin-bottom:0px!important;">
                      <div id="pg_pemenuhan_standar" class="progress-bar bg-success" role="progressbar"  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" ></div>
                    </div>
                    <span style="font-size:11px">Pemenuhan Kebijakan, SOP dan Dokumen Pendukung</span>
                  </div>
                  <div class="from-group" style="margin-bottom:20px!important;">
                    <label style="font-size:17px">Pemenuhan Ceklist Monitoring&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(<span class="-success" id="ps_pemenuhan_monitoring" style = "font-size: 15px; color=orange!important;"></span>)</b></label>
                    <div class="progress mb-6" data-height="10" style="height: 50px !important; margin-bottom:0px!important;">
                      <div id="pg_pemenuhan_monitoring" class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span style="font-size:11px">Aktivitas Sosialisasi Protokol Kepada Stakeholder Terkait</span>
                  </div>
                  <div class="from-group" style="margin-bottom:20px!important;">
                    <label style="font-size:17px">Evidence&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>(<span class="-success" id="ps_pemenuhan_eviden" style = "font-size: 15px; color=red!important;"></span>)</b></label>
                    <div class="progress mb-3" data-height="10" style="height: 50px !important; margin-bottom:0px!important;">
                      <div id="pg_pemenuhan_eviden" class="progress-bar bg-danger" role="progressbar"  aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" ></div>
                    </div>
                    <span style="font-size:11px">Bukti Penunjang Pelaksanaan Checklist Monitoring</span>
                  </div>
                  </div>
      </div>
  </div>
</div>

    <div class="row">
    	<div class="col-sm-12">
            <div class="table-responsive">
            	<table id="table" class="table table-hover" cellspacing="0">
                    <thead>
                        <tr>
                        	<th>No. </th>
                            <th>Nama Perimeter</th>
                            <th>Level</th>
                            <th>Region</th>
                            <th>Status</th>
                            <th>Persentase</th>
                  			    <th>Perimeter in Charger</th>
                            <th>Field Officer</th>
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
<script type="text/javascript">
$(document).ready(function() {
    $("#ExportExcel").on("click", function() {
        /*table.button( '.buttons-excel' ).trigger();*/
        $('.buttons-excel').click();
    });

    $("#ExportCsv").on("click", function() {
        //table.button( '.buttons-csv' ).trigger();
        $('.buttons-csv').click();
    });

    var group = '<?php echo $group;?>';
    var id=$('#group_company').val();
    if(group==1){
        $.ajax({
            url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=');?>"+id,
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
        $('#group_company').change(function(){
            var id=$(this).val();
            console.log(id);
            ///alert(id);
            $.ajax({
                url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=');?>"+id,
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
            return false;
        });
    }

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
                $("#progress-bar").hide();
	            return false;
	        });
	    });
	    dt.ajax.reload();
	};

	get_persenmonitoring();
  	get_cosmic_index();
	$('input[type=file]').val('');
	var company =  $('#company').val();
	var awal_week =  $('#week').val();
	var company_name = $("#company option:selected").text();
	var awal_week_name =  $("#week option:selected").text();
	var ttl = company_name+awal_week_name.trim();
  	var mc_id =  '<?php echo $mc_id; ?>';
  	console.log(mc_id);

	$('title').html('Monitoring '+ttl);
    table = $('#table').DataTable({
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": true,
        "searching": true,
        "dataSrc": "",
        "ajax": {
        	   "url": "<?php echo base_url().'histperimeter/ajax_list'?>/"+company+'/'+awal_week,
            "type": "POST"
        },
        "dom":  'Bfrtip',
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
        "columnDefs": [
              { data: 'no', name:'v_mpml_id', render: function (data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
                }
              },

            { "orderable": false, "targets": 0, "className": "text-right", },
            { "orderable": true, "targets": 1,  "className": "text-left", },
            { "orderable": true, "targets": 2,  "className": "text-left", },
            { "orderable": true, "targets": 3,  "className": "text-left", },
            { "orderable": true, "targets": 4,  "className": "text-right", },
            { "orderable": true, "targets": 5,  "className": "text-left", },
            { "orderable": true, "targets": 6,  "className": "text-left", },
            { "orderable": false, "targets": 7,  "className": "text-left", },
       ],
        // "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
        "lengthChange": false,
        "pageLength": 10
    })
});

function refresh_list() {
	get_persenmonitoring();
	get_cosmic_index();
	var company =  $('#company').val();
	var awal_week =  $('#week').val();
	var company_name = $("#company option:selected").text();
	var awal_week_name =  $("#week option:selected").text();
	var ttl = company_name+awal_week_name.trim();

	$('#mc_name').html(company_name);
	$('title').html('Monitoring '+ttl);
	var table = $('#table').DataTable({
	    "destroy": true,
        "cache":false,
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": true,
        "dataSrc": "",
        "ajax": {
            "url": "<?php echo base_url().'histperimeter/ajax_list'?>/"+company+'/'+awal_week,
            "type": "POST"
        },
        "lengthMenu": [[10, 100, -1], [10, 100, "All"]],
	    "pageLength": 10,
        "dom": 'Bfrtip',
        "buttons": [
            {
                "extend": 'csv',
                "text": '<i class="fa fa-file-text-o" style="color:green;"></i>&nbsp;&nbsp;CSV',
                "titleAttr": 'CSV',
                "className": 'hidden',
                "action": newexportaction,
                'exportOptions' : {
                    'modifier' : {
                        // DataTables core
                        'order' : 'index',  // 'current', 'applied', 'index',  'original'
                        'page' : 'all',      // 'all',     'current'
                        'search' : 'none'     // 'none',    'applied', 'removed'
                    }
                }
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
        "columnDefs": [
            { "orderable": false, "targets": 0, "className": "text-right", },
            { "orderable": false, "targets": 1,  "className": "text-left", },
            { "orderable": false, "targets": 2,  "className": "text-left", },
            { "orderable": false, "targets": 3,  "className": "text-left", },
            { "orderable": false, "targets": 4,  "className": "text-right", },
            { "orderable": false, "targets": 5,  "className": "text-left", },
            { "orderable": false, "targets": 6,  "className": "text-left", },
            { "orderable": false, "targets": 7,  "className": "text-left", },
       ],
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
        "pageLength": 10
  	});
	/*table.ajax.reload();
	table.draw();*/
}

function get_persenmonitoring(){
	var company =  $('#company').val();
	var awal_week =  $('#week').val();

    $.ajax({
        type: "GET",
        url: "<?php echo base_url().'histperimeter/get_persenmonitoring/'?>"+company+'/'+awal_week,
        dataType: "html",
        success: function(response){
           $("#pemenuhan_monitoring").html(response);
        }
    });
}

function clickDetail(){
  var company =  $('#company').val();
  window.open('<?php echo base_url(); ?>/histperimeter/rangkuman_grafik/'+company,'_self');
}

function get_cosmic_index(){
	var company =  $('#company').val();
	var awal_week =  $('#week').val();
  	console.log(awal_week);
  	$(".loader").fadeIn("slow");
    $.ajax({
        type: "GET",
        url: "<?php echo base_url().'histperimeter/ajax_get_cosmic_index/'?>"+company+'/'+awal_week,
        dataType: "html",
        success: function(response){
       
          var myData = JSON.parse(response);
          if(myData.length==0){
        	  var pg_cosmic_index = "0%";
              var pg_pemenuhan_protokol = "0%";
              var pg_pemenuhan_monitoring = "0%";
              var pg_pemenuhan_eviden = "0%";
          }else{
              var pg_cosmic_index = myData.cosmic_index+"%";
              var pg_pemenuhan_protokol = myData.pemenuhan_protokol+"%";
              var pg_pemenuhan_monitoring = myData.pemenuhan_ceklist_monitoring+"%";
              var pg_pemenuhan_eviden = myData.pemenuhan_eviden+"%";
          }
          $("#pg_cosmic_index").html(pg_cosmic_index);
          $("#ps_pemenuhan_standar").html(pg_pemenuhan_protokol);
          $("#ps_pemenuhan_monitoring").html(pg_pemenuhan_monitoring);
          $("#ps_pemenuhan_eviden").html(pg_pemenuhan_eviden);
          document.getElementById("pg_pemenuhan_standar").style.width = pg_pemenuhan_protokol;
          document.getElementById("pg_pemenuhan_monitoring").style.width = pg_pemenuhan_monitoring;
          document.getElementById("pg_pemenuhan_eviden").style.width = pg_pemenuhan_eviden;

          $(".loader").fadeOut("slow");
          console.log(pg_cosmic_index);
        }
    });
}

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
</script>
</body>
</html>
