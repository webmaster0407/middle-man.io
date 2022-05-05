<?php

require '../vendor/autoload.php';

use App\SqlConnection as SqlConnection;
use App\SqlCreateTable as SqlCreateTable;
use App\Config as Config;
use App\NotesModel as NotesModel;
use App\AttachesModel as AttachesModel;
use App\DestructModel as DestructModel;


// get values from post request
$note = $_POST['note'];
$uploadedFileName = $_POST['uploadedFileName'];
$password = $_POST['password'];
$notification = $_POST['notification'];
$ref_name = $_POST['ref_name'];
$access_by_ip = $_POST['access_by_ip'];
$sms_secure = $_POST['sms_secure'];
$isFileUploaded = $_POST['isFileUploaded'];

// destruct model
$destruct_actions = $_POST['destruct_actions'];
$destruct_confirm = $_POST['destruct_confirm'];


//connect to database and connect to each tables
$sql = new SqlCreateTable((new SqlConnection())->connect());
$noteSql = new NotesModel((new SqlConnection())->connect());
$attachSql = new AttachesModel((new SqlConnection())->connect());
$destructSql = new DestructModel((new SqlConnection())->connect());

// create new tables if not exist
$sql->createTables();

// exit if database is not connected
if ($sql === null) {
	$data = [
		'status' => 'failed',
		'data' => 'Database connection error'
	];
	echo json_encode($data);
	exit();
}

$attach_id = -1;

if ($isFileUploaded == 1) {
	$attached_item = $attachSql->getAttacheByName($uploadedFileName);	
	$attach_id = $attached_item['id'];
}

$hashed_id = '';

$note_id = $noteSql->insertToNote(
		$hashed_id,
	    $note, 
	    $attach_id,
	    $password,
	    $notification,
	    $ref_name,
	    $access_by_ip,
	    $sms_secure
	);

// delete uploaded file

$upload_path = Config::UPLOAD_FILE_PATH;
$files = glob($upload_path . '/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    @unlink($file); // delete file
  }
}


$dateRange = Config::DATE_EXPIRED_RANGE;

$hours = time() + $dateRange[$destruct_actions - 1];
if ($destruct_actions == 1) {
	$hours = -1;
}

$is_expired = 0;
$is_read = 0;
$destruct_id = $destructSql->insertToDestructs(
		$note_id,
	    $destruct_actions, 
	    $destruct_confirm,
	    $hours,	 			// seconds will be expired
	    $is_read,
	    $is_expired,
	);

$responseData = $noteSql->getNoteById($note_id);


$data = [
	'status' => 'success',
	'data' => $responseData
];

echo json_encode($data);

?>