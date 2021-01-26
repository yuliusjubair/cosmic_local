<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/css/chocolat.css">
<script src="<?php echo base_url(); ?>assets_stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/bootstrapValidator.min.js"> </script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"/> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/datepicker/js/bootstrap-datepicker.js"></script>
<div class="row">
    <div class="col-8 col-md-8 col-lg-8">
    	<div class="card">
              <!-- <div class="card-header">
                <h4>Check</h4>
              </div> -->
              <div class="card-body"><!--  style="margin: 10px auto;" -->
                <div class="form-group">
                    <div class="row pull-right">
                    </div>
                  	<?php for($a=0; $a<count($aktivitas); $a++){ ?>
        				<div class="gallery gallery-md gutters-sm">
                            <div class="col-8 col-md-8 col-lg-12">
                                <?php 
                                $image = base_url('/uploads/aktifitas/'.$aktivitas[$a]->mc_id.'/'.$aktivitas[$a]->taf_date.'/'.$aktivitas[$a]->taf_file);
                                //$image = 'http://103.146.244.78/cosmic_api/public/storage/aktifitas/'.$aktivitas[$a]->mc_id.'/'.$aktivitas[$a]->ta_date.'/'.$aktivitas[$a]->taf_file;
                                ?> 
                                <div class="gallery-item" data-image="<?php echo $image?>" data-title="Image 1" href="<?php echo $image?>" title="Image 1" 
                                    style="background-image: url('<?php echo $image?>');"></div>
                            </div>
                           <div class="gallery-item gallery-hide" data-image="<?php echo $image?>" data-title="Image 9" href="<?php echo $image ?>" title="Image 9" style="background-image: url('<?php echo $image?>')"></div>
        				</div>
        				<div class="gallery gallery-md gutters-sm">
        			    	<div class="col-8 col-md-8 col-lg-12">
        			    		<h6><?php echo $aktivitas[$a]->mcr_name; ?></h6>
    		    				<h4><?php echo $aktivitas[$a]->mcar_name; ?></h4>
        			    		<button class='btn btn-md btn-primary' type='button'
                                onclick="downloadFile('<?php echo $image; ?>','Download');"
                                formtarget='_self'> <span class='bluexx'>
                                <i class='ace-icon fa fa-download bigger-120'>
                                </i></span>
                             	</button>
        			    	</div>
        			   	</div>
        			   	<br><br><br>
					<?php } ?>
              </div>
            
            </div>
    	</div>
    </div>
</div>
<script type="text/javascript">
function downloadFile(filePath){
    var link=document.createElement('a');
    link.href = filePath;
    link.download = filePath.substr(filePath.lastIndexOf('/') + 1);
    link.click();
}
</script>