<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('url');
$this->session->set_userdata('redirect_url', current_url());
?>
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBf6TTTbkunYoyZCCfbQxkCDZIhMzJNuuQ&callback=initMap"
  	type="text/javascript"></script> -->
    <script type="text/javascript">
//     function initialize(longi,lati) {
//     	var propertiPeta = {
//     	    center:new google.maps.LatLng(longi,lati),
//     	    zoom:20,
//     	    mapTypeId:google.maps.MapTypeId.ROADMAP
//     	};

//     	var peta = new google.maps.Map(document.getElementById("googleMap"), propertiPeta);

//         var marker=new google.maps.Marker({
//             position: new google.maps.LatLng(longi,lati),
//             map: peta,
//             animation: google.maps.Animation.BOUNCE
//         });
//     }
//     google.maps.event.addDomListener(window, 'load', initialize);
    </script>

<div class="panel panel-primary" style="display: none">
 	<div class="panel-heading"><b>Perimeter Detail</b></div>
	<div class="panel-body">
		<div class="row" >

            <div class="card">
              <div class="container">
                <h4><b><div class="namanya"></div></b></h4>
                <p><div class="alamatnya"></div></p>
                <p><div class="kategorinya"></div></p>

              </div>
            </div>
            <input type="hidden" class="form-control" id="mpm_id" name="mpm_id"
                value="<?php echo $mpm_id;?>" readonly/>
            <input type="hidden" class="form-control" id="company" name="company"
                   value="<?php echo $mc_id;?>" readonly/>
                <input type="hidden" class="form-control" id="nama" name="nama"
                    value="" readonly/>
                <input type="hidden" class="form-control" id="alamat" name="alamat"
                    value="" readonly/>
                <input type="hidden" class="form-control" id="kategori" name="kategori"
                    value="" readonly/>
<!--            	<div class="col-sm-12 form-group"> -->
<!--            		<label class="col-sm-2 control-label">Lokasi :</label> -->
<!--     			<div class="col-sm-10"> -->
<!--           	    <div id="googleMap" style="width:100%;height:420px;"></div> -->
<!--             	</div>  -->
<!--            	</div> -->
		</div>
	</div>
</div>
<div class="row">
    <div class="col-10">
     <div class="card">
      <div class="card-body">
        <h5><b><div class="namanya">-</div></b></h5>
        <p><h6><div class="alamatnya">-</div></h6></p>
        <p><div class="region">-</div></p>
        <p><div class="kategorinya">-</div></p>
      </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-lg-12 pull-right form-group">
  <button type="button" class="btn btn-primary btn-md"
   	formtarget='_self' onclick="download_qr('<?php echo $mpm_id; ?>')">
  	<i class="fa fa-download"></i>Download QRCODE Perimeter
  </button>
    <button class='btn btn-success' type='button'
      onclick="tambah_perimeter()" ><span class='white' ><i class='ace-icon fa fa-plus-circle '>
    </i> Tambah Level </span></button>
</div>
</div>

    <div class="row">
	<div class="col-12">
	   <div class="card">
 	      <div class="card-body">
            <div class="table-responsive">
            <table id="tbl_perimeter_level" class="table table-hover">
                <thead>
                    <tr>
                    	<th>No</th>
                    	<th>Level</th>
                    	<th>NIK PIC</th>
                    	<th>PIC</th>
                    	<th>NIK FO</th>
                    	<th>FO</th>
                    	<th>Cluster Ruangan</th>
                    	<th>Aksi</th>
                    </tr>
                </thead>
                <tbody >
                </tbody>
            </table>
            </div>
        </div>
	</div>
</div>
</div>

<button type="button" class="btn btn-danger btn-xs" onclick="location.href='<?php echo base_url()."mperimeter"; ?>';">
    <i class="fa fa-close"></i> Close
</button>
</div>
<?php //var_dump(base_url());die; ?>
<script type="text/javascript">
function download_qr(id) {
	window.location = "<?php echo base_url()."Printqrcode/pdfqrcode/".$mpm_id; ?>";
}


