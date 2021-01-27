<html>
   <head>
      <title>Highcharts Tutorial</title>
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
      </script>
      <script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>

	  <div id="container"></div>		  
		<style>
			#container {
			  height: 400px; 
			}
		</style>
   </head>
   
   <body>
      <div id = "container"></div>
     <!--  <?php
		echo "<pre>";
		print_r($pic);
		echo "</pre>";
		?> -->
      <script language = "JavaScript">

      	function toDate(opt = 0){
      		var arr = [];      		
					for (var i = 14 - 1; i >= 0; i--) {
						var today = new Date();	         	

						today.setDate(today.getDate() - i);
						var dd = String(today.getDate()).padStart(2, '0');
						var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
						var yyyy = today.getFullYear();

						today = yyyy + '-' + mm + '-' + dd;

						if (opt == 0){
							const date = new Date(today);
							const formattedDate = date.toLocaleDateString('en-GB', {
							  day: 'numeric', month: 'short'
							}).replace(/ /g, ' ');
							arr.push(formattedDate);							
						}else{
							arr.push(today);
						}
						// console.log(formattedDate);
					}					

					return arr;
      	}

      	function getRemote(url) {
				    return $.ajax({
				        type: "GET",
				        url: url,
				        async: false
				    }).responseText;
				}

      	function getPic(){
      		const arr = toDate(1);
      		for (var a in arr){
      			console.log(arr[a]);
      		}
					// $.get("http://localhost/ilcs/cosmic_2/dashminmonitoring/get_pic", function(data, status){
				 //    var obj = JSON.parse(data);
				 //    console.log(obj);
				 //  });
      	}

         $(document).ready(function() {    
      //    	$.get("http://localhost/ilcs/cosmic_2/dashminmonitoring/get_pic", function(data, status){
				  //   var obj = JSON.parse(data);
				  //   console.log(obj);
				  // });

				  var arrPic = [], arrFo = [], arrBumn = [];
				  var dataPic = getRemote("http://localhost/ilcs/cosmic_2/dashminmonitoring/get_pic");
				  	dataPic = JSON.parse(dataPic);
				  var dataFo = getRemote("http://localhost/ilcs/cosmic_2/dashminmonitoring/get_fo");
				  	dataFo = JSON.parse(dataFo);
				  var dataBumn = dataPic;
				  // console.log(dataPic);
				  // console.log(dataFo);
				  var tgl = toDate(1);
				  for (var a in tgl){
				  	var valPic = 0, valFo = 0, valBumn = 0;
				  	for (var b in dataPic){
				  		if (dataPic[b].tgl == tgl[a]){
				  			valPic = parseInt(dataPic[b].count);
				  		}				  		
				  	}

				  	for (var b in dataFo){
				  		if (dataFo[b].tgl == tgl[a]){
				  			valFo = parseInt(dataFo[b].count);
				  		}				  		
				  	}

				  	for (var b in dataBumn){
				  		if (dataBumn[b].tgl == tgl[a]){
				  			valBumn = parseInt(dataBumn[b].count);
				  		}				  		
				  	}

				  	arrPic.push(valPic)
				  	arrFo.push(valFo)
				  	arrBumn.push(valBumn)
				  }

				  console.log(arrPic);
				  
         	var colors = Highcharts.getOptions().colors.slice(0);
         	colors[1] = '#78B3E0';
         	colors[2] = '#235797';
         	colors[3] = '#AFD7F5';

          Highcharts.chart('container', {
					  chart: {
					    type: 'column'
					  },
					  colors: colors,
					  title: {
					    text: ''
					  },
					  xAxis: {
					    categories: toDate()
					  },
					  yAxis: {
					    min: 0,
					    title: {
					      text: ''
					    },
					    stackLabels: {
					      enabled: true,
					      style: {
					        fontWeight: 'bold',
					        color: ( // theme
					          Highcharts.defaultOptions.title.style &&
					          Highcharts.defaultOptions.title.style.color
					        ) || 'gray'
					      }
					    }
					  },
					  legend: {
					    align: 'right',
					    x: -30,
					    verticalAlign: 'top',
					    y: 25,
					    floating: true,
					    backgroundColor:
					      Highcharts.defaultOptions.legend.backgroundColor || 'white',
					    borderColor: '#CCC',
					    borderWidth: 1,
					    shadow: false
					  },
					  tooltip: {
					    headerFormat: '<b>{point.x}</b><br/>',
					    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
					  },
					  plotOptions: {
					    column: {
					      stacking: 'normal',
					      dataLabels: {
					        enabled: false
					      }
					    }
					  },
					  series: [{
					    name: 'FO',
					    data: arrFo
					  }, {
					    name: 'PIC',
					    data: arrPic
					  }, {
					    name: 'BUMN',
					    data: arrBumn
					  }]
					});
         });
      </script>
   </body>
   
</html>