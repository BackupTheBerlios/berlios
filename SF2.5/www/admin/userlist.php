<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: userlist.php,v 1.5 2005/02/16 12:16:19 helix Exp $

require "pre.php";    
require($DOCUMENT_ROOT.'/admin/admin_utils.php');

session_require(array('group'=>'1','admin_flags'=>'A'));

site_admin_header(array('title'=>"User List"));

function show_users_list ($result,$status,$search) {
	print "<br>Status: <b>";
	if ( $status == "") {
	  print "All";
	} else {
	  print $status;
	}
	print "</b>\n";

	print "<br>Users Beginning with: <b>";
	if ( $search == "" ) {
	  print "All";
	} else {
	  print $search ;
	}
	print "</b>\n";
	
	echo '<p>Key:
		<b>Active</b>
		<i>Deleted</i>
		Suspended
		(*)Pending
		<p>
		<table width="100%" cellspacing="0" cellpadding="1" border="1">
		<tr><th>UserName</th><th>User\'s Name</th><th>eMail</th><th>DevProfile</th><th colspan=\"4\">Set to Status</th></tr>';

	while ($usr = db_fetch_array($result)) {
		print "\n<tr><td><a href=\"useredit.php?user_id=$usr[user_id]\">";
		if ($usr[status] == 'A') print "<b>";
		if ($usr[status] == 'D') print "<i>";
		if ($usr[status] == 'P') print "*";
		print "$usr[user_name]";
		if ($usr[status] == 'A') print "</b>";
		if ($usr[status] == 'D') print "</i>";
		print "</a></td><td>$usr[realname]</td></td><td>$usr[email]</td>\n";
		print "\n<td><a href=\"/developer/?form_dev=$usr[user_id]\">[DevProfile]</a></td>";
		print "\n<td><a href=\"userlist.php?action=activate&user_id=$usr[user_id]&status=$status&user_name_search=$search\">[Activate]</a></td>";
		print "\n<td><a href=\"userlist.php?action=delete&user_id=$usr[user_id]&status=$status&user_name_search=$search\">[Delete]</a></td>";
		print "\n<td><a href=\"userlist.php?action=suspend&user_id=$usr[user_id]&status=$status&user_name_search=$search\">[Suspend]</a></td>";
		print "\n<td><a href=\"userlist.php?action=pending&user_id=$usr[user_id]&status=$status&user_name_search=$search\">[Pending]</a></td>";
		print "</tr>";
	}
	print "</table>";

}

// Administrative functions

/*
	Set this user to delete
*/
if ($action=='delete') {
	db_query("UPDATE users SET status='D' WHERE user_id='$user_id'");
	echo '<h2><font color="red">User updated to DELETE Status</font></h2>';
}

/*
	Activate their account
*/
if ($action=='activate') {
	db_query("UPDATE users SET status='A' WHERE user_id='$user_id'");
	echo '<h2><font color="red">User updated to ACTIVE status</font></h2>';
}

/*
	Suspend their account
*/
if ($action=='suspend') {
	db_query("UPDATE users SET status='S' WHERE user_id='$user_id'");
	echo '<h2><font color="red">User updated to SUSPEND Status</font></h2>';
}

/*
        Set their account to pending
*/
if ($action=='pending') {
        db_query("UPDATE users SET status='P' WHERE user_id='$user_id'");
        echo '<h2><font color="red">User updated to PENDING Status</font></h2>';
}

/*
	Add a user to this group
*/
if ($action=='add_to_group') {
	db_query("INSERT INTO user_group (user_id,group_id) VALUES ($user_id,$group_id)");
}

/*
	Show list of users
*/
print "<h2>User List</h2>\n";
print "<p>User List for Group: ";
if (!$group_id) {
	print "<b>All Groups</b>";
	print "\n";

	if ($user_id) {
	  $result = db_query("SELECT user_name,user_id,realname,email,status FROM users WHERE user_id='$user_id'");
	  $status = "";
	  $user_name_search = "";
	} else {
	  if ($user_name_search) {
		if ($status) $where_status = "AND status='$status'";
		else $where_status = "";
		$result = db_query("SELECT user_name,user_id,realname,email,status FROM users WHERE user_name ILIKE '$user_name_search%' $where_status ORDER BY user_name");
	  } else {
		if ($status) $where_status = "WHERE status='$status'";
		else $where_status = "";
		$result = db_query("SELECT user_name,user_id,realname,email,status FROM users $where_status ORDER BY user_name");
	  }
	}
	show_users_list ($result,$status,$user_name_search);
} else {
	/*
		Show list for one group
	*/
	print "<b>" . group_getname($group_id) . "</b>";
	print "\n";

	if ($status) $where_status = "AND status='$status'";
        else $where_status = "";
	$result = db_query("SELECT users.user_id AS user_id,users.user_name AS user_name,users.realname AS realname,users.email AS email,users.status AS status "
		. "FROM users,user_group "
		. "WHERE users.user_id=user_group.user_id AND "
		. "user_group.group_id=$group_id $where_status ORDER BY users.user_name");
	show_users_list ($result,$status,$user_name_search);

	/*
        	Show a form so a user can be added to this group
	*/
	?>
	<hr>
	<P>
	<form action="<?php echo $PHP_SELF; ?>" method="post">
	<input type="HIDDEN" name="action" VALUE="add_to_group">
	<input name="user_id" type="TEXT" value="">
	<p>
	Add User to Group (<?php print group_getname($group_id); ?>):
	<br>
	<input type="HIDDEN" name="group_id" VALUE="<?php print $group_id; ?>">
	<p>
	<input type="submit" name="Submit" value="Submit">
	</form>

	<?php	
}

$HTML->footer(array());

?>
