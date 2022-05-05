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
	<title>Private-X | Privacy</title>
	<?php 
		include_once('../includes/header_common_links.php');
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url . '/assets/css/privacy.css'; ?>">
</head>
<body>
	<?php 
		include_once('../includes/privacy/header.php');
	?>
	<div class="container main_section" >
		<div class="card">
			<div class="card-header">
				<h3>Privacy Policy</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<p><i>Last modified: April 8th, 2022</i></p>
						<p>Privacy is the ethos of Privatty service. Our key goal is to preserve customer information and prevent data leakage. In this section you will be able to get acquainted with Privatty measures of protection from information leakage.</p>
						<h3>
							1. Description of the service
						</h3>
						<p>
							With the free web service Privatty, encrypted messages are created. Links to them are sent as one-time HTTPS URL addresses. The recipient follows the link, opens the message, reads it, and then all the information about the message in the system is erased.
						</p>
						<p>
							Regardless of the means of communication with the message recipient, be extremely careful. If you suspect that the link to the file has become public (i.e., sending to the General Fax of the enterprise) or got to the wrong person, it is better to delete the message. There is always a risk of data leakage, albeit minimal, when sending data to someone.
						</p>
						<h3>
							2. How the messages and its content are processed
						</h3>
						<p>
							Before sending, the link to the created file is available only to the user of the service. After sending, it is also available to the addressee. If the link is lost, it cannot be restored. Be careful and copy the most important data just in case. After you send the message, you can delete them.

							A special key is required to decrypt the message. Only the sender and the recipient have access to it. The information is stored on Privatty servers as protected code. This is a guarantee of data security.

							Upon reading the file, all data about it is automatically deleted from the system. Information recovery is not possible by refreshing the browser page, hitting the Back button or in any other way.

							Advanced settings allow you to specify the period of storing the sender's files. The number of reads of the file can be changed in any range. This is very useful when sending messages to a group of recipients. When the time expires, the information is erased. You can't restore a note after that.

							The data retention limit is 30 days. Two-level security system is a reliable lock on the way of those who want to gain unauthorized access to your information, change it or destroy the data. Data encryption is only the first stage of protection. The key to the information is stored at the user’s device and the recipient of the data. Middle Man does not store it, which ensures data safety even in case of service crack.
						</p>
						<h3>
							4. Personal (alias) data
						</h3>
						<p>
							The sender can specify personal information in the message. Thanks to the data encryption at the stage of sending and the total data destruction after reading the message, the sender confidentiality is ensured.

							Access to correspondence is granted only to the sender and the recipient with a special key. Privatty administration is not included in the list of individuals with allowed access, as they do not have authorization to do so.
						</p>
						<h3>
							5. Disclaimer
						</h3>
						<p>
							In case of contradiction of the written to law, ethics or other norms, all responsibility for the information sent lies with the sender.
						</p>
						<h3>
							6. Disclosure of data to other users
						</h3>
						<p>
							Other users or third parties have no way to access <span>Middle Man</span> user information. Protection of personal data of our clients is guaranteed by the agreement on the use of the service and is reflected in the <a href="<?php echo $base_url . '/pages/privacy.php'; ?>">Privacy Policy</a> section.
						</p>

						<h3>
							7. Use of cookie files
						</h3>
						<p>
							The use of cookies, small text data from the user's browser, is only to improve the site and service operation. These data can be used to ensure best experience in sender’s interaction with the system interface (functional cookies).

							The first ones are used to ensure uninterrupted communication with the servers of the service and to send message read notifications. Some of the functional cookies are the basis for the protection of links from third parties. After reading the file or expiration of the storage time, these data are completely removed from the system.

							Non-functional files do not contain personal information about users of the system. This information may be collected for advertisement purposes. Internet browsers allow you to impose a ban on the transfer of certain data. In the settings, you can set restrictions on data exchange with the global network.
						</p>
						<h3>
							8. Age restriction
						</h3>
						<p>
							Use of the Privatty service by minors is permitted only with the express consent of parents, guardians or other authorized representatives.
						</p>
						<h3>
							9. Changes to the Privacy Policy
						</h3>
						<p>
							The Privacy Policy section could not be static. It may be altered or extended, which will be essentially reflected at this page. In case of global changes, we will publish a more prominent notification at the service main page. The date of changes to the section is published in the page title.
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
		include_once('../includes/privacy/footer.php');
	?>
</body>
<?php 
	include_once('../includes/footer_common_scripts.php');
?>
<script type="text/javascript">
	var BASE_URL = "<?php echo $base_url; ?>";
</script>
<script type="text/javascript" src="<?php echo $base_url . '/assets/js/privacy.js' ?>"></script>
</html>
