<?php
require 'vendor/autoload.php';

use App\SqlConnection as SqlConnection;
use App\SqlCreateTable as SqlCreateTable;
use App\AttachesModel as AttachesModel;
use App\Config as Config;

use App\EncDec as EncDec;

$encdec = new EncDec();

//connect to database
$sql = new SqlCreateTable((new SqlConnection())->connect());
// create new tables if not exist
$sql->createTables();

// exit if database is not connected
if ($sql === null) {
	echo 'YOU SHOULD EXTABLISH THE DATABASE';
	exit();
}

$base_url  = Config::BASE_URL;

// if (isset($_GET['p']) && $_GET['p'] != '')
// 	echo $_GET['p'];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Private-X</title>
	<?php 
		include_once('./includes/header_common_links.php');
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url . '/assets/css/home.css'; ?>">
</head>
<body>
	<?php 
		include_once('./includes/home/header.php');
	?>
	<div class="container help_container">
		<a href="<?php echo $base_url . '/pages/help.php'; ?>"><span class="fa fa-info-circle">&nbsp;</span>How it work ?</a>
	</div>
	<div class="container create_section">
		<div class="row main_items">
			<div class="col-lg-6 col-md-12">
				<div class="main_item">
					<div class="main_item_header">
						<p class="main_item_header_title private_note">
							<span class="fa fa-edit">&nbsp;</span>Create new <span>private note</span>
						</p>
						<p class="main_item_header_desc">
							Write the note below, encrypt it and get a link
						</p>
					</div>
					<div class="main_item_body form-group">
						<textarea rows="10" class="form-control" name="note" id="note" placeholder="Write your note here..."></textarea>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-12">
				<div class="main_item upload_section">
					<div class="main_item_header">
						<p class="main_item_header_title private_file">
							<span class="fa fa-upload">&nbsp;</span>Upload <span>private file</span>
						</p>
						<p class="main_item_header_desc">
							Upload file below, encrypt it and get a link
						</p>
					</div>
					<div class="main_item_body form-group">
						<input type="hidden" name="upload_file_name" id="upload_file_name" value="" />
						<!-- dropzone area -->
						<div class="dropzone" id="my-private-dropzone">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="optional_items" style="display: none;">
			<div class="row">
				<div class="col-12">
					<p>Self-destructs</p>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="form-group">
						<select name="destruct_actions" id="destruct_actions" class="form-control">
							<option value="1" selected>after reading it</option>
							<option value="2">1 hour from now</option>
							<option value="3">3 hours from now</option>
							<option value="4">24 hours from now</option>
							<option value="5">7 days from now</option>
							<option value="6">30 days from now</option>
						</select>
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="form-group">
						<div class="form-check-inline">
						  <label class="form-check-label">
						    <input type="checkbox" class="form-check-input" name="destruct_confirm" id="destruct_confirm" value="">Do not ask for confirmation before showing and destroying the note.
						  </label>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<p>Manual password</p>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="form-group">
					  <label for="password">Enter a custom password to encrypt the note</label>
					  <input type="password" class="form-control" name="password" id="password">
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="form-group">
					  <label for="password_confirm">Confirm password</label>
					  <input type="password" class="form-control" name="password_confirm" id="password_confirm">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<p>Destruction notification</p>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="form-group">
					  <label for="notification">E-mail to notify when note is destroyed</label>
					  <input type="text" class="form-control" name="notification" id="notification">
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="form-group">
					  <label for="ref_name">Reference name for the note (optional)</label>
					  <input type="text" class="form-control" name="ref_name" id="ref_name">
					</div>
				</div>
			</div>
			<div class="row" style="display: none">
				<div class="col-lg-6 col-md-6">
					<p>Access by IP</p>
					<div class="form-group">
					  <label for="access_by_ip">Enter IP addresses separated via comma</label>
					  <input type="text" class="form-control" name="access_by_ip" id="access_by_ip">
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<p>SMS Secure</p>
					<div class="form-group">
					  <label for="sms_secure">Enter phone number to send password via SMS</label>
					  <input type="text" class="form-control" name="sms_secure" id="sms_secure">
					</div>
				</div>
			</div>
		</div>
		<div class="row button_items">
			<div class="col-12 text-center">
				<div>
					<p><span class="fa fa-cogs">&nbsp;&nbsp;</span><a class="show_hide_options" href="#">Show option</a></p>
					<button type="button" class="btn btn-lg btn-success create_btn"><span class="fa fa-lock">&nbsp;&nbsp;</span>CREATE NOTE</button>
				</div>
			</div>
		</div>
	</div>
	<div class="container result_section" style="display: none;">
		<div class="card">
			<div class="card-header"><p>Note is ready</p></div>
			<div class="card-body">
				<input type="hidden" name="hidden_id" id="hidden_id" value="3">
				<p class="show_rlt_link_info">Copy the link, paste it into an email or instant message and send it to whom you want to read the note.</p>
				<div class="form-group show_rlt_link_wrapper">
				  <input type="text" class="form-control" name="show_rlt_link" id="show_rlt_link" value="https://privateX.com/..">
				</div>
				<p class="rlt_note_desc"><span class="fa fa-lock">&nbsp;&nbsp;&nbsp;</span>The note will self-destruct after reading it.</p>
			</div>
			<div class="card-footer show_rlt_link_actions">
				<a href="mailto:?body=https://privateX.com/..">
					<button class="rlt_btn1"><span class="fa fa-inbox">&nbsp;</span>EMAIL LINK</button>
				</a>
				<a href="#">
					<button class="rlt_btn2"><span class="fa fa-copy">&nbsp;</span>COPY</button>
				</a>
				<a href="#">
					<button class="rlt_btn3"><span class="fa fa-close">&nbsp;</span>DESTROY NOTE NOW</button>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-12 text-center">
				<a href="<?php echo $base_url; ?>">
					<button type="button" class="btn btn-lg btn-success btn_create_new">
						CREATE NEW
					</button>
				</a>
			</div>
		</div>
	</div>
	<?php 
		include_once('./includes/home/footer.php');
	?>
</body>
<?php 
	include_once('./includes/footer_common_scripts.php');
?>
<script type="text/javascript">
	var BASE_URL = "<?php echo $base_url; ?>";
	var UPLOAD_URL = "<?php echo $base_url . '/util/upload_file.php' ?>";
	var CREATE_NOTE_URL = "<?php echo $base_url . '/util/save_note.php' ?>";
	var DELETE_NOTE_URL = "<?php echo $base_url . '/util/delete_note.php' ?>";
	var UPDATE_EXPIRED_STATE_URL = "<?php echo $base_url . '/util/update_expired_at_home.php' ?>";
</script>
<script type="text/javascript" src="<?php echo $base_url . '/assets/js/home.js' ?>"></script>
</html>
