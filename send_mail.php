<?php
// This function checks for email injection. Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// Load form field data into variables.
$email_address = $_REQUEST['email_address'] ;
$comments = $_REQUEST['comments'] ;

// If the user tries to access this script directly, redirect them to feedback form,
if (!isset($_REQUEST['email_address'])) {
header( "Location: feedback_form.html" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($email_address) || empty($comments)) {
header( "Location: error_message.html" );
}

// If email injection is detected, redirect to the error page.
elseif ( isInjected($email_address) ) {
header( "Location: error_message.html" );
}

// If we passed all previous tests, send the email!
else {
mail( "name@example.com", "Feedback Form Results",
  $comments, "From: $email_address" );
header( "Location: thank_you.html" );
}
?>