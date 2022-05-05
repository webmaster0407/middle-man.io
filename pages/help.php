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
	<title>Private-X | HELP</title>
	<?php 
		include_once('../includes/header_common_links.php');
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url . '/assets/css/help.css'; ?>">
</head>
<body>
	<?php 
		include_once('../includes/help/header.php');
	?>
	<div class="container main_section" >
		<div class="card">
			<div class="card-header">
				<h3>About Middle Man</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<p>In our times, when different types of Internet fraud and ways to access user data are well-developed, many users are afraid to send confidential data because of the insecurity and risk of access by third parties.

						How can you avoid this and be able to share the information with your colleagues, friends and family and still keep your privacy?

						We present you <span>Middle Man</span> – a unique free service which allows to exchange confidential messages via the web. Our service is easy to use and does not require compulsory registration.

	<!-- 					For accessing advanced features you may switch to Privatty Premium tariff, which makes it possible to use the service functionality in full scope. -->
						</p>
						<h3>
							How it work?
						</h3>
					</div>
					<div class="col-12">
						<img src="<?php echo $base_url . '/assets/images/hiw-en.svg';  ?>">
					</div>
					<div class="col-12">
						<p>
							Create a note (a confidential message) and get the link. Copy the link and send it to a recipient by e-mail or instant messaging.

							A one-time key is needed to encrypt the note. It is located at the message sender’s device and is transmitted to the recipient along with a link to the note. The note is encrypted with a key. The message is stored as a code in the database. You need a private key to open it. When your friend or colleague opens the message, the hash of the initial version of the note is checked against the hash of the current one. In case of a match, the message is transmitted, and the data about it are self-destructed in the system.

							More detailed look at how confidentiality is secured is given in the <a href="<?php echo $base_url; ?>">Privacy Policy</a> section.
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<a href="<?php echo $base_url; ?>">
							<button type="button" class="btn btn-lg btn-primary go_home_htn">
								<span class="fa fa-backward">&nbsp;</span>GO HOME
							</button>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>



	<?php 
		include_once('../includes/help/footer.php');
	?>
</body>
<?php 
	include_once('../includes/footer_common_scripts.php');
?>
<script type="text/javascript">
	var BASE_URL = "<?php echo $base_url; ?>";
</script>
<script type="text/javascript" src="<?php echo $base_url . '/assets/js/help.js' ?>"></script>
</html>
