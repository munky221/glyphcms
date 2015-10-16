<?php
/*
Generic Site Functions
*/

function _e($text)
{
	echo $text;
}

function _d($t)
{
	$timestamp = strtotime($t);
	echo "<span class='time-date'>" . date('M d, Y', $timestamp) . "</span>";
	echo " <span class='time-hour'>" . date('h:i A', $timestamp) . "</span>";
}

function _c($n)
{
	// first strip any formatting;
	$n = (0+str_replace(",","",$n));

	// is this a number?
	if(!is_numeric($n)) return false;

	// now filter it;
	if($n>1000000000000) echo round(($n/1000000000000),1).' trillion';
	else if($n>1000000000) echo round(($n/1000000000),1).' billion';
	else if($n>1000000) echo round(($n/1000000),1).' million';
	else if($n>1000) echo round(($n/1000),1).' thousand';

	echo number_format($n);
}

function getExcerpt($str, $startPos=0, $maxLength=100, $showLast=false)
{
	if(strlen($str) > $maxLength) {
		$excerpt   = substr($str, $startPos, $maxLength-7);
		$lastSpace = strrpos($excerpt, ' ');
		if ($lastSpace > 4)
			$excerpt   = substr($excerpt, 0, $lastSpace);
		else
			$excerpt   = substr($excerpt, 0, $maxLength);
		$lastBits = substr($str, strlen($str)-4, 4);
		$excerpt  .= '... ';
		if ($showLast)
			$excerpt  .= $lastBits;
	} else {
		$excerpt = $str;
	}
	
	return $excerpt;
}

function getHeader($file = null)
{
	global $theme;
	if (isset($file))
	{
		include($theme->dir . "/" . $file);
	}
	else
	{
		include($theme->dir . '/header.php');
	}
}

function getSidebar($file = null)
{
	global $theme;
	if (isset($file))
	{
		include($theme->dir . "/" . $file);
	}
	else
	{
		include($theme->dir . '/sidebar.php');
	}
}

function getFooter($file = null)
{
	global $theme;
	if (isset($file))
	{
		include($theme->dir . "/" . $file);
	}
	else
	{
		include($theme->dir . '/footer.php');
	}
}

function cleanInput($input)
{
	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);
 
	$output = preg_replace($search, '', $input);
	return $output;
}
function cleanInput2($input)
{
	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU'    // Strip style tags properly
	);
 
	$output = preg_replace($search, '', $input);
	return $output;
}
function sanitize($input,$skip = false)
{
    if (is_array($input))
    {
        foreach($input as $var=>$val)
        {
            $output[$var] = sanitize($val,$skip);
        }
    }
    else
    {
        if (get_magic_quotes_gpc())
        {
            $input = stripslashes($input);
        }
        if (!$skip)
        	$input  = cleanInput($input);
        else
        	$input = cleanInput2($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}
function html_decode($input)
{
    if (is_array($input))
    {
        foreach($input as $var=>$val)
        {
            $output[$var] = html_decode($val);
        }
    }
    else
    {
        if (get_magic_quotes_gpc())
        {
            $input = stripslashes($input);
        }
        $output = html_entity_decode($input);
    }
    return $output;
}

function dirToArray($dir)
{
	$result = array(); 

	$cdir = scandir($dir); 
	foreach ($cdir as $key => $value) 
	{ 
		if (!in_array($value,array(".",".."))) 
		{ 
			if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
			{ 
				$result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
			} 
			else 
			{ 
				// $result[] = $value; 
			} 
		} 
	} 

	return $result; 
}

?>