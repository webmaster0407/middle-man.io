<?php

require '../vendor/autoload.php';

use App\SqlConnection as SqlConnection;
use App\SqlCreateTable as SqlCreateTable;
use App\Config as Config;
use App\NotesModel as NotesModel;
use App\AttachesModel as AttachesModel;
use App\DestructModel as DestructModel;

//connect to database
$sql = new SqlCreateTable((new SqlConnection())->connect());

// connect to tables
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

// get values from post request
$id = $_POST['id'];	 // note id
$hash = $_POST['hash'];

$note_item = $noteSql->getNoteById($id);

if ( ('#' . $note_item['manual_password']) !== $hash ) {
	$data = [
		'status' => 'failed',
		'data' => 'Invalid decryption password'
	];

	echo json_encode($data);
	exit();
}


$destruction_item = $destructSql->getDestructByNoteId($id);
$destruct_id = $destruction_item['id'];

if ($destruction_item['action_name'] == 1) {
	$destructSql->updateExpiredTime($destruct_id);
	$destructSql->updateIsExpiredState($destruct_id);
}

$note = $destructSql->updateIsReadState($destruct_id);

if ($note === TRUE) {
	$data = [
		'status' => 'success',
		'data' => $note
	];
} else {
	$data = [
		'status' => 'failed',
		'data' => 'something went wrong while deleting from notes table'
	];
}

echo json_encode($data);

?>