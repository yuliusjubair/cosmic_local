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
                <article class="article article-style-c">
                  <div class="article-header">
                    <?php $image1 = base_url('/uploads/pengumuman/'.$row->file_image);
                    //$image1 = base_url().'assets/login/left_bg_font.png';
                    ?> 
                    <div class="article-image" data-background="<?php echo $image1?>" style="background-image: url(<?php echo $image1?>);">
                    </div>
                  </div>
                  <div class="article-details">
                    <div class="article-category">
                        <a href="#">Tanggal Expired</a> <div class="bullet"></div> <a href="#"><?php echo date('d/m/Y', strtotime($row->end_date))?></a> |
                        <a href="#">Tanggal Post</a> <div class="bullet"></div> <a href="#"><?php echo date('d/m/Y', strtotime($row->date_insert))?></a>
                    </div>
                    <div class="article-title">
                      <h2><a href="#"><?php echo $row->judul?></a></h2>
                    </div>
                    <p><?php echo $row->deskripsi?></p>
                    <!-- <div class="article-user">
                      <img alt="image" src="assets/img/avatar/avatar-1.png">
                      <div class="article-user-details">
                        <div class="user-detail-name">
                          <a href="#">Hasan Basri</a>
                        </div>
                        <div class="text-job">Web Developer</div>
                      </div>
                    </div> -->
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Tutup</button>
                </div>
                </article>

          </div>
        </div>
</div>
</div>