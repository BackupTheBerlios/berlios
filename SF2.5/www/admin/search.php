<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//

require "pre.php";
require($DOCUMENT_ROOT.'/admin/admin_utils.php');

session_require(array('group'=>'1','admin_flags'=>'A'));

site_admin_header(array('title'=>"User/Group Maintenance"));

print "<h2>User/Group Maintenance</h2>\n";

if ($search == "") {

  exit_error("Refusing to display whole DB","That would display whole DB.  Please use a CLI query if you wish to do this.");

}


if ($usersearch) {
    print "<p>User Search Criteria: <b>%$search%</b><p>\n"; 

	$sql = "select distinct * from users where user_id like '%$search%' or user_name like '%$search%' or email like '%$search%' or realname like '%$search%'";
	$result = db_query($sql); 
	if (db_numrows($result) < 1) {
		print "No matches.<p><a href=\"/admin/\">Back</a>";

	}
	else {
	  echo '<p>Key:
		<b>Active</b>
		<i>Deleted</i>
		Suspended
		(*)Pending
        <p>
		<table cellspacing="0" cellpadding="1" border="1">
		<tr><th>UserName</th><th>User\'s Name</th><th>eMail</th><th>DevProfile</th><th colspan=\"4\">Set to Status</th></tr>';

		while ($row = db_fetch_array($result)) {
			print "\n<tr><td><a href=\"useredit.php?user_id=$row[user_id]\">";
			if ($row[status] == 'A') print "<b>";
			if ($row[status] == 'D') print "<i>";
			if ($row[status] == 'P') print "*";
			print $row[user_name];
			if ($row[status] == 'A') print "</b>";
			if ($row[status] == 'D') print "</i>";
			print "</a></td><td>$row[realname]</td></td><td>$row[email]</td>\n"; 
			print "<td><a href=\"/developer/?form_dev=$row[user_id]\">[DevProfile]</a></td>\n";
			print "<td><a href=\"userlist.php?action=activate&user_id=$row[user_id]&status=$status\">[Activate]</a></td>\n";
			print "<td><a href=\"userlist.php?action=delete&user_id=$row[user_id]&status=$status\">[Delete]</a></td>\n";
			print "<td><a href=\"userlist.php?action=suspend&user_id=$row[user_id]&status=$status\">[Suspend]</a></td>\n";
			print "<td><a href=\"userlist.php?action=pending&user_id=$row[user_id]&status=$status\">[Pending]</a></td></tr>\n";
		}
		print "</table>\n";

	} 
} // end if ($usersearch)


if ($groupsearch) {
    print "<p>Group Search Criteria: <b>%$search%</b><p>"; 

	$sql = "select distinct * from groups where group_id like '%$search%' or unix_group_name like '%$search%' or group_name like '%$search%'";
	$result = db_query($sql); 

	if (db_numrows($result) < 1) {

		print "No matches.<p><a href=\"/admin/\">Back</a>";

	}
	else {

		print "<table cellspacing=\"0\" cellpadding=\"1\" border=\"1\">";
		print "<tr><th>Group Name</th><th>Unix Name</th><th>Status</th><th>Public</th><th>License</th><th>Members</th></tr>\n";
		while ($row = db_fetch_array($result)) {
			print "<tr>";
			print "<td><a href=\"groupedit.php?group_id=$row[group_id]\">$row[group_name]</a></td>";
			print "<td>$row[unix_group_name]</td>";
			print "<td>$row[status]</td>";
			print "<td>$row[is_public]</td>";
			print "<td>$row[license]</td>";

			// members
			$res_count = db_query("SELECT user_id FROM user_group WHERE group_id=$row[group_id]");
			print "<td>" . db_numrows($res_count) . "</td>";
					
			print "</tr>\n";
		}
		
		print "</table>\n";

	} 


} //end if($groupsearch)

$HTML->footer(array());
?>
