<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
<title>Report Protokol &mdash; Cosmic</title>
<!-- General CSS Files -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
<!-- CSS Libraries -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/node_modules/bootstrap-social/bootstrap-social.css">
<!-- Template CSS -->
 <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets_stisla/css/components.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<style type="text/css">
.divhead {
    position: absolute;
    width: 100%;
    background-color: white;
    padding: 5px;
    align-content:left;
}
</style>
</head>
<body style="background-color:#5283C0;">
<div class="divhead">
	<img src="<?php echo base_url(); ?>assets/login/LogoShort.png" alt="logo" width="100" class="align-items-center">
</div>
<div class="container" style="padding-top:50px;">
	<div class="row">
        <div class="col-sm-2">
        </div> 
        <div class="col-sm-8 card" style="padding:20px" >
        	<form action="#" id="form" class="form-horizontal">
        		<div class="row">
					<div class="col-md-12"><center><h4>Laporan atau Keluhan Perimeter</h4></center></div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-4"><p style="font-size:14px">Nama Perusahaan</p></div>
                    <div class="col-md-8">  
                        <select id="company" name="company" data-live-search="true"
                        	class="form-control selectpicker" data-style="btn-white btn-default" >
                            <?php foreach ($company->result() as $row) { ?>
                            <option value="<?php echo $row->mc_id;?>">
                    			<?php echo $row->mc_name;?>
                    	    </option>
                    		<?php } ?>
                		</select>
            		</div>
              	</div>
			  	<div class="row">
					<div class="col-md-4"><p style="font-size:14px">Nama Perimeter</p></div>
                    <div class="col-md-8">
						<select id="perimeter" name="perimeter" data-live-search="true"
                        	class="form-control selectpicker" data-style="btn-white btn-default" >
                		</select>
					</div>
              	</div>
              	<div class="row">
					<div class="col-md-4"><p style="font-size:14px">Nama Lantai<span style="color:red">*</span></p></div>
                    <div class="col-md-4">
						<select id="lantai" name="lantai" data-live-search="true"
                        	class="form-control selectpicker" data-style="btn-white btn-default" >
                		</select>
                		<font color="red"><span class="help-block"></span></font>
                    </div>
              	</div>
				<div class="row">
					<div class="col-md-4"><p style="font-size:14px">Alamat</p></div>
                    <div class="col-md-8"><b style="font-size:14px" id="perimeter_alamat" name="perimeter_alamat"></div>
              	</div>
              	<br/>
              	<div class="row">
					<div class="col-md-4"><p style="font-size:14px">Laporan atau Keluhan<span style="color:red">*</span></p></div>
                    <div class="col-md-8">
                     	<textarea class="form-control" name="laporan" id="laporan"></textarea>
                    	<font color="red"><span class="help-block"></span></font>
                    </div>
              	</div>
              	<br/>
              	<div class="row">
              		<div class="col-md-4"><p style="font-size:14px">Upload Bukti Foto</p></div>
                    <div class="col-md-8">
                        <input id="eviden_1" name="eviden_1" type="file" class="required" required><br>
                        <font size="1" color="#800000">* Filetype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <span class="help-block"></span>
                    </div>
                </div>
                <br/>
				<div class="row">
                  	<div class="col-md-4"><p style="font-size:14px">Upload Bukti Foto 2</p></div>
                    <div class="col-sm-4">
                        <input id="eviden_2" name="eviden_2" type="file" class="required" required><br>
                        <font size="1" color="#800000">* Filetype yang diperbolehkan jpg, png dan max 2Mb</font>
                        <font color="red"><span class="help-block"></span></font>
                    </div>
                </div>
                <hr>
                <div class="row">
            		<div class="col-md-4">
        				<div class="g-recaptcha" data-sitekey="6Lc9Xd8ZAAAAAHttSC7P7uXXARbQSQ76iaKEqUeo"></div>  
        				<input type="hidden" name="recaptcha">
        				<font color="red"><span class="help-block"></span></font>
        			</div>
    			</div>
    			<br>
              	<div class="row">
              		<div class="col-md-4"></div>
                  	<div class="col-md-4" style="align-self:center;">
            			<button type="button" class="btn btn-xs btn-primary" id="btnSave" onclick="save()" >Kirim Laporan</button>
                	</div>
                	<div class="col-md-4"></div>
            	</div>			
        	</form>
		</div>
		<div class="col-sm-2">
        </div> 
	</div>
