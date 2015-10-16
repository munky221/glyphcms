<?php
/*
Admin Panel Core
*/

// Require System Config File
require('../config.php' );

// Admin Panel Dependencies
require( ABSPATH . SITE_INC . '/database.php' );
require( ABSPATH . SITE_INC . '/functions.php' );
require( ABSPATH . SITE_INC . '/classes.php' );


/*
* SITE OBJECT
*/

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


// require( ABSPATH . SITE_INC . '/passwordLibClass.php' );
// require( ABSPATH . SITE_INC . '/passwordLib.php' );

// include login class
// require_once( ABSPATH . SITE_INC . '/authentication.php' );

// include the config
// require_once( ABSPATH . SITE_INC . '/login/config/config.php' );

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once( ABSPATH . SITE_INC . '/login/translations/en.php' );

// include the PHPMailer library
require_once( ABSPATH . SITE_INC . '/login/libraries/PHPMailer.php' );

// load the login class
require_once( ABSPATH . SITE_INC . '/login/classes/Login.php' );

require_once( ABSPATH . SITE_INC . '/login/libraries/password_compatibility_library.php' );

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();


$pageArray = array(
	array("0","Dashboard","dashboard","index.php","fa-dashboard",null),
	array("1","Media","media","photos.php","fa-picture-o",
		array(
			array("Photo Manager","photo","photos.php","fa-th"),
			array("Upload","upload","upload.php","fa-upload"),
			array("Settings","media-settings","media-settings.php","fa-gear")
		)
	),
	array("1","Manage","manage","slideshows.php","fa-flask",
		array(
			array("Slideshows","slideshows","slideshows.php","fa-ellipsis-h"),
			array("Slideshow Categories","categories","categories.php","fa-list"),
			array("Galleries","galleries","galleries.php","fa-th-large"),
			array("Static Pages","pages","pages.php","fa-pencil")
		)
	),
	array("1","System","system","menu.php","fa-cogs",
		array(
			array("Site Menu","site-menu","menu.php","fa-sitemap"),
			array("Options","site-options","options.php","fa-puzzle-piece"),
			array("Profile","user-profile","profile.php","fa-user")
		)
	)
);




function buildAdminMenu($array,$page)
{
	$menu = '<ul class="nav navbar-nav side-nav">' . "\n";

	for ($i = 0; $i != count($array); $i++)
	{
		$menu .= '<li class="';
		
		// if current page, add active class
		if ($page->pageSlug == $array[$i][2]) $menu .= ' active';
		
		// if parent page, add open class
		if ($page->pageParentSlug == $array[$i][2]) $menu .= ' active keep-open';

		// if have sublinks, add dropdown class
		if ($array[$i][0] != 0)
		{
			$menu .= ' dropdown';
			$menu .='"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa ' . $array[$i][4] . '"></i>' . $array[$i][1] . ' &nbsp; <i class="fa fa-caret-down"></i></a>' . "\n";

			// sublinks
			$menu .='<ul class="dropdown-menu">' . "\n";
			for ($ii = 0; $ii != count($array[$i][5]); $ii++) {
				$menu .= '<li class="';
				if ($page->pageSlug == $array[$i][5][$ii][1]) $menu .= ' active';
				$menu .= '"><a href="' . $array[$i][5][$ii][2] . '"><i class="fa ' . $array[$i][5][$ii][3] . '"></i>' . $array[$i][5][$ii][0] . '</a></li>' . "\n";
			}
			$menu .= '</ul></li>' . "\n";
		}
		else
		{
			$menu .='"><a href="' . $array[$i][3] . '"><i class="fa ' . $array[$i][4] . '"></i>' . $array[$i][1] . '</a></li>' . "\n";
		}
	}
	$menu .= '</ul>' . "\n";
	echo $menu;
}

function buildMenu($data, $container = 'mainNav', $offset = 0, $last = 0, $className = 'siteNav')
{
	$linkType = array('','page','ext','slideshow','slideshow','gallery','gallery');
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

function makeSlug($str)
{
	 // replace non letter or digits by -
	$str = preg_replace('~[^\\pL\d]+~u', '-', $str);

	// trim
	$str = trim($str, '-');

	// transliterate
	$str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);

	// lowercase
	$str = strtolower($str);

	// remove unwanted characters
	$str = preg_replace('~[^-\w]+~', '', $str);

	if (empty($str))
	{
	return 'n-a';
	}

	return $str;
}

// check whether a particular option is checked
function IsChecked($chkname,$value)
{
	if(!empty($_REQUEST[$chkname]))
	{
		foreach($_REQUEST[$chkname] as $chkval)
		{
			if($chkval == $value)
			{
				return true;
			}
		}
	}
	return false;
}

// check if category is in a specific array
function isCategory($array,$value)
{
	$n = count($array);
	for ($i=0;$i!=$n;$i++)
	{
		if ($array[$i] == $value)
		{
			return true;
			break;
		}
	}
	return false;
}

