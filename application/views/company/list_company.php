<div class="container" style="width: 99%;background-color:#fff">
    <table id="table_company" class="display" class="table table-striped table-bordered data">
        <thead>
            <tr>
                <th>No</th>
                <th>Id</th>
                <th>Kode</th>
                <th>Nama Lengkap</th>
                <th>Nama Singkat</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot></tfoot>
    </table>
</div>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#table_company').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "ordering": false,
            "ajax": {
                "url": "<?php echo site_url('Company/get_company')?>",
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], 
                    "orderable": false, 
                },
            ],
        });
    });
</script>