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

$customer_path_files = ABSPATH . $site->uploads_dir . "/static/";
    if (!file_exists($customer_path_files)) {
    @mkdir($customer_path_files);
}
$customer_path_thumbnails = ABSPATH . $site->uploads_dir . '/static/thumbnail/';
if (!file_exists($customer_path_thumbnails)) {
    @mkdir($customer_path_thumbnails);
}
$customer_path_previews = ABSPATH . $site->uploads_dir . '/static/preview/';
if (!file_exists($customer_path_previews)) {
    @mkdir($customer_path_previews);
}

$options = array(
    'delete_type' => 'POST',
    'db_host' => DB_HOST,
    'db_user' => DB_USER,
    'db_pass' => DB_PASS,
    'db_name' => DB_NAME,
    'db_table' => 'taxonomy',
    'upload_dir' => $customer_path_files,
    'upload_url' => $site->url . "/" . $site->uploads_dir . "/static/",
    'thumbnail' => array(
                    'upload_dir' => $customer_path_thumbnails,
                    'upload_url' => $site->url . "/" . $site->uploads_dir . '/static/thumbnail/',
                    'max_width' => $site->gallery_thumb_width,
                    'max_height' => $site->gallery_thumb_height
                )
);

error_reporting(E_ALL | E_STRICT);
require('logoHandler.php');

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
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
    $index = null, $content_range = null) {
        $file = parent::handle_file_upload(
            $uploaded_file, $name, $size, $type, $error, $index, $content_range
        );
        if (empty($file->error)) {
            $sql = "UPDATE `" . $this->options['db_table'] . "` SET `value`='" . $file->name . "' WHERE `name`='site_logo'";
            $query = $this->db->prepare($sql);
            // $query->bind_param('s', $file->name);
            $query->execute();
            $file->id = $this->db->insert_id;
        }
        return $file;
    }

    protected function set_additional_file_properties($file) {
        parent::set_additional_file_properties($file);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sql = "SELECT `value` FROM `" . $this->options['db_table'] . "` WHERE `name`='site_logo'";
            $query = $this->db->prepare($sql);
            $query->execute();
            $query->bind_result(
                $value
            );
            while ($query->fetch()) {
                $file->value = $value;
            }
        }
    }

    public function delete($print_response = true) {
        $response = parent::delete(false);
        foreach ($response as $name => $deleted) {
            if ($deleted) {
                $sql = "UPDATE `" . $this->options['db_table'] . "` SET `value`='' WHERE `name`='site_logo'";
                $query = $this->db->prepare($sql);
                // $query->bind_param('s', $name);
                $query->execute();
            }
        } 
        return $this->generate_response($response, $print_response);
    }

}

$upload_handler = new CustomUploadHandler($options);

?>