$(document).ready(function() {

    $("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();
    });

    $("#ExportCsv").on("click", function() {
        //tbl_perimeter_level.button( '.buttons-csv' ).trigger();
        $('.buttons-excel').click();
    });

    $("#btnPIC").on("click", function(){
      window.open('<?php echo site_url('profile/picfo');?>', '_blank');
    });
    $("#btnFO").on("click", function(){
      window.open('<?php echo site_url('profile/picfo');?> ','_blank');
    });
});
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

$(document).ready(function(){
	var mpm_id = $('#mpm_id').val();
	var mc_id = $('#mc_id').val();
    $.ajax({
        type: "GET",
        url: "<?php echo base_url().'mperimeter/ajax_perimeter_detail/'?>"+mpm_id,
        dataType: "json",
        success: function(response){
            //console.log(response);
            if(response['status']==200){
				$('#nama').val(response['data'][0]['nama_perimeter']);
				$('#alamat').val(response['data'][0]['alamat']);
				$('#kategori').val(response['data'][0]['kategori']);

                $('.namanya').text(response['data'][0]['nama_perimeter']);
                $('.alamatnya').text(response['data'][0]['alamat']);
                $('.kategorinya').text('Kategori : ' + response['data'][0]['kategori']);
                $('.region').text(response['data'][0]['region']);

				$('#foto').val(response['data'][0]['foto']);

		    	var longi = response['data'][0]['longitude'];
		    	var lati = response['data'][0]['latitude'];

		    	if(longi==''){
		    		longi = -6.1818771;
			    }
		    	if(lati==''){
		    		lati = 106.8253895;
			    }

		    	var images = '';
		    	var url = "<?php echo base_url()?>";
				images = '<img src="'+url+response['data'][0]['file']+'" width="200px"/>';
		    	document.getElementById( 'yourcontainer' ).innerHTML = images;
		    	initialize(longi,lati) ;
            }else{
				alert('Data Not Found');
                return false;
            }
        }
    });
    $('title').html('List Perimeter Detail');
	tbl_perimeter_level = $('#tbl_perimeter_level').DataTable({
        "bDestroy": true,
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "paging": false,
        "searching": true,
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
            "url": "<?php echo base_url().'mperimeter/ajaxperimeterlevel_list'?>/"+mpm_id,
            "type": "POST"
        },
        "columnDefs": [
            { "orderable": false, "targets": 0, "className": "text-right", },
            { "orderable": false, "targets": 1,  "className": "text-left", },
            { "orderable": false, "targets": 2,  "className": "text-left", },
            { "orderable": false, "targets": 3,  "className": "text-left", },
            { "orderable": false, "targets": 4,  "className": "text-left", },
            { "orderable": false, "targets": 5,  "className": "text-left", },
            { "orderable": false, "targets": 6,  "className": "text-left", },
            { "orderable": false, "targets": 7,  "className": "text-left", },
       ],
    });
});

function delete_perimeterlevel(id) {
	if(confirm('Yakin melakukan penghapusan ?')) {
        $.ajax({
            url : "<?php echo site_url('mperimeter/ajax_cekaktivitas')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
				if(data.cnt > 0){
					if(confirm('Perimeter telah memiliki aktivitas. Penghapusan akan berdampak pada pelaporan dari perusahaan.')) {
						$.ajax({
			                url : "<?php echo site_url('mperimeter/ajax_delete')?>/"+id,
			                type: "POST",
			                dataType: "JSON",
			                success: function(data) {
			                 	alert(data.message);
			                    refresh_list();
			                },
			                error: function (jqXHR, textStatus, errorThrown) {
			                	 alert(data.message);
			                }
			            });
					}
				}else{
					$.ajax({
		                url : "<?php echo site_url('mperimeter/ajax_delete')?>/"+id,
		                type: "POST",
		                dataType: "JSON",
		                success: function(data) {
		                 	alert(data.message);
		                    refresh_list();
		                },
		                error: function (jqXHR, textStatus, errorThrown) {
		                	 alert(data.message);
		                }
		            });
				}
            },
            error: function (jqXHR, textStatus, errorThrown) {
             alert(data.message);
            }
        });
	}
}

