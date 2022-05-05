<?php
require '../vendor/autoload.php';

use App\Config as Config;

$base_url  = Config::BASE_URL;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Private-X | 404</title>
	<?php 
		include_once('../includes/header_common_links.php');
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url . '/assets/css/404.css'; ?>">
</head>
<body>
	<?php 
		include_once('../includes/404/header.php');
	?>
	<div class="container help_container">
		<a href="<?php echo $base_url . '/pages/help.php'; ?>"><span class="fa fa-info-circle">&nbsp;</span>How it work ?</a>
	</div>

	<div class="container main_section" >
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12 text-center">
						<h1>404</h1>
						<h3>Oops, you've encountered an error</h3>
						<p>It appears the page you were looking for doesn't exist. Sorry about that.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12 text-center">
						<a href="<?php echo $base_url; ?>">
							<button type="button" class="btn btn-lg btn-success go_home_htn">
								<span class="fa fa-backward">&nbsp;</span>GO HOME
							</button>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>



	<?php 
		include_once('../includes/404/footer.php');
	?>
</body>
<?php 
	include_once('../includes/footer_common_scripts.php');
?>
<script type="text/javascript">
	var BASE_URL = "<?php echo $base_url; ?>";
	var DELETE_NOTE_URL = "<?php echo $base_url . '/util/delete_note.php' ?>";
	var UPDATE_READSTATE_NOTE_URL = "<?php echo $base_url . '/util/update_read_state.php' ?>";
</script>
<script type="text/javascript" src="<?php echo $base_url . '/assets/js/404.js' ?>"></script>
</html>
