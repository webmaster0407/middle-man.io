<?php
require '../vendor/autoload.php';

use App\SqlConnection as SqlConnection;
use App\SqlCreateTable as SqlCreateTable;
use App\NotesModel as NotesModel;
use App\AttachesModel as AttachesModel;
use App\DestructModel as DestructModel;
use App\EncDec as EncDec;
use App\Config as Config;


// if (isset($_GET['p']) && ($_GET['p'] != '')) {
// 	print_r($_GET['p']);
// }

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


// action name == 1 (deleting after read) && confirm checked
if ($action_name == 1 && $confirm_check == 1) {
	if ($is_expired == 0) {
		$destructSql->updateExpiredTime($destruct_item['id']);	
	}
	$destructSql->updateIsReadState($destruct_item['id']);
	$destructSql->updateIsExpiredState($destruct_item['id']);
	// update time (expired hour)
}


// calc remain dates
$remain_date = 0;
$remain_string = '';

if ($action_name == 1 && $is_expired == 0 ) {

} else {
	$remain_date = time() - $hours;

	if ($remain_date > 0) {
		$is_expired = 1;
		$destructSql->updateIsExpiredState($destruct_item['id']);
	}
	$abs_remain_date = abs($remain_date);
	$days = $abs_remain_date / (60 * 60 * 24);
	$hours = ($abs_remain_date % (60 * 60 * 24) ) / (60 * 60);
	$mins = ($abs_remain_date % (60 * 60 * 24) % (60 * 60)) / 60;
	$secs = ($abs_remain_date % (60 * 60 * 24) % (60 * 60)) % 60;
	if (intval($days) > 0) {
		if (intval($days) == 1) {
			$remain_string .= (intval($days) . ' day ');
		} else {
			$remain_string .= (intval($days) . ' days ');
		}
	}
	if (intval($hours) > 0) {
		if (intval($hours) == 1) {
			$remain_string .= (intval($hours) . ' hour ');
		} else {
			$remain_string .= (intval($hours) . ' hours ');
		}
	}
	if (intval($mins) > 0) {
		if (intval($mins) == 1) {
			$remain_string .= (intval($mins) . ' min ');
		} else {
			$remain_string .= (intval($mins) . ' mins ');
		}
	}
	if (intval($secs) > 0) {
		if (intval($secs) == 1) {
			$remain_string .= (intval($secs) . ' sec ');
		} else {
			$remain_string .= (intval($secs) . ' secs ');
		}
	}
}



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
	<div class="container help_container">
		<a href="<?php echo $base_url . '/pages/help.php'; ?>"><span class="fa fa-info-circle">&nbsp;</span>How it work ?</a>
	</div>

	<?php 
		if ($confirm_check == 1) { // if destruction type is "delete after reading and cinfirm check is checked"
	?>
		<div class="container confirmation_section" style="display: none;">
	<?php
		} else {
			if ($is_expired == 1) {
	?>
		<div class="container confirmation_section" style="display: none;" >
	<?php
			} else {
	?>
		<div class="container confirmation_section" >
	<?php
			}
		}
	?>
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-lg-6">
						<p class="confirmation_noti_info">Ready and Destroy ?</p>		
					</div>
					<div class="col-lg-6 destroy_note_wrapper">
						<a href="#" class="destroy_note" data-val = "<?php echo $id; ?>"><span>X</span> Destroy note now</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<?php 
					if ($action_name == 1) {
				?>
					<p class="confirmation_notification">You're about to read and destroy the note with id <span><?php echo $hashed_id; ?></span>.</p>
				<?php
					} else {
				?>
					<p class="confirmation_notification">The note with id <span><?php echo $hashed_id; ?>&nbsp;</span>destroyed after <span><?php echo $remain_string; ?></span></p>
				<?php 

					} 
				?>
				
				<div class="row confirmation_buttons">
					<div class="col-12">
						<button type="button" class="btn btn-lg btn-primary confirm_yes_btn" data-val = "<?php echo $id; ?>">
							YES SHOW ME THE NOTE
						</button>
						<button type="button" class="btn btn-lg btn-default confirm_no_btn" data-val = "<?php echo $id; ?>">
							NO, NOT NOW
						</button>	
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php 
		if ($confirm_check == 1) { // if destruction type is "delete after reading and cinfirm check is checked"
	?>
		<div class="container note_content">
	<?php
		} else {
			if ($is_expired == 1) {
	?>
		<div class="container note_content">
	<?php
			} else {
	?>
		<div class="container note_content" style="display: none;">
	<?php
			}
		}
	?>
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-lg-6">
						<p>
							<?php 
								if ($is_expired == 1) {
									echo "Note destroyed";
								} else {
									echo "Note content";
								}
							?>
						</p>
					</div>
					<div class="col-lg-6 destroy_note_wrapper">
						<?php 
							if ($is_expired == 1) {
						?>
							<a href=""></a>
						<?php
							} else {
						?>
							<a href="#" class="destroy_note" data-val = "<?php echo $id; ?>"><span>X</span> Destroy note now</a>
						<?php
							}
						?>
						
					</div>
				</div>
			</div>
			<div class="card-body">
				<?php 
					if ($action_name == 1 && $is_expired == 0) {
				?>
					<div class="alert alert-warning"> <span class="fa fa-info-circle">&nbsp;</span>This note was destroyed. If you need to keep it, copy it before closing this window.</div>
				<?php
					}
				?>

				<?php
					if ($is_expired == false) {
				?>
					<div class="alert alert-success"><?php echo $note_text; ?></div>
					<?php 
						if ( isset($file)  ) {
					?>
						<a href="#" class="download_attach" data-val = "<?php echo $attached_id ; ?>" data-val-filename ="<?php echo $file_name; ?>">
							<button class="btn btn-lg btn-primary" >
								<span class="fa fa-download"></span>
							DOWNLOAD FILE
							</button>
						</a>
					<?php
						}
					?>
				<?php
					} else {
				?>
					<div class="alert alert-danger">
						<p>
							The note with id 
							<span>
								<?php echo $hashed_id; ?>&nbsp;
							</span> was 
								<?php if($is_read == 1) echo " read and ";  ?> 
									destroyed 
							<span><?php echo  $remain_string; ?></span> ago</p>
					</div>
					<div class="back_button_wrapper">
						<a href="<?php echo $base_url; ?>">
							<button class="btn btn-sm btn-outline-primary">
								<span class="fa fa-backward"></span>
							Back
							</button>
						</a>
					</div>
				<?php 
					}
				?>
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
	var DELETE_NOTE_URL = "<?php echo $base_url . '/util/delete_note.php' ?>";
	var UPDATE_READSTATE_NOTE_URL = "<?php echo $base_url . '/util/update_read_state.php' ?>";
	var UPDATE_EXPIRED_STATE_URL = "<?php echo $base_url . '/util/update_expired_at_home.php' ?>";
	var DOWNLOAD_FILE_URL = "<?php echo $base_url . '/util/download_file.php' ?>";
	var VERIFY_PWD_URL = "<?php echo $base_url . '/util/verify_pwd.php' ?>";
	var CONFIRM_CHECK = "<?php echo $confirm_check; ?>";
	if (CONFIRM_CHECK == 1) {
		location.hash = '#hidden';
	} 
</script>
<script type="text/javascript" src="<?php echo $base_url . '/assets/js/nt.js' ?>"></script>
</html>
