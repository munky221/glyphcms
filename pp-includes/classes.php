<?php

class Site
{
	var $system_name;
	var $system_title;
	var $system_desc;
	var $system_version;
	var $system_url;
	var $system_serial;
	var $system_publisher;
	var $system_publisher_url;

	var $title;
	var $name;
	var $owner_name;
	var $owner_email;
	var $theme_name;
	var $theme_url;
	var $site_lang;
	var $site_version;
	var $site_homepage_default;
	var $site_homepage_target_type;
	var $site_homepage_target;
	var $url;
	var $domain;
	var $menu_data;
	var $subscription_due;
	var $uploads_dir;
	var $public_dir;
	var $library_dir;
	var $includes_dir;
	var $site_logo;

	var $gallery_prev_width;
	var $gallery_prev_height;
	var $gallery_prev_quality;
	var $gallery_prev_crop;
	var $gallery_thumb_width;
	var $gallery_thumb_height;
	var $gallery_thumb_quality;
	var $gallery_thumb_crop;
	var $gallery_photo_width;
	var $gallery_photo_height;
	var $gallery_photo_quality;
	var $gallery_photo_crop;

	var $display_slider_filmstrip;
	var $display_slider_infobar;

	var $share_facebook;
	var $share_googleplus;
	var $share_twitter;
	var $share_pinterest;
	var $share_linkedin;
	var $share_reddit;

	var $social_facebook;
	var $social_facebook_like;
	var $social_googleplus;
	var $social_twitter;
	var $social_behance;
	var $social_linkedin;
	var $social_instagram;
	var $social_tumblr;

	var $google_analytics;
	var $google_site_verification;

	var $pageType;

	public function __construct($data)
	{
		// $_SESSION['site'] = $data;
		$this->system_name = $data['system_name'];
		$this->system_title = $data['system_title'];
		$this->system_desc = $data['system_description'];
		$this->system_version = $data['system_version'];
		$this->system_publisher = $data['system_publisher'];
		$this->system_url = $data['system_url'];
		$this->system_serial = $data['system_serial'];
		$this->system_publisher_url = $data['system_publisher_url'];

		$this->title = $data['site_title'];
		$this->name = $data['site_name'];
		$this->owner_name = $data['owner_name'];
		$this->owner_email = $data['owner_email'];
		$this->theme_name = $data['theme_name'];
		$this->theme_url = $data['site_url'] . "system/themes/" . $data['theme_name'] . "/";
		$this->name = $data['site_name'];
		$this->domain = $data['site_domain'];

		if (substr($data['site_url'], -1) !== "/") {
			$this->url = $data['site_url'] . "/";
		} else {
			$this->url = $data['site_url'];
		}

		$this->site_lang = $data['site_lang'];
		if ($data['site_homepage_default'] == "true")
			$this->site_homepage_default = true;
		else
			$this->site_homepage_default = false;
		$this->site_homepage_target_type = $data['site_homepage_target_type'];
		$this->site_homepage_target = $data['site_homepage_target'];
		$this->menu_data = $data['menu_data'];
		$this->uploads_dir = $data['site_uploads'];
		$this->public_dir = $data['site_public'];
		$this->library_dir = $data['site_library'];
		$this->includes_dir = $data['site_includes'];
		$this->subscription_due = $data['subscription_due'];
		$this->site_logo = $data['site_logo'];


		if ($data['display_slider_filmstrip'] == "true")
			$this->display_slider_filmstrip = true;
		else
			$this->display_slider_filmstrip = false;
		if ($data['display_slider_infobar'] == "true")
			$this->display_slider_infobar = true;
		else
			$this->display_slider_infobar = false;

		// social sharing
		if ($data['share_facebook'] == "true")
			$this->share_facebook = true;
		else
			$this->share_facebook = false;
		if ($data['share_googleplus'] == "true")
			$this->share_googleplus = true;
		else
			$this->share_googleplus = false;
		if ($data['share_twitter'] == "true")
			$this->share_twitter = true;
		else
			$this->share_twitter = false;
		if ($data['share_pinterest'] == "true")
			$this->share_pinterest = true;
		else
			$this->share_pinterest = false;
		if ($data['share_linkedin'] == "true")
			$this->share_linkedin = true;
		else
			$this->share_linkedin = false;
		if ($data['share_reddit'] == "true")
			$this->share_reddit = true;
		else
			$this->share_reddit = false;

		$this->social_facebook = $data['social_facebook'];
		$this->social_facebook_like = $data['social_facebook_like'];
		$this->social_googleplus = $data['social_googleplus'];
		$this->social_twitter = $data['social_twitter'];
		$this->social_behance = $data['social_behance'];
		$this->social_linkedin = $data['social_linkedin'];
		$this->social_instagram = $data['social_instagram'];
		$this->social_tumblr = $data['social_tumblr'];

		// gallery preview
		$this->gallery_prev_width = $data['gallery_prev_width'];
		$this->gallery_prev_height = $data['gallery_prev_height'];
		$this->gallery_prev_quality = $data['gallery_prev_quality'];
		if ($data['gallery_prev_crop'] == "true")
			$this->gallery_prev_crop = true;
		else
			$this->gallery_prev_crop = false;
		
		// gallery thumbnails
		$this->gallery_thumb_width = $data['gallery_thumb_width'];
		$this->gallery_thumb_height = $data['gallery_thumb_height'];
		$this->gallery_thumb_quality = $data['gallery_thumb_quality'];
		if ($data['gallery_thumb_crop'] == "true")
			$this->gallery_thumb_crop = true;
		else
			$this->gallery_thumb_crop = false;
		
		// gallery main photo
		$this->gallery_photo_width = $data['gallery_photo_width'];
		$this->gallery_photo_height = $data['gallery_photo_height'];
		$this->gallery_photo_quality = $data['gallery_photo_quality'];
		if ($data['gallery_photo_crop'] == "true")
			$this->gallery_photo_crop = true;
		else
			$this->gallery_photo_crop = false;

		$this->google_analytics = $data['google_analytics'];
		$this->google_site_verification = $data['google_site_verification'];
	}

