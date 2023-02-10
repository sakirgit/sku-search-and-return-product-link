<?php

if ( isset( $_POST['sku_id'] ) ) {
  $sku_id = $_POST['sku_id'];
  
	  
	function getRedirect($url){
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36');
		 curl_setopt($ch, CURLOPT_HEADER, true);
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 $responseHeaders = curl_exec($ch);
		 curl_close($ch);
		 if(preg_match_all("/^location: (.*?)$/im", $responseHeaders, $results))
			  return $results[1][0];
	}


  $get_prod_url = getRedirect("https://www.nisbets.com.au/search/". $sku_id);
  if( $get_prod_url ){
		$response = ['status'=> 'success', 'prod_url'=> trim($get_prod_url)];
  }else{
	   $response = ['status'=> 'error', 'prod_url'=> 'https://www.nisbets.com.au/search/'. $sku_id];
  }
  echo json_encode($response);
  exit;
}

