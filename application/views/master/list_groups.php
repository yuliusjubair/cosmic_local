<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href="<?php echo base_url(); ?>assets/Images/logoSI.png" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/codebase/dhtmlx.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/template/ptsi/css/selectunit.css"/>
	<script src="<?php echo base_url(); ?>assets/codebase/dhtmlxpro.js"></script>
</head>
<body onload="doOnLoad()">
	<form id="form1" name="form1" method="post" action="">
		<td><div id="combo_kab" class="col-sm-2"> </div></td>
	<div class="styled-loader" id="loading"></div>
		<script>
    		var myLayout;
    		var myGrid;
    		var myDataProcessor;
    		function doOnLoad(){
    			myGrid = new dhtmlXGridObject('gridbox');
    			myGrid.setImagePath("./codebase/imgs/");
    			myGrid.setHeader("No., Id,  Nama,...",null,
    			   ["text-align:center;vertical-align:middle;font-size:1.0em;font-weight:bold;color:#404040;padding-right:10px;",
    				"text-align:center;vertical-align:middle;font-size:1.0em;font-weight:bold;color:#404040;padding-right:10px;",
    				"text-align:center;vertical-align:middle;font-size:1.0em;font-weight:bold;color:#404040;padding-right:10px;",
    				"text-align:center;vertical-align:middle;font-size:1.0em;font-weight:bold;color:#404040;padding-right:10px;"]);
    			myGrid.setInitWidths("50,0,250,90");
    			myGrid.setColAlign("right,right,left,center");
    			myGrid.setColTypes("ron,ro,ro,ro");
    			myGrid.setColSorting("int,str,str,str");
    			myGrid.enablePaging(true,30,5,"pagingArea",true,"infoArea");
    			myGrid.setPagingSkin("bricks");
    			myGrid.enableMultiline(true);
    			myGrid.enableStableSorting(false);
    			myGrid.init();
    			myGrid.attachEvent("onXLE",showLoading);
    			myGrid.attachEvent("onXLS",function(){showLoading(true)});
    			myGrid.enableRowsHover(true,"hover");
    			myGrid.load("<?php echo base_url()."master/data_list_groups"; ?>", function(){
    				myGrid.attachHeader("#rspan,#rspan,#rspan, <div id='title_flt'></div>",
    	    				["text-align:center;vertical-align:middle;font-size: 1.0em;",  
    	    				"text-align:center;vertical-align:middle;font-size: 1.0em;",
    	    				"text-align:center;vertical-align:middle;font-size: 1.0em;",
                            "text-align:center;vertical-align:middle;font-size: 1.0em;"]);
    				document.getElementById("title_flt").appendChild(document.getElementById("add_flt_box").childNodes[0]);
    				myGrid.hdr.rows[2].onmousedown=myGrid.hdr.rows[2].onclick=function(e){(e||event).cancelBubble=true;};
    				myGrid.setSizes();
    			});
    		}
    
    		function showLoading(fl){
    			if(fl===true)
    				$("#loading").show();
    			else
    				$("#loading").hide();
    		}

    		function RefreshAndFilter(){
    			cari=$("#nav-search-input").val().replace(/\s+/g, '-').toUpperCase();
    			//alert(cari)
    			myGrid.clearAndLoad('<?php echo base_url()."master/data_list_groups/"; ?>'+cari);
    		}
    		var dhxWins;
    		var w1;
    		function openWindowDetail(idwin) {
    			//alert(idwin);
    		    dhxWins = new dhtmlXWindows();
    			
    			w1 = dhxWins.createWindow("w1Coe", 0, 0, 520, 320);
    			var data = idwin.split('|');
    			//data[0]:id proyek
    			//data[1]:seqno
    			//data[2]:tipe form
    			w1.setText("Form Groups");
    			//w1.setText("Detail");
    			var url_data="<?php echo base_url()."master/popup_groups_form/"; ?>";
    			
    			w1.attachURL(url_data +data[0]+'/'+data[1], true);
    			//w1.attachURL('./images/logoapp.png', true);
    			
    			w1.button("minmax1").hide();
    			w1.button("park").hide();
    			//w1.maximize();
    			w1.center();
    			//w1.setPosition(200, 250);
    			w1.allowMove();	

    		}
    		function delete_data(no, id){
    			var r = confirm("Anda yakin akan menghapus Groups "+myGrid.cellById(myGrid.getSelectedId(),2).getValue()+" tersebut?");
    			var myurl='<?php echo base_url().'master/del_groups'; ?>/';
    			if (r == true) {
    				myGrid.deleteSelectedItem();
    				$.post( myurl, { id : id } );
    				alert('Data berhasil dihapus');	
    			} 
    		}
		</script>

	    <div style="clear:left" ></div>
		<div id="gridbox" style="margin-top:5px;width:100%; height:420px; background-color:white;"></div>
		<div>
			<span id="pagingArea"></span>&nbsp;
			<span id="infoArea"></span>
		</div>
	</div>
 	<div style="display:none">
		<div id="add_flt_box"><button class="btn btn-info btn-sm " type="button" onclick="openWindowDetail('<?php echo '|0'; ?>');"  formtarget='_blank'  ><i class='ace-icon fa fa-plus bigger-120'></i></button></div>
	</div>
	</form>
</body>
</html>