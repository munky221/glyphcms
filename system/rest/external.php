<?php
header('Content-Type: application/json');

require('rest-core.php' );
if (isset($_REQUEST['xurl'])) {
	$xurl = $_REQUEST['xurl'];

	/*$ch = curl_init() or die(curl_error()); 

	curl_setopt($ch, CURLOPT_URL,$xurl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$scrape = curl_exec($ch) or die(curl_error()); 
	$scrape = mb_convert_encoding($scrape, 'utf-8');
	$result = array('content' => $scrape);

	curl_close($ch);*/

	// echo curl_error($ch);

	// $result = array('xurl' => $xurl, 'content' => $scrape);
	$result = array('xurl' => $xurl);
	
} else {
	$result[] = false;
}
echo json_encode($result);
?>