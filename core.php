<?php
// Require System Config File
require('config.php' );

// Website Dependencies
require( ABSPATH . SITE_INC . '/database.php' );
require( ABSPATH . SITE_INC . '/functions.php' );
require( ABSPATH . SITE_INC . '/classes.php' );

// set db connection
$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// setup db query
$q = "SELECT `name`,`value` FROM `taxonomy`";
$r = $db->query($q);
while ($a = $db->fetch_array_assoc($r)) {
	$site_data[$a['name']] = $a['value'];
	if ($a['name'] == 'menu_data')
		$menu_data = $a['value'];
	if ($a['name'] == 'theme_name')
		$theme_name = $a['value'];
}
// create site object
$site = new Site($site_data);

// create theme object
$theme = new Theme($theme_name);

function menuArrayConvert($data)
{	
	$result = array();
	$a1 = explode(",",$data);
	foreach ($a1 as $key => $val)
	{
		if (strpos($val,";") > 0)
		{
			list($parent, $children) = explode(";",$val);

			// add the parent value
			$result[$key]['id'] = $parent;

			$a2 = explode("-",$children);
			$result[$key]['children'] = $a2;
		} else {
			$result[$key]['id'] = $val;
		}
	}
	return $result;
}

function getPhotoData($id)
{
	global $db;

	$q = "SELECT * FROM `photos` WHERE (`id`='$id') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	return $a;
}

function menuArrayRevert($data)
{	
	$result = "";
	foreach ($data as $key => $parent)
	{
		// store parent id first
		if ($result=="")
			$result = $parent['id'];
		else
			$result .= "," . $parent['id'];

		// if parent has children
		if (isset($parent['children']))
		{
			$childGroup = "";
			foreach ($parent['children'] as $child_key => $child)
			{
				if ($childGroup=="")
					$childGroup = ";" . $child;
				else
					$childGroup .= "-" . $child;
			}
			$result .= $childGroup;
		}
	}
	return $result;
}


function getMenuData($mid)
{
	global $db;

	$q = "SELECT * FROM `menu` WHERE (`id`='$mid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $a;
}


