<?php
header('Content-Type: application/json');

require('rest-core.php' );

if (isset($_REQUEST['pid'])) {
	$pid = $_REQUEST['pid'];
	
	// Get the gallery data
	$q = "SELECT * FROM `pages` WHERE `id`='$pid' LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	
	$result['id'] = $a['id'];
	$result['user_id'] = $a['user_id'];
	$result['title'] = $a['title'];
	$result['slug'] = $a['slug'];
	$result['excerpt'] = $a['excerpt'];
	$result['type'] = $a['type'];
	$result['status'] = $a['status'];
	$result['timestamp'] = $a['timestamp'];

	// parse shortcodes for content
	$string = $a['content'];
	$regex = "/\[\%\s(.*?)\s\%\]/";
	preg_match_all($regex, $string, $matches);

	for($i = 0; $i < count($matches[1]); $i++)
	{
	    $match = $matches[1][$i];

	    if ($match == "contactForm") {
	    	$newValue = '<form id="contactForm" method="post" class="" action="contact-form-send.php">';
	    	$newValue .= file_get_contents('contact-form.php', true);
	    	$newValue .= '<div class="form-group form-group-buttons"><div class="col-lg-12"><button type="submit" class="btn btn-primary">Send</button></div></div></form>';
	    }

	    $string = str_replace($matches[0][$i], $newValue, $string);
	}

	$result['content'] = $string;
	
} else {
	$result[] = false;
}
echo json_encode($result);
?>