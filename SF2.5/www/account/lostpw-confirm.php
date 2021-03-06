<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: lostpw-confirm.php,v 1.3 2003/11/20 10:32:42 helix Exp $

require ('pre.php');    

$confirm_hash = md5($session_hash . strval(time()) . strval(rand()));

$res_user = db_query("SELECT * FROM users WHERE user_name='$form_loginname'");
if (db_numrows($res_user) < 1) exit_error("Invalid user","That user does not exist.");
$row_user = db_fetch_array($res_user);

db_query("UPDATE users SET confirm_hash='$confirm_hash' WHERE user_id=$row_user[user_id]");

$message = "Someone (presumably you) on the $sys_default_name site requested a\n"
	. "password change through email verification. If this was not you,\n"
	. "ignore this message and nothing will happen.\n\n"
	. "If you requested this verification, visit the following URL\n"
	. "to change your password:\n\n"
	. "https://$GLOBALS[HTTP_HOST]/account/lostlogin.php?confirm_hash=$confirm_hash\n\n"
	. " -- the $sys_default_name staff\n";

// echo "<p>mail (".$row_user['email'].",$sys_default_name.' Verification',$message,'From: noreply@".$GLOBALS[sys_default_domain]."')\n";

mail ($row_user['email'],$sys_default_name." Verification",$message,"From: noreply@$GLOBALS[sys_default_domain]");

$HTML->header(array('title'=>"Lost Password Confirmation"));

?>

<P><B>Confirmation mailed</B>

<P>An email has been sent to the address you have on file. Follow
the instructions in the email to change your account password.

<P><A href="/">[ Home ]</A>

<?php
$HTML->footer(array());

?>
