<?php
header('Content-Type: application/json');

require('rest-core.php' );

$id = $site->site_homepage_target;

if ($site->site_homepage_default) {

	$q = "SELECT * FROM `photos`";
	$r = $db->query($q);
	while ($a = $db->fetch_array_assoc($r)) {
		$hay = explode(";",$a['categories']);
		if (in_array('0',$hay))
			$featured_array[] = $a['id'];
	}
	$rand = rand(0,count($featured_array)-1);
	$featured_id = $featured_array[$rand];
	$src = getImage($featured_id,"full");

	$result['hero'] = $src;
} else {

	if ($site->site_homepage_target_type == "3" || $site->site_homepage_target_type == "4") { // SLIDESHOW PRESENTATION

		// Get the gallery data
		$q = "SELECT * FROM `slideshows` WHERE `id`='$id' LIMIT 1";
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

	} else if ($site->site_homepage_target_type == "5") { // GALLERY PRESENTATION

		// Get the gallery data
		$q = "SELECT * FROM `galleries` WHERE `id`='$id' LIMIT 1";
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

	} else if ($site->site_homepage_target_type == "6") { // GALLERIES GRID

		// Get the gallery data
		$q = "SELECT * FROM `galleries` WHERE `id`='$id' LIMIT 1";
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

	} else if ($site->site_homepage_target_type == "1") { // STATIC PAGE

		$pid = $_REQUEST['pid'];
			
		// Get the gallery data
		$q = "SELECT * FROM `pages` WHERE `id`='$id' LIMIT 1";
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

		    if ($match == "contactForm")
		    	$newValue = file_get_contents('contact-form.php', true);

		    $string = str_replace($matches[0][$i], $newValue, $string);
		}

		$result['content'] = $string;

	}
}

echo json_encode($result);
?>