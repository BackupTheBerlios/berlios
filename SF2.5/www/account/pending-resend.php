<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: pending-resend.php,v 1.2 2003/11/13 11:29:21 helix Exp $

require "pre.php";    

$res_user = db_query("SELECT * FROM users WHERE user_name='$form_user'");
$row_user = db_fetch_array($res_user);

// send mail
$message = "Thank you for registering on the $GLOBALS[sys_default_domain] web site. In order\n"
	. "to complete your registration, visit the following url: \n\n"
	. "https://$GLOBALS[HTTP_HOST]/account/verify.php?confirm_hash=$row_user[confirm_hash]\n\n"
	. "Enjoy the site.\n\n"
	. " -- the $sys_default_name staff\n";


// only mail if pending
if ($row_user[status] == 'P') {
	mail($row_user[email],$sys_default_name." Account Registration",$message,"From: admin@$GLOBALS[sys_default_domain]");
	$HTML->header(array(title=>"Account Pending Verification"));
?>

<P><B>Pending Account</B>

<P>Your email confirmation has been resent. Visit the link
in this email to complete the registration process.

<P><A href="/">[Return to <?php echo $sys_default_name ?>]</A>
 
<?php
} else {
	exit_error("Error","This account is not pending verification.");
}

$HTML->footer(array());

?>