function getStatus($id,$table)
{
	global $db;
	$statusArray = array('Draft', 'Pending', 'Published', 'Archived', 'NA');
	$q = "SELECT * FROM '$table' WHERE `id`='$id'";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $statusArray[$a['status']];
}

function getPhotoDir($id)
{
	global $db;
	$q = "SELECT * FROM `photos` WHERE `id`='$id'";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $statusArray[$a['slideshow']];
}

function _status($n)
{
	$statusArray = array('Draft', 'Pending', 'Published', 'Archived', 'NA');
	echo $statusArray[$n];
}

function rrmdir($dir) {
	foreach(glob($dir . '/*') as $file) { 
		if(is_dir($file)) rrmdir($file); else unlink($file); 
	} rmdir($dir); 
}

function getPhotoData($id)
{
	global $db;

	$q = "SELECT * FROM `photos` WHERE (`id`='$id') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	return $a;
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

	$q = "SELECT `cover` FROM `slideshows` WHERE (`id`='$sid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);

	$cover_id = $a['cover'];
	
	$q2 = "SELECT `name` FROM `photos` WHERE (`id`='$cover_id') LIMIT 1";
	$r2 = $db->query($q2);
	$a2 = $db->fetch_array_assoc($r2);

	$file = SITE_URL . "/" . SITE_ADMIN . "/css/defaul-thumb.jpg";
	if (!empty($a2)) {
		$file = SITE_URL . "/" . SITE_UPLOADS. "/thumbnail/" . $a2['name'];
	}
	return $file;
}

function getSlideshowPhotoCount($sid)
{
	global $db;

	$q = "SELECT `photos` FROM `slideshows` WHERE `id`='$sid' ORDER BY `title` ASC";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	$photos = explode(";", $a['photos']);
	if (count($photos) == 0)
		$result = "empty";
	else if (count($photos) == 1)
		$result = "1 photo";
	else
		$result = number_format(count($photos)) . " photos";

	return $result;
}

function getSlideshowTitle($sid)
{
	global $db;

	$q = "SELECT `title` FROM `slideshows` WHERE (`id`='$sid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $a['title'];
}

function curPageURL()
{
	$pageURL = 'http';
	// if ($_SERVER["HTTPS"] == "on") { $pageURL .= "s"; }
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function makePagination($g,$total,$page,$hash=null)
{
	if (!isset($g['p'])) {
		$p = 1;
	} else {
		$p = $g['p'];
	}

	$pagination = "<ul class=\"pagination pull-right\">\n";
	for ($i = 1; $i <= $total; $i++) {
		$pagination .= "<li class=\"";
		if ($i == $p) {
			$pagination .= "active";
		}
		$link = $page->pageSlug . ".php?c=" . $total . "&p=" . $i;
		if (!is_null($hash)) {
			// check if $hash has the # sign in it
			if (strpos($hash,"#") !== false) {
				$link .= $hash;
			} else {
				$link .= "#" . $hash;
			}
			
		}
		$pagination .= "\" id=\"" . $i . "\"><a href=\"" . $link . "\">" . $i . "</a></li>\n";
	}
	$pagination .= "</ul> <!-- /.pagination -->\n";

	echo $pagination;
}

function getCategoryName($cid)
{
	if ($cid == "empty") {
		return "Uncategorized";
	} else {
		global $db;
		$q = "SELECT `title` FROM `categories` WHERE (`id`='$cid') LIMIT 1";
		$r = $db->query($q);
		$a = $db->fetch_array_assoc($r);
		return $a['title'];
	}
}

function getMenuData($mid)
{
	global $db;
	$q = "SELECT * FROM `menu` WHERE (`id`='$mid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $a;
}

function search_array($needle, $haystack)
{
	 if(in_array($needle, $haystack))
	 {
		  return true;
	 }
	 foreach($haystack as $element)
	 {
		  if(is_array($element) && search_array($needle, $element))
			   return true;
	 }
   return false;
}

function getUserName($uid)
{
	global $db;
	$q = "SELECT `title` FROM `categories` WHERE (`id`='$cid') LIMIT 1";
	$r = $db->query($q);
	$a = $db->fetch_array_assoc($r);
	return $a['title'];
}

function multiexplode($delimiters,$string) {
	$ary = explode($delimiters[0],$string);
	array_shift($delimiters);
	if($delimiters != NULL) {
		foreach($ary as $key => $val) {
			 $ary[$key] = multiexplode($delimiters, $val);
		}
	}
	return $ary;
}

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


/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() )
{
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img )
	{
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

function getAvailableThemes()
{
	global $site;
	$dir = ABSPATH . "/" . $site->site_public;
	$result = dirToArray($dir);
	return $result;
}

function checkCategoryExist($slug)
{
	global $db;
	$q = "SELECT * FROM `categories` WHERE `slug`='$slug' LIMIT 1";
	$r = $db->query($q);
	$a = $db->num_rows($r);

	return $a;
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
?>