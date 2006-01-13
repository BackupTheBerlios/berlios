<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.6 2006/01/13 12:20:49 helix Exp $

require "pre.php";
require($DOCUMENT_ROOT.'/admin/admin_utils.php');

session_require(array('group'=>'1','admin_flags'=>'A'));

site_admin_header(array('title'=>"Site Admin"));

$abc_array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');

?>
 
<h2>Administrative Functions</h2>
<p><i><b>Warning!</b> These functions currently have minimal error checking,
if any. They are fine to play with but may not act as expected if you leave
fields blank, etc... Also, navigating the admin functions with the 
<b>back</b> button is highly unadvised.</i>

<h3>User Maintenance</h3>
<ul>
<li><a href="userlist.php">Display Full User List/Edit Users</a>&nbsp;&nbsp;
<li>Display Users Beginning with : 
<?php
	for ($i=0; $i < count($abc_array); $i++) {
		echo "<a href=\"userlist.php?user_name_search=$abc_array[$i]\">$abc_array[$i]</a>|";
	}
?>
<br>
Search <i>(email,username,realname,userid)</i>:
<br>
<form name="usersrch" action="search.php" method="POST">
  <input type="text" name="search">
  <input type="hidden" name="usersearch" value="1">
  <input type="submit" value="get">
</form>
<li>Users in <a href="userlist.php?status=P"><B>P</B> (pending) Status</A>
<li>Users in <a href="userlist.php?status=S"><B>S</B> (suspended) Status</A>
<li>Users in <a href="userlist.php?status=D"><B>D</B> (deleted) Status</A>
</ul>

<h3>Group Maintenance</h3>

<ul>
<li><a href="grouplist.php">Display Full Group List/Edit Groups</a>

<li>Display Groups Beginning with : 
<?php
	for ($i=0; $i < count($abc_array); $i++) {
		echo "<a href=\"grouplist.php?group_name_search=$abc_array[$i]\">$abc_array[$i]</a>|";
	}
?>
<br>
Search <i>(groupid,groupunixname,groupname)</i>:
<br>
<form name="gpsrch" action="search.php" method="POST">
  <input type="text" name="search">
  <input type="hidden" name="groupsearch" value="1">
  <input type="submit" value="get">
</form>

<p>


<LI>Groups in <a href="grouplist.php?status=I"><B>I</B> (incomplete) Status</A>
<LI>Groups in <a href="approve-pending.php"><B>P</B> (pending) Status</A> <i>(New Project Approval)</i>
<LI>Groups in <a href="grouplist.php?status=H"><B>H</B> (holding) Status</A>
<LI>Groups in <a href="grouplist.php?status=D"><B>D</B> (deleted) Status</A>
</ul>

<h3>Donation Maintenance</h3>
<ul>
<li>Group Donations in <a href="group_donations.php"><b>Every</b> Status</a>
<li>Group Donations in <a href="group_donations.php?status=P"><b>P</b> (pending) Status</a>
<li>Group Donations in <a href="group_donations.php?status=A"><b>A</b> (approved) Status</a>
<li>Group Donations in <a href="group_donations.php?status=D"><b>D</b> (deleted) Status</a>
</ul>

<ul>
<li>User Donations in <a href="user_donations.php"><b>Every</b> Status</a>
<li>User Donations in <a href="user_donations.php?status=P"><b>P</b> (pending) Status</a>
<li>User Donations in <a href="user_donations.php?status=A"><b>A</b> (approved) Status</a>
<li>User Donations in <a href="user_donations.php?status=D"><b>D</b> (deleted) Status</a>
</ul>

<h3>Trove Maintenance</h3>
<ul>
<li><a href="trove/trove_cat_list.php">Display Trove Map</a>
<li><a href="trove/trove_cat_add.php">Add to the Trove Map</a>
</ul>

<h3>Statistics</h3>
<ul>
<li><a href="lastlogins.php">View Most Recent Logins</A>
</ul>

<h3>Site Utilities</h3>
<UL>
<LI><A href="massmail.php">Mail Engine for <?php print $GLOBALS['sys_default_name'] ?> Subscribers (MESS)</A>
<LI><A HREF="add_language.php">Add Supported Language</A>
<LI><A HREF="responses_admin.php">Add/Change Canned Responses</A>
</UL>

<h3>Site Stats</h3>
<?php
	$res=db_query("SELECT count(*) AS count FROM users WHERE status='A'");
	$row = db_fetch_array($res);
	print "<P>Registered active site users: <B>$row[count]</B>";

	$res=db_query("SELECT count(*) AS count FROM groups");
	$row = db_fetch_array($res);
	print "<BR>Registered projects: <B>$row[count]</B>";

	$res=db_query("SELECT count(*) AS count FROM groups WHERE status='A'");
	$row = db_fetch_array($res);
	print "<BR>Registered/hosted projects: <B>$row[count]</B>";

	$res=db_query("SELECT count(*) AS count FROM groups WHERE status='P'");
	$row = db_fetch_array($res);
	print "<BR>Pending projects: <B>$row[count]</B>";
site_admin_footer(array());

?>
