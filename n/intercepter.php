<?php
require '../vendor/autoload.php';

use App\SqlConnection as SqlConnection;
use App\SqlCreateTable as SqlCreateTable;
use App\NotesModel as NotesModel;
use App\AttachesModel as AttachesModel;
use App\DestructModel as DestructModel;
use App\EncDec as EncDec;
use App\Config as Config;


//connect to database
$sql = new SqlCreateTable((new SqlConnection())->connect());
// create new tables if not exist
$sql->createTables();

// exit if database is not connected
if ($sql === null) {
	echo 'YOU SHOULD EXTABLISH THE DATABASE';
	exit();
}

// connect to tables
$noteSql = new NotesModel((new SqlConnection())->connect());
$attachSql = new AttachesModel((new SqlConnection())->connect());
$destructSql = new DestructModel((new SqlConnection())->connect());


$base_url  = Config::BASE_URL;
$error_url = Config::ERROR_URL;

$hashed_id = '';  // get hashed_id from given url
if (isset($_GET['p']) && ($_GET['p'] != '')) {
	$hashed_id = $_GET['p'];
}

$request_url = $_SERVER['REQUEST_URI'];

// get information about the note that are in note table.
$note_item = $noteSql->getNoteByHashedId($hashed_id);


if ($note_item === null) {
	header("Location: $error_url");
}


$id = $note_item['id'];		// note id
$note_text = $note_item['note'];
$manual_password = $note_item['manual_password'];
$destruct_notification = $note_item['destruct_notification'];
$ref_name = $note_item['ref_name'];

// get attached file information about this note form attached_id in note table
$file = null;
$attached_id = $note_item['attached_id'];
if ($attached_id > 0 && $attached_id != -1) {	// if attached file exist
	$file_item = $attachSql->getAttache($attached_id);
	$file_id = $file_item['id'];
	$file_name = $file_item['name'];
	$file = $file_item['content'];
} else {	// not exist
	// do nothing
}


// get destruction information about this note from note id
$destruct_item = $destructSql->getDestructByNoteId($id);
$action_name = $destruct_item['action_name'];
$confirm_check = $destruct_item['confirm_check'];
$hours = $destruct_item['hours'];
$is_read = $destruct_item['is_read'];
$is_expired = $destruct_item['is_expired'];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Private-X</title>
	<?php 
		include_once('../includes/header_common_links.php');
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url . '/assets/css/nt.css'; ?>">
</head>
<body>
	<?php 
		include_once('../includes/ntpage/header.php');
	?>
	<div class="container help_container" style="margin-bottom: 30px;">
		<a href="<?php echo $base_url . '/pages/help.php'; ?>"><span class="fa fa-info-circle">&nbsp;</span>How it work ?</a>
	</div>
	<div class="container main_section" style="display: none;">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<p style="font-size: 28px; padding: 0; font-weight: 600;">
						Note Content
						</p>
					</div>
					<div class="card-body">
						<p>Can not load, because the decryt key is not correct!</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row"  style="margin-top: 30px;">
			<div class="col-12">
				<a href="<?php echo $base_url; ?>">
					<button type="button" class="btn btn-lg btn-success go_home_htn" style="border-radius: 25px; font-size: 14px; font-weight: 600;">
						<span class="fa fa-backward">&nbsp;</span>GO HOME
					</button>
				</a>
			</div>
		</div>
	</div>
	<?php 
		include_once('../includes/ntpage/footer.php');
	?>
</body>
<?php 
	include_once('../includes/footer_common_scripts.php');
?>
<script type="text/javascript">
	var BASE_URL = "<?php echo $base_url; ?>";
	var VERIFY_PWD_URL = "<?php echo $base_url . '/util/verify_pwd.php' ?>";
	var CONFIRM_CHECK = "<?php echo $confirm_check; ?>";
</script>
<script type="text/javascript">
	var id = "<?php echo $id; ?>";
	var hash_id = "<?php echo $hashed_id?>";
	var hash = location.hash;
	if ( CONFIRM_CHECK == 1 ) {
		var elem = $(this);
		// update note state to is_read true and show notes if hash is correct
		$.ajax({
			url: VERIFY_PWD_URL,
			type: "POST",
			data: {
				id: id,
				hash: hash
			},
			beforeSend: function() {
				$.blockUI({ message: '<span class="spinner spinner-primary"></span>' });
			},
			success: function(data) {
				data = $.parseJSON(data);
				if (data.status === "success") {
					$.unblockUI();
					window.location.href =  BASE_URL + '/n/index.php?p=' + hash_id + hash;
				} else {
					$.unblockUI();
					swal({
	                    title: data.data,
	                    type: 'error',
	                    confirmButtonText: "Ok, Got it!",
	                    confirmButtonClass: 'btn-danger',
	                    showConfirmButton: true,
	                });
	                $('.main_section').show();
				}
			},
			error: function(error) {
				$.unblockUI();
				console.log(error);
			}
		});
	} else {
		window.location.href =  BASE_URL + '/n/index.php?p=' + hash_id + hash;
	}
</script>
</html>