	public function setPageType($type)
	{
		$this->pageType = $type;
	}

	public function setImageDimensions($targ, $width, $height, $quality, $crop)
	{
		global $db;
		if ($targ == "thumb")
		{
			$key_width = "gallery_thumb_width";
			$key_height = "gallery_thumb_height";
			$key_quality = "gallery_thumb_quality";
			$key_crop = "gallery_thumb_crop";
		}
		else if ($targ == "prev")
		{
			$key_width = "gallery_prev_width";
			$key_height = "gallery_prev_height";
			$key_quality = "gallery_prev_quality";
			$key_crop = "gallery_prev_crop";
		}
		else if ($targ == "photo")
		{
			$key_width = "gallery_photo_width";
			$key_height = "gallery_photo_height";
			$key_quality = "gallery_photo_quality";
			$key_crop = "gallery_photo_crop";
		}

		if ($crop == "1")
		{
			$crop = "true";
			$crop_bool = true;
		}
		else
		{
			$crop = "false";
			$crop_bool = false;
		}
			
		$q = "UPDATE `taxonomy` SET `value`='$width' WHERE name='$key_width'";
		$r = $db->query($q);
		$q2 = "UPDATE `taxonomy` SET `value`='$height' WHERE name='$key_height'";
		$r2 = $db->query($q2);
		$q3 = "UPDATE `taxonomy` SET `value`='$quality' WHERE name='$key_quality'";
		$r3 = $db->query($q3);
		$q4 = "UPDATE `taxonomy` SET `value`='$crop' WHERE name='$key_crop'";
		$r4 = $db->query($q4);
	}
	public function setTaxonomy($data)
	{
		global $db;
			
		if (is_array($data))
		{
			foreach($data as $key => $value)
			{
				$q = "UPDATE `taxonomy` SET `value`='$value' WHERE name='$key'";
				$r = $db->query($q);
			}
		}
	}
}


