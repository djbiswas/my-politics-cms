<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// header('Access-Control-Allow-Origin: *');

// header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization,X-Requested-With');
// header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1

header("Access-Control-Allow-Origin: https://devuforiawork239.site");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization,X-Requested-With');
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1

$target_dir = "/var/www/clone/my-politics-cms/storage/app/public/post/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$_FILES['file']['name'])) {
    $status = 1;
	$result = json_encode(array('action' => 'upload', 'action_status' => 'Success','filename' => $_FILES['file']['name']));
} else {
	$result = json_encode(array('action' => 'upload', 'action_status' => 'error'));
}
echo $result;
?>

