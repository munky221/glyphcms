<?php
// Admin Panel Required Files
require('../../../config.php');
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

$customer_path_files = ABSPATH . $site->uploads_dir . "/";
    if (!file_exists($customer_path_files)) {
    @mkdir($customer_path_files);
}
$customer_path_thumbnails = ABSPATH . "/" . $site->uploads_dir . '/thumbnail/';
if (!file_exists($customer_path_thumbnails)) {
    @mkdir($customer_path_thumbnails);
}
$customer_path_previews = ABSPATH . "/" . $site->uploads_dir . '/preview/';
if (!file_exists($customer_path_previews)) {
    @mkdir($customer_path_previews);
}

// chown the upload folder
/*$chown_upload_dir = ABSPATH . "uploads/";
$chown_upload_dir_thumbs = ABSPATH . "uploads/thumbnail/";
$chown_upload_dir_prev = ABSPATH . "uploads/preview/";
$chown_user = "root";
chown($chown_upload_dir, $chown_user);
chown($chown_upload_dir_thumbs, $chown_user);
chown($chown_upload_dir_prev, $chown_user);*/

// chown the static folder
/*$chown_static_dir = ABSPATH . "uploads/static/";
$chown_static_dir_thumbs = ABSPATH . "uploads/static/thumbnail/";
$chown_static_dir_prev = ABSPATH . "uploads/static/preview/";
chown($chown_static_dir, $chown_user);
chown($chown_static_dir_thumbs, $chown_user);
chown($chown_static_dir_prev, $chown_user);*/

$options = array(
    'delete_type' => 'POST',
    'db_host' => DB_HOST,
    'db_user' => DB_USER,
    'db_pass' => DB_PASS,
    'db_name' => DB_NAME,
    'db_table' => 'photos',
    'upload_dir' => $customer_path_files,
    'upload_url' => $site->url . "/" . $site->uploads_dir . "/",
    'thumbnail' => array(
                    'upload_dir' => $customer_path_thumbnails,
                    'upload_url' => $site->url . "/" . $site->uploads_dir . '/thumbnail/',
                    'max_width' => $site->gallery_thumb_width,
                    'max_height' => $site->gallery_thumb_height
                )
);

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');

class CustomUploadHandler extends UploadHandler {

    protected function initialize() {
        $this->db = new mysqli(
            $this->options['db_host'],
            $this->options['db_user'],
            $this->options['db_pass'],
            $this->options['db_name']
        );
        parent::initialize();
        $this->db->close();
    }

    protected function handle_form_data($file, $index) {
        $file->title = @$_REQUEST['title'][$index];
        $file->description = @$_REQUEST['description'][$index];

        // slideshow
        global $db;

        if (@$_REQUEST["slideshow"][$index] == "new") {
            $title = @$_REQUEST["slideshow_new"][$index];

            //  check if its really new
            $q = "SELECT * FROM `slideshows` WHERE `title`='$title' LIMIT 1";
            $r = $db->query($q);
            $n = $db->num_rows($r);
            if ($n > 0) {
                // new slideshow exists
                $a = $db->fetch_array_assoc($r);
                $file->slideshow = $a['id'];
            } else {
                // create the new slideshow
                $q2 = "INSERT INTO `slideshows` (`title`, `status`) VALUES ('$title', '2')";
                $r2 = $db->query($q2);
                $file->slideshow = mysql_insert_id();
            }
        }
        else {
            $file->slideshow = @$_REQUEST["slideshow"][$index];
        }

        // categories
        $file->categories = @$_REQUEST["categories"][$index];
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
    $index = null, $content_range = null) {
        $file = parent::handle_file_upload(
            $uploaded_file, $name, $size, $type, $error, $index, $content_range
        );
        if (empty($file->error)) {
            $sql = 'INSERT INTO `'.$this->options['db_table']
                .'` (`name`, `size`, `type`, `title`, `description`, `categories`)'
                .' VALUES (?, ?, ?, ?, ?, ?)';
            $query = $this->db->prepare($sql);
            $query->bind_param(
                'sissss',
                $file->name,
                $file->size,
                $file->type,
                $file->title,
                $file->description,
                $file->categories
            );
            $query->execute();
            $file->id = $this->db->insert_id;

            // get current photo sets for slideshow
            global $db;
            $new_photo_id = $this->db->insert_id;
            $slideshow_target = $file->slideshow;
            $q = "SELECT * FROM `slideshows` WHERE `id`='$slideshow_target' LIMIT 1";
            $r = $db->query($q);
            $a = $db->fetch_array_assoc($r);
            $old_photos = $a['photos'];
            // update the slideshow photos
            if (strlen($old_photos)>0)
                $new_photos = $old_photos . ";" . $new_photo_id;
            else
                $new_photos = $new_photo_id;
            $q = "UPDATE `slideshows` SET `photos` = '$new_photos' WHERE id='$slideshow_target'";
            $r = $db->query($q);
        }
        return $file;
    }

    protected function set_additional_file_properties($file) {
        parent::set_additional_file_properties($file);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = 'SELECT `id`, `type`, `title`, `description`, `categories`, `timestamp` FROM `'
                .$this->options['db_table'].'` WHERE `name`=?';
            $query = $this->db->prepare($sql);
            $query->bind_param('s', $file->name);
            $query->execute();
            $query->bind_result(
                $id,
                $type,
                $title,
                $description,
                $categories,
                $timestamp
            );
            while ($query->fetch()) {
                $file->id = $id;
                $file->type = $type;
                $file->title = $title;
                $file->description = $description;
                $file->categories = $categories;
                $file->timestamp = $timestamp;
            }
        }
    }

    public function delete($print_response = true) {
        $response = parent::delete(false);
        foreach ($response as $name => $deleted) {
            if ($deleted) {
                $sql = 'DELETE FROM `'
                    .$this->options['db_table'].'` WHERE `name`=?';
                $query = $this->db->prepare($sql);
                $query->bind_param('s', $name);
                $query->execute();
            }
        } 
        return $this->generate_response($response, $print_response);
    }

}

$upload_handler = new CustomUploadHandler($options);

?>