// Page Object
class Page
{
	public $pageTitle;
	public $pageSlug;
	public $pageIcon;
	public $pageParent;
	public $pageParentSlug;
	public $pageParentIcon;
	public $pageContent;
	public $pageDescription;
	public $pageExcerpt;
	public $pageMenu;
	public $pageIncludes;
}




class Theme
{
	var $name;
	var $url;
	var  $dir;

	public function __construct($name)
	{
		global $site;
		$this->name = $name;
		$this->url = $site->url . "/themes/" . $name . "/";
		$this->dir = ABSPATH . $site->public_dir . "/themes/" . $name;
	}
}

class PPage
{
	var $id;
	var $title;
	var $content;
	var $excerpt;
	var $slug;
	var $status;
	var $timestamp;

	
	public function __construct($pid)
	{
		global $db;

		// Get the gallery data
		$q = "SELECT * FROM `pages` WHERE `id`='$pid' LIMIT 1";
		$r = $db->query($q);
		$a = $db->fetch_array_assoc($r);

		$this->id = $a['id'];
		$this->title = $a['title'];
		$this->content = $a['content'];
		$this->excerpt = $a['excerpt'];
		$this->slug = $a['slug'];
		$this->status = $a['status'];
		$this->timestamp = $a['timestamp'];
	}
	public function getTitle()
	{
		return $this->title;
	}
	public function getContent()
	{
		return $this->content;
	}
	public function getSlug()
	{
		return $this->slug;
	}
	public function getID()
	{
		return $this->id;
	}
}

class Gallery
{
	var $title;
	var $excerpt;
	var $slug;
	var $slideshows;
	var $categories;
	var $id;

	public function __construct($pid)
	{

		global $db;

		// Get the gallery data
		$q = "SELECT * FROM `galleries` WHERE `id`='$pid' LIMIT 1";
		$r = $db->query($q);
		$a = $db->fetch_array_assoc($r);

		$this->id = $a['id'];
		$this->title = $a['title'];
		$this->excerpt = $a['excerpt'];
		$this->slug = $a['slug'];
		$this->slideshows = explode(';',$a['slideshows']);
		$this->categories = $a['categories'];
	}

	var $gallery;
	public function buildGallery($container = null, $class = null, $offset = 0, $last = 0)
	{
		global $db;

		// start the container
		$gallery = '<ul';
		if (isset($container))
			$gallery .= ' id="'. $container .'"';
		if (isset($class))
			$gallery .= ' class="' . $class . '"';
		$gallery .= '>';

		// gallery loop
		$s_array = $this->slideshows;

		for ($i = $offset; $i != count($s_array)-$last; $i++)
		{
			$sid = $s_array[$i];
			// query slideshow
			$q = "SELECT * FROM `slideshows` WHERE `id`='$sid'";
			$r = $db->query($q);
			$a = $db->fetch_array_assoc($r);

			// append the gallery output
			$image_src = getSlideshowCover($a['id'],$db);
			$gallery .= '<li class="item"><a href="?pid=' . $a['id'] . '&gid=' . $this->id . '&t=4&i=start"><img src="' . $image_src  . '"></a></li>';
		}

		// close the container
		$gallery .= "</ul>";

		echo $gallery;
	}
}

class Slideshow
{
	var $cover;
	var $title;
	var $excerpt;
	var $id;
	var $category;
	var $photos;

	public function __construct($pid)
	{

		global $db;

		// Get the slideshow data
		$q = "SELECT * FROM `slideshows` WHERE `id`='$pid' LIMIT 1";
		$r = $db->query($q);
		$a = $db->fetch_array_assoc($r);

		$this->id = $a['id'];
		$this->title = $a['title'];
		$this->excerpt = $a['excerpt'];
		$this->category = explode(';',$a['category']);
		$this->cover = explode(';',$a['cover']);
		$this->photos = explode(';',$a['photos']);

		// get the photos for this slideshow
	}
}

