<?php header("Cache-Control: no-cache, must-revalidate");?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js">
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js">
</script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js">
</script>
<style>
  .sorting_disabled {
    display: none;
  }
  .dataTables_length {
    display: none;
  }

  ul li a span {
  	color: #000000;
  	font-weight: 600;
  	font-size: 14px;
  }

  .total_title {
  	color: #676767;
  	font-weight: 600;
  	font-size: 14px;
  	line-height: 5px;
  }

  .sub-title-cpu {
  	color: #969696;
  	font-size: 12px;
  	font-weight: 400;
  }

  .value-cpu {
  	color: #969696;
  	font-size: 14px;
  	font-weight: bold;
  }

  .percent-cpu {
  	color: #235797;
  	font-size: 14px;
  	font-weight: bold;
  	line-height: 0;
  	width:40px;
	text-align: right;
  }

  .title-cpu {
  	color: #676767;
  	font-size: 14px;
  	font-weight: bold;
  }

  .progress {
  	width: 280px;
  }

  .card-statistic-1{padding-bottom:10px;}

  #container {
  height: 400px; 
}

.highcharts-figure, .highcharts-data-table table {
  min-width: 310px; 
  max-width: 800px;
  margin: 1em auto;
}

.highcharts-data-table table {
  font-family: Verdana, sans-serif;
  border-collapse: collapse;
  border: 1px solid #EBEBEB;
  margin: 10px auto;
  text-align: center;
  width: 100%;
  max-width: 500px;
}
.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
  padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}

.collapse {
	visibility: visible;
}


  /* .row-flex {
  display: flex;
  flex-wrap: wrap;
  }*/
  /*.content {
  height: 100%;
  padding: 20px 25px 10px;
  }*/
</style>
<!-- <?php
echo "<pre>";
print_r($haloo);
echo "</pre>";
?> -->
<?php
// for ($i=0; $i < 14; $i++) { 
// 	$date = new DateTime(date('Y-m-d H:i:s'));
// 	$minus = 13 - $i;
// 	$date->modify('-'.$minus.' day');
// 	echo $date->format('Y-m-d')."<br>";
// }

?>
<button class="btn btn-primary mb-3 align-items-center">
	<img src="<?php echo base_url(); ?>assets/images/reload.png"/>
	Refresh Data
</button>
<div class="row">
	<div class="col">
		<div class="card card-statistic-1">
			<div class="card-wrap">
				<div class="card-header pt-3">
					<p class="total_title">Total User Login </p>
					<p class="total_title">Saat Ini</p>
				</div>
				<div class="card-body">
					<b><?php echo $total_user; ?></b>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card card-statistic-1">
			<div class="card-wrap">
				<div class="card-header pt-3">
					<p class="total_title">Total BUMN Login </p>
					<p class="total_title">Saat Inii</p>
				</div>
				<div class="card-body">
					<b><?php echo $total_bumn; ?></b>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card card-statistic-1">
			<div class="card-wrap">
				<div class="card-header pt-3">
					<p class="total_title">Total PIC Login </p>
					<p class="total_title">Saat Ini</p>
				</div>
				<div class="card-body">
					<b><?php echo $total_fo; ?></b>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card card-statistic-1">
			<div class="card-wrap">
				<div class="card-header pt-3">
					<p class="total_title">Total FO Login </p>
					<p class="total_title">Saat Ini</p>
				</div>
				<div class="card-body">
					<b><?php echo $total_pic; ?></b>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card card-statistic-1">
			<div class="card-wrap">
				<div class="card-header pt-3">
					<p class="total_title">Lama Session </p>
					<p class="total_title">yang terjadi</p>
				</div>
				<div class="card-body">
					<b><?php echo $time_session; ?></b>
				</div>
			</div>
		</div>
	</div>
</div>

<iframe src="<?php echo base_url(); ?>chart" width="100%" height="500px;" scrolling="no" frameBorder="0">Browser not compatible.</iframe>
<div class="col-md-6">
	<div class="card card-statistic-1">
		<div class="card-wrap">
			<div class="card-body pt-4">
				<div class="row justify-content-between">
					<p class="title-cpu">Main Server <span class="sub-title-cpu">(<?php echo $ip; ?>)</span></p>
					<p>Usage: <?php echo $cpu; ?>% <img data-toggle="collapse" data-target=".hide1" src="<?php echo base_url(); ?>assets/images/arrow-down.png"/></p>
				</div>
				<div class="hide1 collapse show">
					<div class="row justify-content-between">
						<p>CPU 1</p>
						<div class="d-flex flex-row align-items-center">
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $cpu; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $cpu; ?>%">
								  <?php echo $cpu; ?>%
								</div>
							</div>
							<p class="percent-cpu"><?php echo $cpu; ?>%</p>					
						</div>
					</div>			
					<div class="row justify-content-between">
						<div>
							<p>Disk Usage</p>
							<p class="value-cpu"><?php echo $disk_usage.'/'.$disk_total; ?></p>
						</div>
						<div class="d-flex flex-row align-items-center">
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo intval($disk_usage[2]); ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo intval($disk_free); ?>%">
								  <?php echo intval($disk_free); ?>%
								</div>
							</div>
							<p class="percent-cpu"><?php echo intval($disk_usage[2]); ?>%</p>
						</div>				
					</div>
					<div class="row justify-content-between">
						<div>
							<p class="mt-2">Memory Usage</p>
							<p class="value-cpu"><?php echo intval($memory_usage[0]).'/'.intval($memory_usage[1]); ?></p>
						</div>
						<div class="d-flex flex-row align-items-center">
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo intval($memory_usage[2]); ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo intval($memory_usage[2]); ?>%">
								  <?php echo intval($memory_usage[2]); ?>%
								</div>
							</div>
							<p class="percent-cpu"><?php echo intval($memory_usage[2]); ?>%</p>
						</div>					
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;
          </span>
        </button>
        <h5 class="modal-title">Download File
        </h5>
      </div>
      <div class="modal-body form">
        <div class="text-center">
          <button onclick="loader_rangkuman()" class="btn btn-primary" type="button" id="ExportExcel">
            <i class="fa fa-file-text-o">
            </i>&nbsp;Format Excel
          </button>
          <button onclick="loader_rangkuman()" class="btn btn-primary" type="button" id="ExportCsv">
            <i class="fa fa-file-text-o">
            </i>&nbsp;Format CSV
          </button>
          <div id="progress-bar" style="display:none;margin-top: 11px;">
            <img src="<?php echo base_url(); ?>assets/images/busy.gif"/>&nbsp;Waiting For Download File...
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- <script src="https://code.highcharts.com/modules/export-data.js"></script> -->
<!-- <script src="https://code.highcharts.com/modules/accessibility.js"></script> -->
