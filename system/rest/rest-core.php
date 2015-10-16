<?php
/*
PhotoPress Core
*/

// Require System Config File
require('../../config.php' );

// Website Dependencies
require( ABSPATH . SITE_INC . '/database.php' );
require( ABSPATH . SITE_INC . '/functions.php' );
require( ABSPATH . SITE_INC . '/classes.php' );
require( ABSPATH . SITE_INC . '/load.php' );
require( ABSPATH . SITE_INC . '/image.php' );
require( ABSPATH . SITE_INC . '/mail.php' );
require( ABSPATH . SITE_INC . '/newsletter.php' );

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

function buildMenu($data, $container = 'mainNav', $offset = 0, $last = 0, $className = 'siteNav')
{
	$linkType = array('','page','gallery','ext','slideshow','gallery','slideshow');
	global $site;
	$result = '<ul id="' . $container . '"';
	if (!isset($className))
		$result .= '>';
	else
		$result .= ' class="nav navbar-nav">';
	

	$parent_array = explode(",",$data);

	for ($i = $offset; $i != count($parent_array)-$last; $i++)
	{
		$key = $i;
		$val = $parent_array[$i];

		// if parent has children
		if (strpos($val,";") > 0)
		{
			list($parent, $children) = explode(";",$val);

			// get menu data for parent
			$x = getMenuData($parent);
			$target = '_self';
			$class = '';

			if ($x['type'] == '3')
			{
				$link = '#/' . $linkType[$x['type']] . '/' . urlencode($x['data']);
				$class = 'ajaxLink';
			}
			else
			{
				if ($x['type'] == '5') {

					$link = '#/' . $linkType[$x['type']] . '/' . $x['data'] . "/" . getGalleryDefaultSlideshow($x['data']) . "/1";
				}
				else if ($x['type'] == '6')
					$link = '#/' . $linkType[$x['type']] . '/' . $x['data'] . "/1";
				else
					$link = '#/' . $linkType[$x['type']] . '/' . $x['data'];
				$class = 'ajaxLink';
			}

			// add the parent to result
			if (!isset($result))
				$result = '<li data-id="' . $parent . '">';
			else
				$result .= '<li data-id="' . $parent . '">';

			if ($x['allowChildren'])
			{
				$result .= '<a data-title="' . $site->name . " - " . $x['title'] . '" href="javascript:void(0);" class="' . $class . ' siteNavExpand" target="' . $target . '">' . $x['title'] . "</a>\n";	
			}
			else
			{
				$result .= '<a data-title="' . $site->name . " - " . $x['title'] . '" href="' . $link . '" class="' . $class . '" rel="' . $x['title'] . '" target="' . $target . '">' . $x['title'] . "</a>\n"; 
			}

			// open sub container for children
			if ($x['allowChildren'])
			{
				$result .= '<ul>' . "\n";
			}

			// iterate all children
			$children_array = explode("-",$children);

			foreach ($children_array as $key2 => $child)
			{
				// get menu data for children
				$y = getMenuData($child);
				$target = '_self';
				$class = '';

				if ($y['type'] == '3')
				{
					$link = '#/' . $linkType[$y['type']] . '/' . urlencode($y['data']);
					$class = 'ajaxLink';
				}
				else
				{
					if ($y['type'] == '5') {

						$link = '#/' . $linkType[$y['type']] . '/' . $y['data'] . "/" . getGalleryDefaultSlideshow($y['data']) . "/1";
					}
					else if ($y['type'] == '6')
						$link = '#/' . $linkType[$y['type']] . '/' . $y['data'] . "/1";
					else
						$link = '#/' . $linkType[$y['type']] . '/' . $y['data'];
					$class = 'ajaxLink';
				}

				$result .= '<li data-id="' . $y['id'] . '"><a data-title="' . $site->name . " - " . $x['title'] . '" href="' . $link . '" class="' . $class . '" rel="' . $y['title'] . '" target="' . $target . '">' . $y['title'] . "</a>\n";
			}

			// close the sub container for children
			if ($x['allowChildren']) {
				$result .= '</ul>' . "\n";
			}
			$result .= '</li>' . "\n";

		// if parent has no children
		}
		else
		{
			// get menu data for parent
			$x = getMenuData($val);
			$target = '_self';
			$class = '';

			if ($x['type'] == '3')
			{
				$link = '#/' . $linkType[$x['type']] . '/' . urlencode($x['data']);
				$class = 'ajaxLink';
			}
			else
			{
				if ($x['type'] == '5') {

					$link = '#/' . $linkType[$x['type']] . '/' . $x['data'] . "/" . getGalleryDefaultSlideshow($x['data']) . "/1";
				}
				else if ($x['type'] == '6')
					$link = '#/' . $linkType[$x['type']] . '/' . $x['data'] . "/1";
				else
					$link = '#/' . $linkType[$x['type']] . '/' . $x['data'];
				$class = 'ajaxLink';
			}

			if (!isset($result))
				$result = '<li data-id="' . $val . '"><a data-title="' . $site->name . " - " . $x['title'] . '" href="' . $link . '" class="' . $class . '" rel="' . $x['title'] . '" target="' . $target . '">' . $x['title'] . "</a>\n";
			else
				$result .= '<li data-id="' . $val . '"><a data-title="' . $site->name . " - " . $x['title'] . '" href="' . $link . '" class="' . $class . '" rel="' . $x['title'] . '" target="' . $target . '">' . $x['title'] . "</a>\n";

			$result .= '</li>';
		}
	}

	$result .= '</ul>';

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

function getSlideshowTitle($sid)
{
	global $db;

	$q = "SELECT `title` FROM `slideshows` WHERE (`id`='$sid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $a['title'];
}

?>