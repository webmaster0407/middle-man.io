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

$note = $noteSql->getNoteById($id);

if ($noteSql->deleteNoteById($id) === TRUE) {
	if ($destructSql->updateIsExpiredState($note['id']) == TRUE) {
		$destruction_item = $destructSql->getDestruct($note['id']);
		$data = [
			'status' => 'success',
			'note' => $note,
			'destruct' => $destruction_item
		];
	} else {
		$data = [
			'status' => 'failed',
			'data' => 'something went wrong while updating from destruction table'
		];
	}	
} else {
	$data = [
		'status' => 'failed',
		'data' => 'something went wrong while deleting from notes table'
	];
}

echo json_encode($data);

?>