function buildMenu($data, $container = 'mainNav', $offset = 0, $last = 0, $className = 'siteNav', $linkClass = 'ajaxLink')
{
	$linkType = array('','page','ext','slideshow','slideshow','gallery','gallery');
	global $site;
	$result = '<ul id="' . $container . '"';
	if (!isset($className))
		$result .= '>';
	else
		$result .= ' class="nav navbar-nav">' . "\n";

	$data_array = explode(",", $data);

	$error = 0;

	if ( (($offset+$last) > count($data_array)) )
		$error++;

	if ($error == 0) {
		for ($i = $offset; $i != count($data_array)-$last; $i++)
		{
			$val = $data_array[$i];
			if (strpos($val,";") > 0)
				list($parent, $children) = explode(";",$val);
			else
				$parent = $val;

			$x = getMenuData($parent);

			if ($x['type'] == '0')
				$href = 'javascript:void(0);';
			else if ($x['type'] == '1')
				$href = '#/' . $linkType[$x['type']] . '/' . $x['data']; // STATIC PAGE
			else if ($x['type'] == '2')
				$href = '#/' . $linkType[$x['type']] . '/' . urlencode($x['data']); //  EXTERNAL PAGE
			else if ($x['type'] == '3')
				$href = '#/' . $linkType[$x['type']] . '/' . $x['data'] . '/1'; // SLIDESHOW PRESENTATION
			else if ($x['type'] == '4')
				$href = '#/' . $linkType[$x['type']] . '/' . $x['data']; // SLIDESHOW GRID
			else if ($x['type'] == '5')
				$href = '#/' . $linkType[$x['type']] . '/' . $x['data'] . '/' . getGalleryDefaultSlideshow($x['data']) . '/1'; // GALLERY PRESENTATION
			else if ($x['type'] == '6')
				$href = '#/' . $linkType[$x['type']] . '/' . $x['data']; // GALLERY PRESENTATION

			$result .= '<li data-id="' . $x['id'] . '"><a href="' . $href . '" class="' . $linkClass . '">' . $x['title'] . '</a>' . "\n";


			if (strpos($val,";") > 0) {
				$children_array = explode("-", $children);
				if (count($children_array) > 0)
				{
					$result .= '<ul>' . "\n";
					foreach($children_array as $key => $child)
					{
						$x = getMenuData($child);
						if ($x['type'] == '0')
							$href = 'javascript:void(0);';
						else if ($x['type'] == '1')
							$href = '#/' . $linkType[$x['type']] . '/' . $x['data']; // STATIC PAGE
						else if ($x['type'] == '2')
							$href = '#/' . $linkType[$x['type']] . '/' . urlencode($x['data']); //  EXTERNAL PAGE
						else if ($x['type'] == '3')
							$href = '#/' . $linkType[$x['type']] . '/' . $x['data'] . '/1'; // SLIDESHOW PRESENTATION
						else if ($x['type'] == '4')
							$href = '#/' . $linkType[$x['type']] . '/' . $x['data']; // SLIDESHOW GRID
						else if ($x['type'] == '5')
							$href = '#/' . $linkType[$x['type']] . '/' . $x['data'] . '/' . getGalleryDefaultSlideshow($x['data']) . '/1'; // GALLERY PRESENTATION
						else if ($x['type'] == '6')
							$href = '#/' . $linkType[$x['type']] . '/' . $x['data']; // GALLERY PRESENTATION

						$result .= '<li data-id="' . $x['id'] . '"><a href="' . $href . '" class="' . $linkClass . '">' . $x['title'] . '</a></li>' . "\n";
					}
					$result .= '</ul>' . "\n";
				}
			}
		}

		$result .= '</ul>' . "\n";
	} else {
		$result = "ERROR";
	}

	echo $result;
}


function getGalleryDefaultSlideshow($gid)
{
	global $db;

	$q = "SELECT * FROM `galleries` WHERE (`id`='$gid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	$slides_array = explode(";",$a['slideshows']);
	return $slides_array[0];
}

function getSlideshowCover($sid)
{
	global $db;
	global $site;

	$q = "SELECT `cover` FROM `slideshows` WHERE (`id`='$sid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	$cover_id = $a['cover'];
	
	$q2 = "SELECT `name` FROM `photos` WHERE (`id`='$cover_id') LIMIT 1";
	$r2 = $db->query($q2);
	$a2 = $db->fetch_array_assoc($r2);

	$file = $site->url . "/" . "defaul-thumb.jpg";
	if (!empty($a2)) {
		$file = $site->url . "/" . SITE_UPLOADS. "/preview/" . $a2['name'];
	}
	return $file;
}
function getCategoryName($cid)
{
	global $db;

	$q = "SELECT `title` FROM `categories` WHERE (`id`='$cid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $a['title'];
}

function getImage($id,$size = null)
{
	global $db;
	global $site;

	if (!isset($size))
	{
		$img_size = "preview/";
	} else {
		if ($size == "full")
			$img_size = "";
		else if ($size == "preview")
			$img_size = "preview/";
		else if ($size == "thumbnail")
			$img_size = "thumbnail/";
		else
			$img_size = "preview/";

	}
	
	$q2 = "SELECT `name` FROM `photos` WHERE (`id`='$id') LIMIT 1";
	$r2 = $db->query($q2);
	$a2 = $db->fetch_array_assoc($r2);

	$file = $site->url . "/" . "defaul-thumb.jpg";
	if (!empty($a2)) {
		$file = $site->url . "/" . SITE_UPLOADS. "/" . $img_size . $a2['name'];
	}
	return $file;
}

function facebook_like_count($pageID = null) {
	if (is_null($pageID)) {
		return "PageID Error.";
	} else {
		$info = json_decode(file_get_contents('http://graph.facebook.com/' . $pageID));
		return number_format($info->likes);
	}
}

?>