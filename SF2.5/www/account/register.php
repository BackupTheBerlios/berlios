<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: register.php,v 1.3 2004/04/02 12:14:03 helix Exp $

require ('pre.php');    
require ('account.php');
require ('timezones.php');

if ($submit) {
	/*

		Adding call to library rather than
		logic that used to be coded in this page

	*/
	$register=account_register_new(strtolower($unix_name),$realname,$password1,$password2,$email,$language,$timezone,$mail_site,$mail_va,$donor_display,$language_id,$timezone);
	if ($register) {
		echo $HTML->header(array('title'=>'Register Confirmation'));
		?>
		<p>
		<b><?php echo $sys_default_name ?>: New Account Registration Confirmation</b>
		<p>
		Congratulations. You have registered on <?php echo $sys_default_name ?>.
		<p> 
		You are now being sent a confirmation email to verify your email 
		address. Visiting the link sent to you in this email will activate
		your account.
		<?php

		echo $HTML->footer(array());
		exit;
	}
}


$HTML->header(array('title'=>$sys_default_name.': Register'));

if (browser_is_windows() && browser_is_ie() && browser_get_version() < '5.1') {
	echo '<H2><FONT COLOR="RED">Internet Explorer users need to 
	upgrade to IE 5.01 or higher, preferably with 128-bit SSL or use Netscape 4.7 or higher</FONT></H2>';	
}
if (browser_is_ie() && browser_is_mac()) {
	echo '<H2><FONT COLOR="RED">Internet Explorer on the Macintosh
	is not supported currently. Use Netscape 4.7 or higher</FONT></H2>';
}


?>
<p><b><?php echo $sys_default_name ?> New Account Registration</b>
<?php 
if ($feedback) {
	print "<p><FONT color=#FF0000>$feedback $register_error</FONT>";
} 
?>
<form action="https://<?php echo $HTTP_HOST.$PHP_SELF; ?>" method="post">
<p>
Login Name:
<br><i>Please take into account that a unix name of a project
you like to register in the future cannot get
the same name as this Login Name. It is recommended to choose a Login Name
which have something to do with your personal name.</i>
<br><input type="text" name="unix_name" value="<?php print($unix_name); ?>">
<p>
Password (min. 6 chars):<br>
<input type="password" name="password1" value="<?php print($password1); ?>">
<p>
Password (repeat):<br>
<input type="password" name="password2" value="<?php print($password2); ?>">
<P>
Full/Real Name:<BR>
<INPUT size=30 type="text" name="realname" value="<?php print($realname); ?>">
<P>
Language Choice:<BR>
<?php echo html_get_language_popup ($Language,'language_id',1); ?>
<P>
Timezone:<BR>
<?php echo html_get_timezone_popup (); ?>
<P>
Email Address:
<BR><I>This email address will be verified before account activation.
It will not be displayed on the site. You will receive a mail forward
account at loginname@<?php echo $GLOBALS['sys_users_host']; ?> that will forward to
this address.</I>
<BR><INPUT size=30 type="text" name="email" value="<?php print($email); ?>">
<P>
<INPUT type="checkbox" name="mail_site" value="1" checked>
Receive Email about Site Updates <I>(Very low traffic and includes
security notices. Highly Recommended.)</I>
<P>
<INPUT type="checkbox" name="mail_va" value="1">
Receive additional community mailings. <I>(Low traffic.)</I>
<P>
<INPUT type="checkbox" name="donor_display" value="1" checked>
Display your donations on the <a href="/supporters.php">supporters page</a>.
<p>
<input type="submit" name="submit" value="Register">
</form>

<?php

$HTML->footer(array());

?>
