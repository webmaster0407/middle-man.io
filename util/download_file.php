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



// get values from download request
$attached_id = $_POST['id'];

$attach_item = $attachSql->getAttache($attached_id);
$content = base64_decode($attach_item['content']);
$name = $attach_item['name'];
$size = $attach_item['size'];
$type = $attach_item['type'];


header("Content-type: " . $type);
header("Content-length: " . $size);
header("Content-Disposition: attachment; filename='" . $name . "'");
header("Content-Description: Encoded and Decoded data");

echo ($content);

?>