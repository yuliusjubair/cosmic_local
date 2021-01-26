 <?php if ($group == 1){?>
  <div class="col-sm-3 form-group">
        <select id="group_company" name="group_company" data-live-search="true"
          class="form-control " data-style="btn-white btn-default" >
            <option value="3" selected>SEMUA</option>
            <option value="1" >BUMN</option>
            <option value="2" >NON BUMN</option>
        </select>
  </div>
  <?php }?>
    <div class="col-sm-6">
        <div class="form-group">
           <select id="company" name="company" data-live-search="true" onchange="refresh_list()"
        	class="form-control " data-style="btn-white btn-default" >
             	<?php
        	foreach ($company->result() as $row) {
        	    if(isset($p_mc_id)){

                    if($p_mc_id!=NULL && $p_mc_id==$row->mc_id){
            	        $mc_selected='selected="selected"';
            	    }else{
            	        $mc_selected='';
            	    }
                }else{
                    $mc_selected='';
                }
        	?>
            <option value="<?php echo $row->mc_id;?>" <?php echo $mc_selected;?>>
    			<?php echo $row->mc_name;?>
    	    </option>
    		<?php } ?>
        </select>
        </div>
    </div>
<script type="text/javascript">
$(document).ready(function() {
	$('#group_company').selectpicker();
    $('#company').selectpicker();
	$('#group_company').change(function(){
       block();
       var id=$(this).val();
       console.log(id);
       $.ajax({
           url : "<?php echo site_url('master/get_company_bygroupcompany?group_company=');?>"+id,
           method : "POST",
           async : true,
           dataType : 'json',
           success: function(data){
               $('#company').html('');
               var html = '';
               var i;
               for(i=0; i<data.length; i++){
                   html += '<option value='+data[i].mc_id+'>'+data[i].mc_name+'</option>';
                   // console.log(data[i].mc_name);
               }
               $('#company').append(html);
               refresh_list();
               $('#company').selectpicker('refresh');
               unblock();
           }
       });
       return false;
   });
});  
</script>