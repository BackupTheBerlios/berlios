<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: staff.php,v 1.2 2003/11/13 11:29:20 helix Exp $

require ('pre.php');    
$HTML->header(array('title'=>'SorceForge Staff'));

?>
<P>
<h2>BerliOS Staff</h2>
<P>
<table border=0 cellspacing=2 cellpadding=2 bgcolor="">

<tr valign=top>
<td><a href="/sendmessage.php?toaddress=helix@users.berlios.de">Lutz Henckel</a></td>
<td>(helix)</td>
<td>
Lutz is the Project Leader of BerliOS.
</td>
</tr>

<tr valign=top>
<td><a href="/sendmessage.php?toaddress=schily@users.berlios.de">Jörg Schilling</a></td>
<td>(schily)</td>
<td>Jörg is the developer of cdrecord and other nice Open Source Software.</td>
</tr>

<tr valign=top>
<td><a href="/sendmessage.php?toaddress=riessen@users.berlios.de">Andreas Riessen</a></td>
<td>(riessen)</td>
<td>Andreas is a developer of <a href="http://sourceagency.berlios.de/">SourceAgency</a>.</td>
</tr>

<tr valign=top>
<td><a href="/sendmessage.php?toaddress=grex@users.berlios.de">Gregorio Robles</a></td>
<td>(grex)</td>
<td>Gregorio is a student at the University of Madrid and is the main developer of <a href="http://sourceagency.berlios.de/">SourceAgency</a>.</td>
</tr>

<tr valign=top>
<td><a href="/sendmessage.php?toaddress=chrics@users.berlios.de">Christian Schmidt</a></td>
<td>(chrics)</td>
<td>Christian is a student at the Technical University of Berlin and a developer of <a href="http://docswell.berlios.de/">DocsWell</a>.</td>
</tr>

</table>

<?php
$HTML->footer(array());
?>
