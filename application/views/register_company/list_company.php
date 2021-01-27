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
<div class="row">
    <div class="col-md-4">
      <div class="card card-hero">
           <div class="card-header">
     
              <div class="card-description">Jumlah Perusahaan Terdaftar per<br/> Provinsi 
                <!-- <span class="pull-right" style="font-size:12px !important;">
                    <a href="<?php echo base_url(); ?>dashmin/all_kategori_provinsi" class="link"> Lihat Semua</a>
                </span> -->
                </div>
            </div>

        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_perusahaanbyprovinsi" class="display table table-bordered" cellspacing="0">
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
         
                  <div class="card-description">Jumlah Perusahaan Terdaftar per <br/>Industri
                    <!-- <span class="pull-right" style="font-size:12px !important;">
                        <a href="<?php echo base_url(); ?>dashmin/all_kategori_index" class="link"> Lihat Semua</a>
                    </span> -->
                    </div>
                </div>

        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_perusahaanbyindustri" class="display table table-bordered" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody >
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-hero">
        <div class="card-header">
         
          <div class="card-description">Jumlah Pegawai per Perusahaan <br/>Terdaftar
            <!-- <span class="pull-right" style="font-size:12px !important;">
                <a href="<?php echo base_url(); ?>dashmin/all_kategori_perimeter" class="link"> Lihat Semua</a>
            </span> -->
        </div>
        </div>
        <div class="card-body p-0">
          <div class="tickets-list">
            <table id="tbl_pegawai" class="display table table-bordered" cellspacing="0">
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
<div class="row">
    <div class="table-responsive">
        <div class="search">
            <button name="all" id="all" class="btn btn-primary">All</button>
            <button name="belum" id="belum" class="btn btn-primary">Belum Terverifikasi</button>
            <button name="sudah" id="sudah" class="btn btn-primary">Verifikasi</button>
        </div>
        <table id="table_company" class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perusahaan</th>
                    <th>Provinsi</th>
                    <th>Tanggal Submit</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
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
        table = $('#table_company').DataTable({ 
            "responsive": true,
            "processing": true, 
            "serverSide": true, 
            //"ordering": false,
            "ajax": {
                "url": "<?php echo site_url('RegisterCompany/get_company')?>",
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
                    { "visible": false, "targets": 6,  "className": "text-right hidden", },
                    { "targets": 5,  "className": "text-right", },
                   
               ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
                "pageLength": 10
        });

        $('#table_company tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            block();
            window.location.href="<?php echo site_url('RegisterCompany/detail')?>/"+data[6];
        });

        $('#tbl_perusahaanbyprovinsi').DataTable({ 
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
                "url": "<?php echo base_url().'RegisterCompany/ajax_perusahaan_byprovinsi_all'?>/",
                "type": "POST"
            },
            "order": [],
            "columnDefs": [
                { "targets": 0, "orderable": false, "className": "text-left", },
                { "targets": 1, "orderable": false, "className": "text-right", },
            ],
            drawCallback:function(settings)
            {
             $('.total_provinsi').html(settings.json.recordsTotal+" Unit");
            }
        });

        $('#tbl_perusahaanbyindustri').DataTable({ 
            "responsive": true,
            "processing": true, 
            //"serverSide": true, 
            "ordering": false,
            "dataSrc": "",
            "bPaginate": false,
            "searching": false, 
            "paging": false, 
            "info": false,
            "scrollY": '50vh',
            "scrollCollapse": true,
            "ajax": {
                "url": "<?php echo base_url().'RegisterCompany/ajax_perusahaan_byindustri_all'?>/",
                "type": "POST"
            },
            //"order": [],
            "lengthMenu": [20, 40, 60, 80, 100],
            "pageLength": 20,
            /*"columnDefs": [
                { "targets": 0, "orderable": false, "className": "text-left", },
                { "targets": 1, "orderable": false, "className": "text-right", },
            ],
            drawCallback:function(settings)
            {
             $('.total_provinsi').html(settings.json.recordsTotal+" Unit");
            }*/
        });

         $('#tbl_pegawai').DataTable({ 
            //"responsive": true,
            "processing": true, 
            "serverSide": true, 
            //"ordering": false,
            "dataSrc": "",
            "bPaginate": false,
            "searching": false, 
            //"paging": false, 
            "scrollY": '50vh',
            //"scrollCollapse": true,
            //"order": [[ 2, "desc" ]],
            "lengthMenu": [20, 40, 60, 80, 100],
            "pageLength": 10,
            //"sDom": "lfrti",
            "info": false,
            "ajax": {
                "url": "<?php echo base_url().'RegisterCompany/ajax_perusahaan_bypegawai'?>/",
                "type": "POST"
            },
            "order": [],
            "columnDefs": [
                { "targets": 0, "orderable": false, "className": "text-left", },
                { "targets": 1, "orderable": false, "className": "text-right", },
            ]
        });

         $("#all").click(function(){
           $(this).prop("disabled","disabled"); 
           $('#belum').prop("disabled",false);
           $('#sudah').prop("disabled",false);
           refresh_tabel('all'); 
         })

         $("#belum").click(function(){
           $(this).prop("disabled","disabled"); 
           $('#all').prop("disabled",false);
           $('#sudah').prop("disabled",false);
           refresh_tabel('belum'); 
         })

         $("#sudah").click(function(){
           $(this).prop("disabled","disabled"); 
           $('#belum').prop("disabled",false);
           $('#all').prop("disabled",false);
           refresh_tabel('sudah'); 
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
                "url": "<?php echo site_url('RegisterCompany/get_company')?>/"+status,
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
                    { "visible": false, "targets": 6,  "className": "text-right hidden", },
                    { "targets": 5,  "className": "text-right", },
                   
               ],
                "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Todos"] ],
                "pageLength": 10
        });

        $('#table_company tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            block();
            window.location.href="<?php echo site_url('RegisterCompany/detail')?>/"+data[6];
        });
}
</script>