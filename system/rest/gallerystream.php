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
		$result[$i]['slide_title'] = getSlideshowTitle($slide_id);

		// Get the slideshow data
		$q2 = "SELECT * FROM `slideshows` WHERE `id`='$slide_id' LIMIT 1";
		$r2 = $db->query($q2);
		$a2 = $db->fetch_array_assoc($r2);
		
		$photos = explode(';',$a2['photos']);
		$photos_total = count($photos);

		unset($p);
		unset($pv);
		unset($pt);
		$result[$i]['start'] = $photo_count;
		$result[$i]['set_number'] = $i;
		for ($i2 = 0; $i2 != $photos_total; $i2++) {
			$pid = $photos[$i2];
			
			$image_src = getImage($pid,'full');
			$p[] = $image_src;
			$prev_src = getImage($pid,'preview');
			$pv[] = $prev_src;
			$thumb_src = getImage($pid,'thumbnail');
			$pt[] = $thumb_src;
			$photo_count++;
		}
		// append the photos for the slideshow
		$result[$i]['show_infobar'] = $a2['show_infobar'];
		$result[$i]['show_filmstrip'] = $a2['show_filmstrip'];
		$result[$i]['photo_count'] = count($p);
		$result[$i]['photos'] = $p;
		$result[$i]['previews'] = $pv;
		$result[$i]['thumbnails'] = $pt;
		$cover_src = getSlideshowCover($slide_id,$db);
		$result[$i]['cover'] = $cover_src;
	}
}
	
// var_dump($_REQUEST);

echo json_encode($result);
?>