<style type="text/css">
     .sorting_disabled { display: none; }
     .dataTables_length { display: none; }

    table#table_company.dataTable tbody tr:hover {
      background-color: #ffa;
      cursor: pointer;
    }
     
    table#table_company.dataTable tbody tr:hover > .sorting_1 {
      background-color: #ffa;
      cursor: pointer;
    }

</style>
    <div class="row" id="list_card"></div>
<div class="row">
    <div class="table-responsive">
        <div class="search col-6">
            <!-- <button name="belum" id="belum" class="btn btn-primary">Perusahaan Mendaftar</button>
            <button name="sudah" id="sudah" class="btn btn-primary">Perusahaan disetujui/dalam proses</button> -->
            <select name="status" class="status form-control">
                <option value="0">-Semua-</option>
                <option value="2">-Menunggu Persetujuan-</option>
                <option value="1">-Disetujui-</option>
                <option value="3">-Dalam Proses-</option>
                <option value="4">-Ditolak-</option>
            </select>
        </div>
        <table id="table_company" class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Tanggal Buat</th>
                    <th>Tanggal Diverifikasi</th>
                    <th>Status</th>
                    <th>Diproses Oleh</th>
                    <!-- <th>Estimasi</th> -->
                    <!-- <th>&nbsp;</th> -->
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        get_list_card();
        var status='0';
        refresh_tabel(status);

         $(".status").click(function(){
           var status = this.value;
           refresh_tabel(status); 
         })

        /* $("#sudah").click(function(){
           $(this).prop("disabled","disabled"); 
           $('#belum').prop("disabled",false);
           refresh_tabel('sudah'); 
         })*/
    });

function get_list_card(){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>dashatestasi/ajax_list_card_partner",
        dataType: "html",
        beforeSend: function(e) {
            if(e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function(response){
            $("#list_card").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

function refresh_tabel(status){
    table = $('#table_company').DataTable({ 
            "destroy": true,
            "responsive": true,
            "processing": true, 
            "serverSide": true, 
            //"ordering": false,
            "ajax": {
                "url": "<?php echo site_url('dashatestasi/get_all_company')?>/"+status,
                "type": "POST"
            },
             "lengthMenu": [[10, 100, -1], [10, 100, "All"]],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": true, "targets": 0, "className": "text-right", },
                    { "orderable": true, "targets": 1,  "className": "text-left", },
                    { "orderable": true, "targets": 2,  "className": "text-left", },
                    { "orderable": true, "targets": 3,  "className": "text-left", },
                    { "orderable": true, "targets": 4,  "className": "text-left", },
                    // { "orderable": true, "targets": 4,  "className": "text-right", },
                    { "visible": false, "targets": 5,  "className": "text-right hidden", },
                    { "targets": 6,  "className": "text-right", },
                   
               ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
                "pageLength": 10
        });

        $('#table_company tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            block();
            window.location.href="<?php echo site_url('partner')?>/"+data[7];
        });
}
</script>