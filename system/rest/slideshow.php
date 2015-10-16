<?php
header('Content-Type: application/json');

require('rest-core.php' );
if (isset($_REQUEST['sid']))
	$sid = $_REQUEST['sid'];
else
	$sid = 1;
	
// Get the gallery data
$q = "SELECT * FROM `slideshows` WHERE `id`='$sid' LIMIT 1";
$r = $db->query($q);
$a = $db->fetch_array_assoc($r);

$photos = explode(';',$a['photos']);
for ($i = 0; $i != count($photos); $i++) {
	$pid = $photos[$i];
	$image_src = getImage($pid,'full');
	$p[] = $image_src;
	$prev_src = getImage($pid,'preview');
	$pv[] = $prev_src;
	$thumb_src = getImage($pid,'thumbnail');
	$pt[] = $thumb_src;
}

$categories = explode(';',$a['category']);
for ($i = 0; $i != count($categories); $i++) {
	$cid = $categories[$i];
	$catname = getCategoryName($cid);
	$c[] = $catname;
}

$result['slide_id'] = $a['id'];
$result['slide_title'] = getSlideshowTitle($a['id']);
$result['title'] = $a['title'];
$result['desc'] = $a['excerpt'];
$result['categories'] = $c;
$result['show_infobar'] = $a['show_infobar'];
$result['show_filmstrip'] = $a['show_filmstrip'];
$result['photo_total'] = count($photos);
$result['photos'] = $p;
$result['previews'] = $pv;
$result['thumbnails'] = $pt;
$result['cover'] = getImage($a['cover'],'full');
	
// var_dump($_REQUEST);

echo json_encode($result);
?>