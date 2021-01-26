<?php header("Cache-Control: no-cache, must-revalidate");?>
<!DOCTYPE html>
<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>COSMIC</title>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/owl.carousel/owl.carousel.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css">
      <link rel="stylesheet" type="text/css" href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css">
    <style> 
      .modal-content-carousel{
        width: 900px;
      }
    </style>
    </head> 
<body>

    <div class="modal fade" id="modal_form_dashboard" role="dialog">
   
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Perbaharui Data</h5>
            </div>
            <div class="modal-body form">
               <div class="row">
                 <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                      <div class="card-header" style="justify-content: space-evenly">
                        <img style="width:50%" src="<?php echo base_url(); ?>assets/images/headModal.jpeg">
                      </div>
                       <input type="hidden" id="alert_val" value="0"/> 
                      <div class="card-body">
                        <div class="form-group">
                          <div class="input-group mb-2">
                           <span class="col-xs-12" id="alert_protokol"></span>
                           </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group mb-2">
                           <span class="col-xs-12" id="alert_sosialisasi"></span>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <span class="col-xs-12" id="alert_kasus"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- <style>.carousel-inner > .carousel-item > img { width:100%; height:270px; } </style> -->
    <div class="row">
      <div class="col-12 col-sm-12 col-lg-12">
      <div class="owl-carousel owl-theme">
      <!-- <div class="item">
        <img class="d-block w-100" src="<?php echo base_url(); ?>assets/images/BannerCosmic.png" alt="First slide">
      </div>
      <div class="item">
        <h4>2</h4>
      </div>
      <div class="item">
        <h4>3</h4>
      </div>
      <div class="item">
        <h4>4</h4>
      </div> -->
      <?php //$count=0;
        if($count==0){?>
          <div class="item">
            <img class="img-responsive d-block w-100" src="<?php echo base_url(); ?>assets/images/BannerCosmic.png" alt="First slide" style="height:300px;"><br><br>
            <div class="carousel-caption d-none d-md-block">
              <!-- <h5>Heading</h5> --><!-- 
              <p>Belum Ada Pengumuman</p> -->
            </div>
          </div>
         <?php }else{?>
    <?php 
        $indicators = ''; 
        $no=0;
        foreach($images as $image):
            $no++; 
               if ($no === 1) 
               { 
                  $class = 'active'; 
               }  
               else 
               { 
                  $class = ''; 
               }
        $indicators .= '<li data-target="#myCarousel" data-slide-to="' . $no . '" class="' . $class . '"></li>' ; 
        ?>
        <div style="cursor: pointer;" class="item" onclick="open_news(<?php echo $image->id?>)"> 
          <img class="img-responsive d-block w-100" src="<?=base_url()."uploads/pengumuman/".$image->file_image ?>" alt="<?php echo $image->judul?>" style="width:800px; height:300px;">
        
          <div class="carousel-caption d-none d-md-block">
            <h5>Heading</h5>
            <p><?php echo $image->judul?></p>
          </div>

        </div>
        <?php endforeach;
      }
        ?>                
    </div>
    </div>
  </div>
	<div id="dashboard_headbumn" class="row"></div>
    <div class="row">
        <div class="col-md-4">
          <div class="card card-hero">
            <div class="card-header">
              <div class="card-description"><h5><b>Status Upload Protokol</b></h5></div>
            </div>
            <div class="card-body p-0">
              <div class="tickets-list">
                <table id="tblbumn_protokol" class="display table-hover" cellspacing="0">
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
              <div class="card-description" style="white-space: nowrap"><h5><b>Jumlah Perimeter berdasarkan Region</b></h5></div>
            </div>
            <div class="card-body p-0">
              <div class="tickets-list">
                <table id="tblbumn_mrmpm" class="display table-hover" cellspacing="0">
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
              <div class="card-description"><h5><b>Overview data pegawai terdampak</b></h5></div>
            </div>
            <div class="card-body p-0">
              <div class="tickets-list">
                <table id="tblbumn_kasus" class="display table-hover" cellspacing="0">
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

    <div class="modal fade" id="modal_carousel" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content-carousel">
          <div class="modal-body form"></div>
          
        </div>
         
      </div>
    </div>
