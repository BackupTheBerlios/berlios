<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: userdonation.php,v 1.1 2004/04/02 10:42:24 helix Exp $

require "pre.php";    // Initial db and session library, opens session

$HTML->header(array(title=>"Process User Donation"));

if (user_isloggedin()) {
  $from_user_name = user_getname();
  $from_user_id = user_getid();
} else {
  $from_user_name = 'anonymous';
  $from_user_id = 7428;
}

$time = time();
?>

<p>
<center>
<table cellspacing="1" cellpadding="5" border="0" bgcolor="#FFFFFF">
<tr bgcolor="#EAECEF"><td><b>Donors Login name:</b></td><td><?php echo $from_user_name; ?></td></tr>
<tr bgcolor="#EAECEF"><td><b>Donors User ID:</b></td><td><?php echo $from_user_id; ?></td></tr>
<tr bgcolor="#EAECEF"><td><b>Donation User ID:</b></td><td><?php echo $user_id; ?></td></tr>
<tr bgcolor="#EAECEF"><td><b>Amount:</b></td><td>&euro;<?php echo $amt; ?>,00</td></tr>
<tr bgcolor="#EAECEF"><td><b>Comment:</b></td><td><?php echo $comment; ?></td></tr>
<tr bgcolor="#EAECEF"><td><b>Date:</b></td><td><?php echo date($sys_datefmt,$time); ?></td></tr>
</table>
</center>

<?php
$query = "INSERT INTO user_donors "
    . "(user_id,to_user_id,comment,amount,status,add_date) "
    . "VALUES ('".$from_user_id."', "
    . "'".$user_id."',"
    . "'".$comment."',"
    . "'".$amt."',"
    . "'P',"
    . "'".$time."')";

$res = db_query($query);

if (!$res) {
  $feedback = 'User Donor Insert Failed '.db_error();
  print "<p><font color=#FF0000>$feedback</font>";
} else {
?>

<p><center>
<img src="/images/x-click-but7.gif" border="0" alt="Make payments with PayPal - it's fast, free and secure!">
<h2><font color="#FF0000">Sorry, not yet activated!</a><h2>
</center>

<?php
}
$HTML->footer(array());
?>