function refresh_list() {
	var mpm_id = $('#mpm_id').val();
	tbl_perimeter_level = $('#tbl_perimeter_level').DataTable({
		"bDestroy": true,
		"responsive": true,
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "dataSrc": "",
        "bPaginate": false,
        "searching": true,
        "paging": false,
        "info": false,
        "dom": 'Bfrtip',
        "ajax": {
            "url": "<?php echo base_url().'mperimeter/ajaxperimeterlevel_list'?>/"+mpm_id,
            "type": "POST"
        },
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
            { "orderable": false, "targets": 0, "className": "text-right", },
            { "orderable": false, "targets": 1,  "className": "text-left", },
            { "orderable": false, "targets": 2,  "className": "text-left", },
            { "orderable": false, "targets": 3,  "className": "text-left", },
            { "orderable": false, "targets": 4,  "className": "text-left", },
            { "orderable": false, "targets": 5,  "className": "text-left", },
            { "orderable": false, "targets": 6,  "className": "text-left", },
            { "orderable": false, "targets": 7,  "className": "text-left", },
       ],
    });
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}


function edit_perimeter(id_perimeter_level) {
    save_method = 'update';
    $('#form_modal1')[0].reset();
    $("#region-group").css('visibility', 'visible');
    $("#perimeter-group").css('visibility', 'visible');
    $.ajax({
        url : "<?php echo site_url('mperimeter/ajaxperimeterlevel_detail')?>/"+id_perimeter_level,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            //console.log(data.data.pic);
            $('[name="id_perimeter_level"]').val(data.data.id_perimeter_level);
            $('[name="region"]').val(data.data.region);

            $('[name="id_kategori_perimeter"]').val(data.data.id_kategori);
            $('[name="perimeter"]').val(data.data.nama_perimeter);
            $('[name="level"]').val(data.data.level);
            $('[name="keterangan"]').val(data.data.keterangan);
            $('[name="pic"]').val(data.data.nik_pic);
            $('[name="fo"]').val(data.data.nik_fo);
            for (i = 1; i <= 23; i++) {
                for (x = 0; x < (data.data.cluster.length); x++) {
                    if (data.data.cluster[x].id_cluster_ruangan == i) {
                        $('[name="cl' + (i) + '"]').val(data.data.cluster[x].cluster_ruangan);
                        $('[name="jml' + (i) + '"]').val(data.data.cluster[x].jumlah);
                    }
                }
            }


            $('#modal_perimeter').modal('show');
            $('.modal-title').text('Edit Perimeter');
            refresh_list();
        },
        error: function (jqXHR, textStatus, errorThrown)  {
            alert('Error get data from ajax');
        }
    });
}

function save() {
    //var nama = $('#nama').val();
    //var tgl = $('#tgl').val();

    $('#btnSave').text('Saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    //if(tgl > tgl_now){
    //    alert('Tgl tidak boleh lebih dari hari ini');
    //}

    if(save_method == 'update') {
      url = "<?php echo site_url('mperimeter/ajaxperimeterlevel_update')?>";
    } else {
      url = "<?php echo site_url('mperimeter/ajaxperimeterlevel_add')?>";
    }

    var formData = new FormData($('#form_modal1')[0]);

    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data) {
            //console.log(data.status);

            if(data.status) {
                alert(data.message);
                $('#modal_perimeter').modal('hide');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
                $('#form_modal1')[0].reset();
                //reset_kabupaten(1);
                refresh_list();

            } else {
              console.log(data);
                //for (var i = 0; i < data.length; i++) {
              //      $('[name="'+data[i]+'"]').parent().parent().addClass('has-error');
              //      $('[name="'+data[i]+'"]').next().text(data.error_string[i]);
              //  }
                alert('Data masih ada yang kosong');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
            cancel();
        }
    });
}

