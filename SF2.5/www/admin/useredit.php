<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: useredit.php,v 1.5 2006/01/13 12:41:43 helix Exp $

/*

	Editing user-specific properties
	
	Group-related changes (such as membershup or group permissions)
	should be edited from that group's admin page

*/

require "pre.php";    
require "account.php";
require($DOCUMENT_ROOT.'/admin/admin_utils.php');

session_require(array('group'=>'1','admin_flags'=>'A'));

if ($action=="update_user") {
	$user=user_get_object($user_id);
	$user->setEmail($email);
	$user->setShell($shell);
	$user->setUnixStatus($unix_status);
	
	if ($user->isError()) {
		$feedback=$user->getErrorMessage();
	}
}


site_admin_header(array('title'=>'User Info'));


// get users info
$res_user = db_query("SELECT * FROM users WHERE user_id=$user_id");
$row_user = db_fetch_array($res_user);

?>
<h2>User: <?php print user_getname($user_id) ?></h2>
<p>User ID: <?php print $user_id ?>
<br>Status: <?php print $row_user['status'] ?>
<br>Registered: <?php print date($sys_datefmt, $row_user['add_date']) ?>

<h3>Unix Account Info</h3>
<form method="post" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="action" value="update_user">
<input type="hidden" name="user_id" value="<?php print $user_id; ?>">

<p>
Shell:
<select name="shell">
<?php account_shellselects($row_user[shell]); ?>
</select>

<p>
Unix Account Status:
<select name="unix_status">
<option <?php echo ($row_user['unix_status'] == 'N') ? 'selected ' : ''; ?>value="N">No Unix Account
<option <?php echo ($row_user['unix_status'] == 'A') ? 'selected ' : ''; ?>value="A">Active
<option <?php echo ($row_user['unix_status'] == 'S') ? 'selected ' : ''; ?>value="S">Suspended
<option <?php echo ($row_user['unix_status'] == 'D') ? 'selected ' : ''; ?>value="D">Deleted
</select>

<p>
Email:
<input type="TEXT" name="email" VALUE="<?php echo $row_user[email]; ?>" size="25" maxlength="55">

<p><a href="user_changepw.php?user_id=<?php print $user_id; ?>">[Change User Password]</a><br>
<a href="/account/pending-resend.php?form_user=<?php print user_getname($user_id); ?>">[Resend Pending Mail]</a>
</p>

<p>
<b>
This pages allows to change only direct properties of user object. To edit
properties spanning across user/group pair, visit admin page of that
group.
</b>
</p>
<input type="submit" value="Update">
</form>

<hr>

<p>
<h3>Current Groups:</h3>

<?php
/*
	Iterate and show groups this user is in
*/
$res_cat = db_query("SELECT groups.unix_group_name, "
	. "groups.status AS status, "
	. "groups.group_name AS group_name, "
	. "groups.group_id AS group_id, "
	. "user_group.admin_flags AS admin_flags FROM "
	. "groups,user_group WHERE user_group.user_id=$user_id AND "
	. "groups.group_id=user_group.group_id");

	print "<table>\n";
	while ($row_cat = db_fetch_array($res_cat)) {
		print "<tr><td><b>$row_cat[group_name]</b> ("
			."<a href=\"/projects/".$row_cat[unix_group_name]."\">"
			.$row_cat[unix_group_name]."</a>)</td>"
			."<td>(".$row_cat[status].")</td>"
			."<td><a href=\"/project/admin/?group_id=$row_cat[group_id]\">[Remove User from Group]</a></td>"
			."<td><a href=\"/project/admin/userperms.php?group_id=".$row_cat['group_id']."\">[Edit Permissions]</a></td></tr>\n";
	}
	print "</table>\n";

html_feedback_bottom($feedback);

site_admin_footer(array());

?>
