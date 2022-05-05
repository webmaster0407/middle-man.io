<?php

require '../vendor/autoload.php';

use App\SqlConnection as SqlConnection;
use App\SqlCreateTable as SqlCreateTable;
use App\Config as Config;
use App\AttachesModel as AttachesModel;

//connect to database and connect to each tables
$sql = new SqlCreateTable((new SqlConnection())->connect());
// create new tables if not exist
$sql->createTables();

// connect to attaches table via AttachesModel
$attachSql = new AttachesModel((new SqlConnection())->connect());

// get values from dropzone request
$file = $_FILES['file'];

$uploaddir = Config::UPLOAD_FILE_PATH;

$target_file = $uploaddir . '/' . basename($file['name']);

if (move_uploaded_file($file["tmp_name"], $target_file)) {
	$attachSql->insertToAttaches($file['name'], $file['size'], $file['type'], $target_file);
	echo "success";
} else {
	echo "error";
}

?>