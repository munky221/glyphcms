<?php
header('Content-Type: application/json');

if (isset($_REQUEST['gid']))
	$gid = $_REQUEST['gid'];
else
	$gid = 1;

require('rest-core.php' );
if (isset($_REQUEST['sid']))
	$sid = $_REQUEST['sid'];
else
	$sid = 1;
	
// Get the gallery data
$q = "SELECT * FROM `galleries` WHERE `id`='$gid' LIMIT 1";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r);

$slides = explode(';',$a['slideshows']);
$slides_total = count($slides);

$photo_count = 0;
if (count($slides)>0) {
	
	for ($i = 0; $i != $slides_total; $i++) {
		$slide_id = $slides[$i];
		
		// append slideshow id
		$result[$i]['slide_id'] = $slide_id;

		// Get the slideshow data
		$q2 = "SELECT * FROM `slideshows` WHERE `id`='$slide_id' LIMIT 1";
		$r2 = $db->query($q2);
		$a2 = $db->fetch_array_assoc($r2);
		
		$result[$i]['slide_title'] = $a2['title'];
		$photos = explode(';',$a2['photos']);
		$photos_total = count($photos);

		unset($p);
		$result[$i]['start'] = $photo_count;
		$result[$i]['set_number'] = $i;
		for ($i2 = 0; $i2 != $photos_total; $i2++) {
			$pid = $photos[$i2];
			
			$image_src = getImage($pid,'full');
			$p[] = $image_src;
			$photo_count++;
		}
		// append the photos for the slideshow
		$result[$i]['photo_count'] = count($p);
		// $result[$i]['photos'] = $p;
		$cover_src = getSlideshowCover($slide_id,$db);
		$result[$i]['cover'] = $cover_src;
	}
}
	
// var_dump($_REQUEST);

echo json_encode($result);
?>