<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: lostpw.php,v 1.2 2003/11/13 11:29:21 helix Exp $

require "pre.php";    
$HTML->header(array('title'=>"Lost Account Password"));
?>

<P><B>Lost your password?</B>

<P>Hey... losing your password is serious business. It compromises the
security of your account, your projects, and this site.

<P>Clicking "Send Lost PW Hash" below will email a URL to the email
address we have on file for you. In this URL is a 128-bit confirmation
hash for your account. Visiting the URL will allow you to change
your password online and login.

<FORM action="lostpw-confirm.php" method="post">
<P><INPUT type="hidden" name="form_user" value="<?php print $form_user; ?>">
Login Name:
<INPUT type="text" name="form_loginname">
<INPUT type="submit" name="Send Lost PW Hash" value="Send Lost PW Hash">
</FORM>

<P><A href="/">[Return to <?php echo $sys_default_name ?>]</A>

<?php
$HTML->footer(array());

?>