<script type="text/javascript">
var kd_perusahaan = '<?php echo $user->mc_id;?>';
$(document).ready(function() {

  $(".owl-carousel").owlCarousel({
    loop:true,
    autoplay: true,
    autoplayTimeout: 4000,
    navigation: true,
    items:1,
    autoHeight:true,
    margin:10,
    // autoplayHoverPause: true
  });

	cnt_protokol();
	cnt_sosialisasi();
	cnt_kasus();
    get_alert();
    $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashboard/ajax_dashboard_headbumn/'?>"+kd_perusahaan,     
        dataType: "html",               
        success: function(response){                    
           $("#dashboard_headbumn").html(response);
        }
    });

    tblbumn_protokol = $('#tblbumn_protokol').DataTable({ 
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
            "url": "<?php echo base_url().'dashboard/ajax_bumn_protokol'?>/"+kd_perusahaan,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });

    tblbumn_mrmpm = $('#tblbumn_mrmpm').DataTable({ 
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
            "url": "<?php echo base_url().'dashboard/ajax_bumn_mrpmpm'?>/"+kd_perusahaan,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });

    tblbumn_kasus = $('#tblbumn_kasus').DataTable({ 
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
            "url": "<?php echo base_url().'dashboard/ajax_bumn_kasus'?>/"+kd_perusahaan,
            "type": "POST"
        },
        "order": [],
        "columnDefs": [
            { "targets": 0, "orderable": true, "className": "text-left", },
            { "targets": 1, "orderable": true, "className": "text-right", },
        ]
    });
});

function open_news(id){
  block();
  $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashboard/open_news/'?>"+id,     
        dataType: "html",               
        success: function(response){
          unblock();
          $('#modal_carousel').modal('show');
          $('.modal-content-carousel').html(response);
        }
    });

    
    //var myurl='<?php echo base_url().'pengumuman/detail_pengumuman/'; ?>'+id;
    //window.location = myurl;
} 

function get_alert(){
    $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashboard/get_alert/'?>"+kd_perusahaan,     
        dataType: "html",               
        success: function(response){
        	$('#alert_val').val(JSON.parse(response).tot);                  
            if(JSON.parse(response).total==3){
            	$('#modal_form_dashboard').modal('hide');
            }else{
                $('#modal_form_dashboard').modal('show');
            }
        }
    });
}

function cnt_protokol(){
	 $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashboard/ajax_week_protokol/'?>"+kd_perusahaan,     
        dataType: "html",               
        success: function(response){          
            if(response!=""){
               $("#alert_protokol").html(response);
            }          
        }
    });
}

function link_protokol(){
	window.location.href='<?php echo base_url()."protokol"; ?>';
}

function link_protokol2(){
	if(confirm('Anda yakin tidak ada perubahan Protokol?')) {
		$("#alert_protokol").html('');
		perubahan_protokol();
	}else{
		cnt_protokol();
	}
}

function perubahan_protokol(){
    $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashboard/perubahan_week_protokol/'?>"+kd_perusahaan,     
        dataType: "json",               
        success: function(response){      
         	alert(response.message);
          	var alertval = parseInt($('#alert_val').val())-1;   
          	$('#alert_val').val(alertval);   
            if(alertval==0){
            	$('#modal_form_dashboard').modal('hide');
            }
        }
 	});
}

function cnt_sosialisasi(){
	 $.ajax({ 
       type: "GET",
       url: "<?php echo base_url().'dashboard/ajax_week_sosialisasi/'?>"+kd_perusahaan,     
       dataType: "html",               
       success: function(response){  
           if(response!=""){                  
              $("#alert_sosialisasi").html(response);
           }
       }
   });
}

function link_sosialisasi(){
	window.location.href='<?php echo base_url()."sosialisasi"; ?>';
}

function link_sosialisasi2(){
	if(confirm('Anda yakin tidak ada Kegiatan Perusahaan minggu ini?')) {
		$("#alert_sosialisasi").html('');
		perubahan_sosialisasi();
	}else{
		cnt_sosialisasi();
	}
}

function perubahan_sosialisasi(){
    $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashboard/perubahan_week_sosialisasi/'?>"+kd_perusahaan,     
        dataType: "json",               
        success: function(response){      
         	alert(response.message);
          	var alertval = parseInt($('#alert_val').val())-1;   
          	$('#alert_val').val(alertval);   
            if(alertval==0){
            	$('#modal_form_dashboard').modal('hide');
            }
        }
 	});
}

function cnt_kasus(){
	 $.ajax({ 
      type: "GET",
      url: "<?php echo base_url().'dashboard/ajax_week_kasus/'?>"+kd_perusahaan,     
      dataType: "html",               
      success: function(response){      
          if(response!=""){              
             $("#alert_kasus").html(response);
          }
      }
  });
}

function link_kasus(){
	window.location.href='<?php echo base_url()."kasus"; ?>';
}

function link_kasus2(){
	if(confirm('Anda yakin tidak ada Perubahan Data Pegawai Terdampak?')) {
		$("#alert_kasus").html('');
		perubahan_kasus();
	}else{
		cnt_kasus();
	}
}

function perubahan_kasus(){
    $.ajax({ 
        type: "GET",
        url: "<?php echo base_url().'dashboard/perubahan_week_kasus/'?>"+kd_perusahaan,     
        dataType: "json",               
        success: function(response){      
         	alert(response.message);
          	var alertval = parseInt($('#alert_val').val())-1;   
          	$('#alert_val').val(alertval);   
            if(alertval==0){
            	$('#modal_form_dashboard').modal('hide');
            }
        }
 	});
}
</script>
</body>
</html>
