<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: grouplist.php,v 1.4 2005/02/11 12:54:58 helix Exp $

require "pre.php";    
require($DOCUMENT_ROOT.'/admin/admin_utils.php');

session_require(array('group'=>'1','admin_flags'=>'A'));

site_admin_header(array('title'=>"Group List"));

// start from root if root not passed in
if (!$form_catroot) {
	$form_catroot = 1;
}

print "<h2>Group List</h2>\n";
// print "<p><a href=\"groupedit-add.php\">[Add Group]</a>";
print "<p>Group List for Category: ";

if ($form_catroot == 1) {

	if (isset($group_name_search)) {
		print "<b>Groups that begin with $group_name_search</b>\n";
		$res = db_query("SELECT group_name,unix_group_name,group_id,is_public,status,license "
			. "FROM groups WHERE group_name ILIKE '$group_name_search%' "
			. ($form_pending?"AND WHERE status='P' ":"")
			. " ORDER BY group_name");
	} else {
		print "<b>All Categories</b>\n";
		$res = db_query("SELECT group_name,unix_group_name,group_id,is_public,status,license "
			. "FROM groups "
			. ($status?"WHERE status='$status' ":"")
			. "ORDER BY group_name");
	}
// 2003-02-03 helix
//} else {
//	print "<b>" . category_fullname($form_catroot) . "</b>\n";
//
//	$res = db_query("SELECT groups.group_name,groups.unix_group_name,groups.group_id,"
//		. "groups.is_public,"
//		. "groups.license,"
//		. "groups.status "
//		. "FROM groups,group_category "
//		. "WHERE groups.group_id=group_category.group_id AND "
//		. "group_category.category_id=$GLOBALS[form_catroot] ORDER BY groups.group_name");
}

print "<br>Status: <b>";
if ( $status == "") {
  print "All";
} else {
  print $status;
}
print "</b><p>\n";

?>

<P>
<table width="100%" cellspacing="0" cellpadding="1" border="1">
<tr>
<th>Group Name</th>
<th>Unix Name</th>
<th>Status</th>
<th>Public</th>
<th>License</th>
<!-- 2003-02-03 helix
<th>Categories</th>
-->
<th>Members</th>
</tr>

<?php
while ($grp = db_fetch_array($res)) {
	print "<tr>";
	print "<td><a href=\"groupedit.php?group_id=$grp[group_id]\">$grp[group_name]</a></td>";
	print "<td>$grp[unix_group_name]</td>";
	print "<td>$grp[status]</td>";
	print "<td>$grp[is_public]</td>";
	print "<td>$grp[license]</td>";
	
	// categories
// 2003-02-03 helix
//	$count = db_query("SELECT group_id FROM group_category WHERE "
//                . "group_id=$grp[group_id]");
//        print ("<td>" . db_numrows($count) . "</td>");

	// members
	$res_count = db_query("SELECT user_id FROM user_group WHERE group_id=$grp[group_id]");
	print "<td>" . db_numrows($res_count) . "</td>";

	print "</tr>\n";
}
?>

</table>

<?php
site_admin_footer(array());

?>