class Paginator {
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $high;
	var $limit;
	var $return;
	var $default_ipp = 25;
	var $search;
	var $filter;
	var $status;

	function Paginator()
	{
		$this->current_page = 1;
		$this->mid_range = 7;
		$this->search = (isset($_GET['search'])) ? $_GET['search'] : "";
		$this->filter = (isset($_GET['filter'])) ? $_GET['filter'] : "";
		$this->status = (isset($_GET['status'])) ? $_GET['status'] : "";
		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;
	}

	function paginate()
	{
		if($_GET['ipp'] == 'All')
		{
			$this->num_pages = ceil($this->items_total/$this->default_ipp);
			$this->items_per_page = $this->default_ipp;
		}
		else
		{
			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
			$this->num_pages = ceil($this->items_total/$this->items_per_page);
		}
		$this->current_page = (int) $_GET['page']; // must be numeric > 0
		if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;
 
		if($this->num_pages > 10)
		{
			if ($this->current_page != 1 And $this->items_total >= 10)
			{
				$this->return = "<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$prev_page&ipp=$this->items_per_page";
				if (isset($_GET['search']))
					$this->return .= "&search=$this->search";
				else if (isset($_GET['filter']))
					$this->return .= "&filter=$this->filter";
				else if (isset($_GET['status']))
					$this->return .= "&status=$this->status";
				$this->return .= "\">«</a> ";
			}
			else
			{
				$this->return = "<li><span class=\"inactive\" href=\"#\">«</span></li>";
			}
 
			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);
 
			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);
 
			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= "<li><span>...</span></li>";
				// loop through all pages. if first, last, or in range, display
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
					if ($i == $this->current_page And $_GET['page'] != 'All')
					{
						$this->return .= "<li class=\"active\"><a title=\"Go to page $i of $this->num_pages\" href=\"#\">$i</a></li> ";
					}
					else
					{
						$this->return .= "<li><a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page";
						if (isset($_GET['search']))
							$this->return .= "&search=$this->search";
						if (isset($_GET['filter']))
							$this->return .= "&filter=$this->filter";
						if (isset($_GET['status']))
							$this->return .= "&status=$this->status";
						$this->return .= "\">$i</a></li> ";
					}
				}
				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= "<li><span>...</span></li>";
			}

			if (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($_GET['page'] != 'All'))
			{
				$this->return .= "<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$next_page&ipp=$this->items_per_page";
				if (isset($_GET['search']))
					$this->return .= "&search=$this->search";
				if (isset($_GET['filter']))
					$this->return .= "&filter=$this->filter";
				if (isset($_GET['status']))
					$this->return .= "&status=$this->status";
				$this->return .= "\">»</a></li> ";
			}
			else
			{
				$this->return .= "<li><span class=\"inactive\" href=\"#\">»</span></li>";
			}
		}
		else
		{
			for($i=1;$i<=$this->num_pages;$i++)
			{
				if ($i == $this->current_page) {
					$this->return .= "<li class=\"active\"><a href=\"#\">$i</a></li> ";
				}
				else
				{
					$this->return .= "<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page";
					if (isset($_GET['search']))
						$this->return .= "&search=$this->search";
					if (isset($_GET['filter']))
						$this->return .= "&filter=$this->filter";
					if (isset($_GET['status']))
						$this->return .= "&status=$this->status";
					$this->return .= "\">$i</a></li> ";
				}
			}
			// $this->return .= "<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All\">All</a></li> \n";
		}
		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = ($_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
		$this->limit = ($_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";
	}

	function display_items_per_page()
	{
		$items = '';
		$ipp_array = array(10,25,50,100,'All');
		foreach($ipp_array as $ipp_opt)    $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value;return false\">$items</select>\n";
	}

	function display_jump_menu()
	{
		for($i=1;$i<=$this->num_pages;$i++)
		{
			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
		}
		return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page';return false\">$option</select>\n";
	}

	function display_pages()
	{
		return $this->return;
	}
}

?>