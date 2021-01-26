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
    <div class="col-12">
        <div class="search col-6">
      <select name="status" class="status form-control">
                <option value="0">-Semua-</option>
                <option value="2">-Menunggu Persetujuan-</option>
                <option value="1">-Disetujui-</option>
                <option value="3">-Dalam Proses-</option>
                <option value="4">-Ditolak-</option>
            </select>
        </div>
        <div class="table-responsive">
        <table id="table_company" class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Tanggal Buat</th>
                    <th>Tanggal Diverifikasi</th>
                    <th>Status</th>
                    <th>Diproses Oleh</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
  </div>
</div>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        var status='0';
        refresh_tabel(status);
        get_list_card();

         $(".status").change(function(){
           var status = this.value;
           refresh_tabel(status); 
         })

    });

function refresh_tabel(status){
    table = $('#table_company').DataTable({ 
            "destroy": true,
            "responsive": true,
            "processing": true, 
            "serverSide": true, 
            //"ordering": false,
            "ajax": {
                "url": "<?php echo site_url('sertifikasi/get_all_company')?>/"+status,
                "type": "POST"
            },
             "lengthMenu": [[10, 100, -1], [10, 100, "All"]],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": true, "targets": 0, "className": "text-right", },
                    { "orderable": true, "targets": 1,  "className": "text-left", },
                    { "orderable": true, "targets": 2,  "className": "text-left", },
                    { "orderable": true, "targets": 3,  "className": "text-left", },
                    { "orderable": true, "targets": 4,  "className": "text-right", },
                    { "orderable": true, "targets": 5,  "className": "text-right", },
                    {  "orderable": true, "targets": 6,  "className": "text-right", },
                    { "visible": false, "targets": 7,  "className": "text-right hidden", },
                   
               ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
                "pageLength": 10
        });

        $('#table_company tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            block();
            window.location.href="<?php echo site_url('sertifikasi/detail_sertifikasi')?>/"+data[7];
        });
}

function get_list_card(){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>sertifikasi/ajax_list_card_partner",
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
</script>