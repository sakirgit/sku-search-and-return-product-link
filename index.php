<?php
// a one-line simple option to reade CSV to array
// it uses PHP str_getcsv
$csvArray = array_map('str_getcsv', file('wc-product-export-8-2-2023-1675807726850.csv'));

$all_sku_list = [];
$total_count = 0;
foreach( $csvArray as $csvArray_k => $csvArray_v ){
	if( $csvArray_k > 0 ){
		array_push($all_sku_list, $csvArray_v[0]);
		$total_count = $csvArray_k;
	}
}

function cal_percentage($num_amount, $num_total) {
	$count1 = $num_amount / $num_total;
	$count2 = $count1 * 100;
	$count = number_format($count2, 0);
	return $count;
 }
?>	
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Hugo 0.108.0">
    <title> </title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<style>
		#prod_url,
		#prod_url_x{
			font-size: 12px;
			line-height: 0.917;
		}
		#prod_url_x{
			color: red;
		}
	</style>
  </head>
  <body>
		<div class="container py-3">
		  	<div class="container progress rounded-0 fixed-top mt-2" role="progressbar" aria-label="Animated striped example" style="height: 30px;" aria-valuenow="<?php echo cal_percentage(0, $total_count); ?>" aria-valuemin="0" aria-valuemax="100">
				<div class="row progress-bar progress-bar-striped bg-info progress-bar-animated" id="cal_percentage_bar" style="width: <?php echo cal_percentage(0, $total_count); ?>%">0%</div>
			</div>
			<br>
		  <main>
			 <div class="row">
				 <div class="col-7">
						<div class="row">
							<?php 
								foreach( $all_sku_list as $all_sku_list_k => $all_sku_list_v ){ 
									
									echo "<div class='col-2 mt-3 sku_cell'>". $all_sku_list_k+1 ."<input id='". $all_sku_list_k+1 ."' size='8' class='form-control form-control-sm text-center' type='text' value='". $all_sku_list_v ."'></div>";
								}
							?>
					 </div>
				 </div>
				 <div class="col-5">
				 	
					<button id="btn2start" class="btn btn-primary btn-lg mt-2"> &nbsp; Start &nbsp; </button>
					<div id="prod_url"></div>
					<div id="prod_url_x"></div>
				 </div>
			 </div>

		  </main>

		  <footer class="pt-4 my-md-5 pt-md-5 border-top">
			 <div class="row">
				<div class="col-12 col-md">
				  <small class="d-block mb-3 text-muted">&copy; www.developer-s.com</small>
				</div>
			 </div>
		  </footer>
		</div>

    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	 <script>
	 
	 function sku_ajax_pass(input_id){
		var sku_id = $('#' + input_id).val();
		$.ajax({
		  type: 'POST',
		  url: 'sku-data-pro.php',
		  data: { "sku_id": sku_id }
		})
		.done(function( msg ) {
			msg_data = JSON.parse(msg);
		//	console.log(msg_data);
		//  $( "#get_sku_with_cat" ).append('"' + sku_id + '"=>"'+ msg.cat +'",');
		//  $( "#get_sku_with_cat" ).html('<iframe src="https://www.nisbets.com.au' + msg_data.prod_url + '" ></iframe>');
			$("#"+ input_id).css("border-color", "yellow");
		  if( msg_data.status == "success" ){
			  console.log('msg_success::', input_id);
			  $("#prod_url").append('"https://www.nisbets.com.au' + msg_data.prod_url + '",<br>');
			  $("#"+ input_id).css("background-color", "green").css("color", "#fff");
		  }else{
			  console.log('msg_error::', input_id);
			  $("#prod_url_x").append( msg_data.prod_url + ',<br>');
			  $("#"+ input_id).css("background-color", "red").css("color", "#fff");
		  }
		  var total_comp_percentage = (100 * input_id) / (<?php echo $total_count ?> *1);
		  $("#cal_percentage_bar").css("width", Math.round(total_comp_percentage) + "%").text(total_comp_percentage.toFixed(2) + "%");
		  sku_ajax_pass(input_id+1);
		});
	 }
		$('#btn2start').on('click', function(){
			sku_ajax_pass(1);
		});

	 </script>
  </body>
</html>
