<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: project_icon_legend.php,v 1.1 2004/04/02 10:50:38 helix Exp $

require "pre.php";    
require "donate.php";    

if (! isset($group_id)) {
  $group_id = 1;
}
$group=&group_get_object($group_id);

$HTML->header(array('title'=>'Project Icon Legend'));
?>

<h2 align="center">Project Icon Legend</h2>

<p>BerliOS Developer uses special icons and markings next to the names of some projects to indicate that they request for contributions. This page provides a legend for those markings:

<table border="0" cellpadding="3" cellspacing="5">
<tbody>
<tr><td colspan="2"><h3>Icon information for Projects:</h3></td>
</tr>
<tr><td>&nbsp;</td>
<td><b>This page has been generated for:</b> <a href="/projects/<?php print $group->getUnixName() ?>/"><?php print $group->getPublicName()." (".$group->getUnixName().")" ?></a><?php print req_project_donate($group_id) ?></td>
</tr>
<tr><td valign="top"><img src="/images/iconPurpleStar_16x16.png" alt="Project Donate" border="0" height="16" width="16">&euro;</a></td>
<td>The icon shown at left, a small purple star with a &euro; sign, designates that this project requests for donations.</td>
</tr>
</tbody>
</table>
<?php

$HTML->footer(array());
?>
