<?php

class Site
{

	public function __construct($data)
	{
		// $_SESSION['site'] = $data;

		$this->title = $data['site_title'];
		$this->name = $data['site_name'];
		$this->owner_name = $data['owner_name'];
		$this->owner_email = $data['owner_email'];
		$this->theme_name = $data['theme_name'];
		$this->name = $data['site_name'];
		$this->domain = $data['site_domain'];
		$this->url = $data['site_url'];
		$this->lang = $data['site_lang'];
		$this->menu_data = $data['menu_data'];
		$this->uploads_dir = $data['site_uploads'];
		$this->public_dir = $data['site_public'];

		// gallery preview
		$this->gallery_prev_width = $data['gallery_prev_width'];
		$this->gallery_prev_height = $data['gallery_prev_height'];
		$this->gallery_prev_quality = $data['gallery_prev_quality'];
		$this->gallery_prev_crop = $data['gallery_prev_crop'];
		
		// gallery thumbnails
		$this->gallery_thumb_width = $data['gallery_thumb_width'];
		$this->gallery_thumb_height = $data['gallery_thumb_height'];
		$this->gallery_thumb_quality = $data['gallery_thumb_quality'];
		$this->gallery_thumb_crop = $data['gallery_thumb_crop'];
		
		// gallery main photo
		$this->gallery_photo_width = $data['gallery_photo_width'];
		$this->gallery_photo_height = $data['gallery_photo_height'];
		$this->gallery_photo_quality = $data['gallery_photo_quality'];
		$this->gallery_photo_crop = $data['gallery_photo_crop'];
	}

	public function setPageType($type)
	{
		$this->pageType = $type;
	}
}

class Theme
{
	var $name;
	var $url;
	var $dir;

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
	var $key;

	public function __construct($pid)
	{

		global $db;
		global $gallery;

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
		$this->key = array_search($a['id'],$gallery->slideshows);

		// get the photos for this slideshow
	}
}

?>