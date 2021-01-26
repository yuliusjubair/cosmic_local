<!DOCTYPE html>
<html>
<head>
	<title>Cara Menggunakan Datatables | Malas Ngoding</title>	
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/new-datatables/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/new-datatables/buttons.dataTables.min.css" />

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/bootstrap-tag.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.flash.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/new-datatables/buttons.print.min.js"></script>
</head>
<body>
	<center>
		<h1>Menampilkan data dengan datatables | Malas Ngoding</h1>
	</center>
	<br/>
	<br/>
	<div class="container">
		<table class="table table-striped table-bordered data">
			<thead>
				<tr>			
					<th>Nama</th>
					<th>Alamat</th>
					<th>Pekerjaan</th>
					<th>Usia</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Malas Ngoding</td>
					<td>Bandung</td>
					<td>Web Developer</td>
					<td>26</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Malas Ngoding</td>
					<td>Bandung</td>
					<td>Web Developer</td>
					<td>26</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
				<tr>				
					<td>Andi</td>
					<td>Jakarta</td>
					<td>Web Designer</td>
					<td>21</td>
					<td>Aktif</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		$('.data').DataTable({
		     dom: 'Bfrtip',
		        buttons: [
		            'copy', 'csv', 'excel', 'pdf', 'print'
		        ]
		});
	});
</script>
</html>