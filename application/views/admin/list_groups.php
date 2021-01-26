<div class="container">
	<div class="rows"  style="padding-right:1%;">
		<div class="col-sm-12">
        <table id="table_groups" style="width:100%;overflow:auto; !important" 
        class="table table-striped table-bordered data">
            <thead>
                <tr>
					<th style="text-align:center;">No</th>
                    <th style="text-align:center;">Name</th>
                    <th style="text-align:center;">Description</th>
        			<th style="text-align:center;">Create</th>
        			<th style="text-align:center;">Read</th>
        			<th style="text-align:center;">Update</th>
        			<th style="text-align:center;">Delete</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
        </table>
        </div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var table_groups;
    table_groups = $('#table_groups').DataTable({ 
		"responsive": true,
        "processing": true, 
        "serverSide": true, 
        "ordering": true,
        "dataSrc": "",
        "ajax": {
            "url": "<?php echo site_url('admin/ajax_list_groups')?>",
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
          	 { "orderable": true, "targets": 0, "className": "text-right"},
           	 { "orderable": true, "targets": 1, "className": "text-left"},
           	 { "orderable": true, "targets": 2, "className": "text-center"},
           	 { "orderable": true, "targets": 3, "className": "text-center"},
         	 { "orderable": true, "targets": 4, "className": "text-left"},
           	 { "orderable": true, "targets": 5, "className": "text-center"},
           	 { "orderable": true, "targets": 6, "className": "text-center"},
       ],
    });
});
</script>