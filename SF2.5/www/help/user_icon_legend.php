<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: user_icon_legend.php,v 1.1 2004/04/02 10:50:38 helix Exp $

require "pre.php";    
require "donate.php";    

if (! isset($user_id)) {
  $user_id = 102;
}
$user=&user_get_object($user_id);

$HTML->header(array('title'=>'User Icon Legend'));
?>

<h2 align="center">User Icon Legend</h2>

<p>BerliOS Developer uses special icons and markings next to the names of some users to indicate their contributions to the BerliOS Developer site, projects and users. Furthermore other special icons and markings next to the names of users indicate that they request for contributions. This page provides a legend for those markings:

<table border="0" cellpadding="3" cellspacing="5">
<tbody>
<tr><td colspan="2"><h3>Icon information for Users:</h3></td>
</tr>
<tr><td>&nbsp;</td>
<td><b>This page has been generated for:</b> <a href="/users/<?php print $user->getUnixName() ?>/"><?php print $user->getUnixName() ?></a><?php print is_project_donor($user_id).is_user_donor($user_id).req_user_donate($user_id) ?></td>
</tr>
<?php
if (is_project_donor($user_id) != "") {
?>
<tr><td valign="top"><img src="/images/iconRedStar_16x16.png" alt="Project Donor" border="0" height="16" width="16"></td>
<td>The icon shown at left, a small red star, designates that this user has made a donation to projects.</td>
</tr>
<?php
}
if (is_user_donor($user_id) != "") {
?>
<tr><td valign="top"><img src="/images/iconYellowStar_16x16.png" alt="User Donor" border="0" height="16" width="16"></td>
<td>The icon shown at left, a small yellow star, designates that this user has made a donation to users.</td>
</tr>
<?php
}
if (req_user_donate($user_id) != "") {
?>
<tr><td valign="top"><img src="/images/iconTealStar_16x16.png" alt="User Donate" border="0" height="16" width="16">&euro;</a></td>
<td>The icon shown at left, a small teal star with a &euro; sign, designates that this user requests for donations.</td>
</tr>
<?php
}
?>
</tbody>
</table>

<?php

$HTML->footer(array());

?>
