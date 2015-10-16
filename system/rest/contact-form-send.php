<?php
header('Content-Type: application/json');

require('rest-core.php' );

global $site;

if (isset($_REQUEST['email'])) {

	// $_REQUEST = sanitize($_REQUEST,true);
	$_REQUEST['message'] = nl2br($_REQUEST['message']);

	$to = $site->owner_name . " <" . $site->owner_email . ">";
	$subject = "[ " . $site->system_name . " ] " . $_REQUEST['name'] . " has sent you an email.";

	$content = "<html>" . "\r\n";
	$content .= "<head>" . "\r\n";
	$content .= "<meta http-equiv='Content-Type' content='text/html; charset='utf-8' />" . "\r\n";
	$content .= "<style>" . "\r\n";
	$content .= "body { font-family: 'Trebuchet MS', Helvetica, sans-serif; font-size: 0.9em; background: url('" . $site->url . "system/images/email_bg.gif') no-repeat top right; }" . "\r\n";
	$content .= "hr { border:none; width:100%; height:1px; display:block; size: 1px; }" . "\r\n";
	$content .= "</style>" . "\r\n";
	$content .= "</head>" . "\r\n";
	$content .= "<body style='background-color: #ffffff'>" . "\r\n";
	$content .= "<a href='" . $site->url . "' target='_blank'><img src='" . $site->url . "system/images/email_header.gif' alt='" . $site->system_name . "' title='" . $site->system_name . "'></a><br>" . "\r\n";
	$content .= "<h3>" . $_REQUEST['subject'] . "</h3>" . "\r\n";

	$content .= $_REQUEST['message'] . "<br><br>";
	
	if ($_REQUEST['website'] != "")
		$content .= "Website: " . $_REQUEST['website'] . "<br><br>" . "\r\n";

	$content .= "<hr><br>" . "\r\n";
	$content .= "<small><font color='#9c9c9c'>This email came from the website, " . $site->name . " (" . $site->url . ") - Powered by <a href='" . $site->system_url . "' target='_blank'>" . $site->system_name . "</a><br><br>" . "\r\n";
	$content .= "This email and any attached files transmitted with it are confidential and intended solely for the use of individual or entity to whom it is addressed. If you received this email in error, kindly notify the sender immediately, delete the message and do not copy or use this for any other purpose nor disclose this content to any other person, Please note that any views or opinions presented in this email are solely those of the author and do not necessarily represent those of the Company. The recipient should check this email and any attachments for the presence of virus. The Company accepts no liability for any damage caused by any virus transmitted by this email.</font></small>" . "\r\n";
	$content .= "</body>" . "\r\n";
	$content .= "</html>" . "\r\n";

	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: ' . $_REQUEST['name'] . ' <' . $_REQUEST['email'] . '>' . "\r\n";
	$headers .= 'Bcc: mnkbox-work@yahoo.com.sg' . "\r\n";
	$headers .= 'Reply-To: ' . $_REQUEST['name'] . ' <' . $_REQUEST['email'] . '>' . "\r\n";
	$headers .= 'Subject: ' . $subject . "\r\n";
	$headers .= 'X-Mailer: PHP/'.phpversion() . "\r\n";

	mail($to, $subject, $content, $headers);

	$result = $_REQUEST;

} else {
	$result = false;
}
echo json_encode($result);
?>
