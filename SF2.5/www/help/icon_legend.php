<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: icon_legend.php,v 1.1 2004/04/02 10:50:38 helix Exp $

require "pre.php";    

$HTML->header(array('title'=>'Icon Legend'));
?>

<h2 align="center">Icon Legend</h2>

<p>BerliOS Developer uses special icons and markings next to the names of some users to indicate their contributions to the BerliOS Developer site, projects and users. Furthermore other special icons and markings next to the names of projects and users indicate that they request for contributions. This page provides a legend for those markings:

<table border="0" cellpadding="3" cellspacing="5">
<tbody>
<tr><td colspan="2"><h3>Icon information for <?php print $uname; ?>:</h3></td>
</tr>
<tr><td>&nbsp;</td>
<td><b>This page has been generated for:</b> <a href="/users/rngadam/">rngadam</a><a href="/help/icon_legend.php?context=group_donor&uname=rngadam&this_group=84122&return_to=/"><img src="/images/iconRedStar_16x16.png" alt="Project Donor" border="0" height="16" width="16"></a></td>
</tr>
<tr><td valign="top"><a href="/projects/azureus/"><img src="/images/iconRedStar_16x16.png" alt="Project Donor" border="0" height="16" width="16"></a></td>
<td>The icon shown at left, a small red star, designates that this user, rngadam, has made a donation current project, <a href="/projects/azureus">Azureus - BitTorrent Client</a>.</td>
</tr>
<tr><td colspan="2"><h3>Other icons used on site:</h3></td>
</tr>
<tr><td valign="top"><a href="/projects/azureus/"><img src="/images/iconRedStar_16x16.png" alt="Project Donor" border="0" height="16" width="16"></a></td>
<td>The icon shown at left, a small red star, designates that this user has made a donation to current project.</td>
</tr>
<tr><td valign="top"><a href="/projects/azureus/"><img src="/images/iconYellowStar_16x16.png" alt="User Donor" border="0" height="16" width="16"></a></td>
<td>The icon shown at left, a small yellow star, designates that this user has made a donation to current user.</td>
</tr>
<tr><td valign="top"><a href="/projects/azureus/"><img src="/images/iconPurpleStar_16x16.png" alt="Project Donate" border="0" height="16" width="16">&euro;</a></td>
<td>The icon shown at left, a small purple star with a &euro; sign, designates that this project requests for donations.</td>
</tr>
<tr><td valign="top"><a href="/projects/azureus/"><img src="/images/iconTealStar_16x16.png" alt="User Donate" border="0" height="16" width="16">&euro;</a></td>
<td>The icon shown at left, a small teal star with a &euro; sign, designates that this user requests for donations.</td>
</tr>
</tbody>
</table>
<?php

$HTML->footer(array());
?>