function tambah_perimeter() {
    save_method = 'add';
    perimeter_id = "<?php echo $mpm_id;?>";
    console.log(perimeter_id);
    $('[name="id_perimeter"]').val(perimeter_id);
    $("#region-group").hide();
    $("#perimeter-group").hide();
    $('#form_modal1')[0].reset();
    $( "#x" ).prop( "disabled", false );
    $('#modal_perimeter').modal('show');
    $('.modal-title').text('Tambah Perimeter');
    refresh_list();

}
</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Download File</h5>
            </div>
            <div class="modal-body form">
                <div class="text-center">
                    <button onclick="loader()" class="btn btn-primary" type="button" id="ExportExcel"><i class="fa fa-file-text-o"></i>&nbsp;Format Excel</button>

                    <button onclick="loader()" class="btn btn-primary" type="button" id="ExportCsv"><i class="fa fa-file-text-o"></i>&nbsp;Format CSV</button>

                     <div id="progress-bar" style="display:none;margin-top: 11px;">
	                    <img src="<?php echo base_url(); ?>assets/images/busy.gif"/>&nbsp;Waiting For Download File...
	                </div>

                </div>



            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade " id="modal_perimeter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Edit Perimeter</h5>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_modal1" class="form-horizontal">
                    <input type="hidden" value="0" name="id_perimeter_level"/>
                    <input type="hidden" value="0" name="id_perimeter"/>
                    <input type="hidden" value="" id="id_perimeter_level_modal" name="id_perimeter_level_modal"/>
                    <div  class="form-body">
                        <div id="region-group" class="form-group">
                            <label class="control-label col-sm-4">Region</label>
                            <div class="col-sm-12">
                                <input id="region" name="region" placeholder="Region" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div id="perimeter-group"  class="form-group">
                            <label class="control-label col-sm-4">Perimeter</label>
                            <div class="col-sm-12">
                                <input id="perimeter" name="perimeter" placeholder="Perimeter" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Level</label>
                            <div class="col-sm-12">
                                <input id="level" name="level" placeholder="Level" class="form-control" type="text" >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Keterangan<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input id="keterangan" name="keterangan" placeholder="Keterangan" class="form-control" type="text" required>
                                <input id="id_kategori_perimeter" name="id_kategori_perimeter" placeholder="id_kategori_perimeter" class="form-control" type="hidden">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Perimeter in Charge<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                              <div class="row">
                                <div class="col-sm-9">
                                <select name="pic" id="pic" class="form-control" >
                                    <?php foreach ($pic as $itempic){ ?>
                                        <option value="<?php echo $itempic->username; ?>"><?php echo $itempic->first_name; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                                <div class="col-sm-1">
                                <button type="button" class="btn btn-lg btn-primary" id="btnPIC"  ><i class="fa fa-plus"></i></button>
                                </div>
                                <span class="help-block"></span>
                              </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Field Officer<span style="color:red">*</span></label>
                            <div class="col-sm-12">
                              <div class="row">
                                <div class="col-sm-9">
                                <select name="fo" id="fo" class="form-control" >
                                    <?php foreach ($fo as $itemfo){ ?>
                                        <option value="<?php echo $itemfo->username; ?>"><?php echo $itemfo->first_name; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                                <div class="col-sm-1">
                                <button type="button" class="btn btn-lg btn-primary" id="btnFO"  ><i class="fa fa-plus"></i></button>
                                </div>
                                <span class="help-block"></span>
                              </div>
                            </div>
                        </div>

                      <div class="container">
                        <?php for ($x = 1; $x <= 23; $x++) { ?>

                        <div name="groupcl<?php echo $x; ?>" class="form-group">


                                    <?php foreach ($cluster_ruangan as $cr){
                                        if( $cr->mcr_id == $x) { ?>
                                            <input id="idcl<?php echo $x; ?>" name="idcl<?php echo $x; ?>"  class="form-control" type="hidden" value=<?php echo strval($cr->mcr_id);?> >
                                    <!--input type="text" name="cl<?php echo $x; ?>" readonly class="form-control-plaintext col-lg-12"  value=<?php echo strval($cr->mcr_name);?> -->

                                              <div class="row">
                                                <div class="col">
                                                  <label>
                                                        <?php echo strval($cr->mcr_name);?>
                                                    </label>
                                                </div>
                                                <div class="col">
                                                  <input class="form-control" type="number" id="jml<?php echo $x; ?>" name="jml<?php echo $x; ?>" value="0" min="0" max="50" step="1"/>
                                                </div>
                                              </div>
                                        <?php }
                                    } ?>
                            </div>
                        <?php } ?>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-primary" id="btnSave" onclick="save()" >Save</button>
                <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal" onclick="cancel()">Cancel</button>
            </div>
        </div>
    </div>
</div>
</div>