</div>
<!-- General JS Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets_stisla/js/stisla.js"></script>
<!-- JS Libraies -->
<!-- Template JS File -->
<script src="<?php echo base_url(); ?>assets_stisla/js/scripts.js"></script>
<script src="<?php echo base_url(); ?>assets_stisla/js/custom.js"></script>
<!-- Page Specific JS File -->
<script type="text/javascript">
$(document).ready(function() {
    $('input[type=file]').change(function () {
        var val = $(this).val().toLowerCase(),
            regex = new RegExp("(.*?)\.(png|jpg|PNG|JPG)$");
        if (!(regex.test(val))) {
            $(this).val('');
            alert('Filtype yang diperbolehkan png atau jpg');
        }
    
        if(this.files[0].size/1024/1024 > 2){
        	$(this).val('');
        	alert('File size max 2Mb');
        }
    });

    //if($perimeter==null){
        $("#company").change(function(){ 
            $("#perimeter").hide();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>reportprotokol/perimeter",
                data: {kd_perusahaan : $("#company").val()},
                dataType: "json",
                beforeSend: function(e) {
                    if(e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response){
                    $("#perimeter").html(response.list_perimeter).show();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>reportprotokol/perimeter_level",
                        data: {perimeter_id : $("#perimeter").val()},
                        dataType: "json",
                        beforeSend: function(e) {
                            if(e && e.overrideMimeType) {
                                e.overrideMimeType("application/json;charset=UTF-8");
                            }
                        },
                        success: function(response){
                            $("#lantai").html(response.list_perimeter_level).show();
                            var perimeter =  $("#perimeter").val();
                            $.ajax({
                                type: "GET",
                                url: "<?php echo base_url().'reportprotokol/perimeter_alamat/'?>"+perimeter,
                                dataType: "html",
                                success: function(response){
                                   $("#perimeter_alamat").html(response);
                                }
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    
        $("#perimeter").change(function(){ 
            $("#perimeter_level").hide();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>reportprotokol/perimeter_level",
                data: {perimeter_id : $("#perimeter").val()},
                dataType: "json",
                beforeSend: function(e) {
                    if(e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function(response){
                    $("#lantai").html(response.list_perimeter_level).show();
                    var perimeter =  $("#perimeter").val();
                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url().'reportprotokol/perimeter_alamat/'?>"+perimeter,
                        dataType: "html",
                        success: function(response){
                           $("#perimeter_alamat").html(response);
                        }
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    //}
});

function save() {
    var form = new FormData($('#form')[0]);
    $.ajax({
        type: "POST",
        url : "<?php echo base_url('Reportprotokol/save_report')?>",
        data: form,
        cache: false,
        contentType: false,
        processData: false,
        success:  function(datax){
            console.log(datax);
            if(JSON.parse(datax).status==200) {
            	alert('Berhasil Submit Report');
                location.reload();
            } else {
            	alert('Gagal Submit Report');
                for (var i = 0; i < JSON.parse(datax).inputerror.length; i++) {
            		$('[name="'+JSON.parse(datax).inputerror[i]+'"]').parent().parent().addClass('has-error');
                	$('[name="'+JSON.parse(datax).inputerror[i]+'"]').next().text(JSON.parse(datax).error_string[i]);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        	unblock();
        	alert('Gagal Submit Report');
        }
    });
}

function block() {
    var body = $('#panel-body');
    var w = '100%';
    var h = '100%';
    var trb = $('#throbber');
    var position = body.offset(); // top and left coord, related to document
    var top = '1';
    trb.css({
        width: w,
        height: h,
        opacity: 0.7,
        position: 'absolute',
        top:        0,
        left:       0
    });
    trb.show();
}

function unblock() {
    var trb = $('#throbber');
    trb.hide();
}
</script>
</body>
</html>
