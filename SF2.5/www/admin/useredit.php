<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: useredit.php,v 1.4 2005/02/11 12:54:58 helix Exp $

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
<FORM method="post" action="<?php echo $PHP_SELF; ?>">
<INPUT type="hidden" name="action" value="update_user">
<INPUT type="hidden" name="user_id" value="<?php print $user_id; ?>">

<p>
Shell:
<SELECT name="shell">
<?php account_shellselects($row_user[shell]); ?>
</SELECT>

<p>
Unix Account Status:
<SELECT name="unix_status">
<OPTION <?php echo ($row_user['unix_status'] == 'N') ? 'selected ' : ''; ?>value="N">No Unix Account
<OPTION <?php echo ($row_user['unix_status'] == 'A') ? 'selected ' : ''; ?>value="A">Active
<OPTION <?php echo ($row_user['unix_status'] == 'S') ? 'selected ' : ''; ?>value="S">Suspended
<OPTION <?php echo ($row_user['unix_status'] == 'D') ? 'selected ' : ''; ?>value="D">Deleted
</SELECT>

<p>
Email:
<INPUT TYPE="TEXT" NAME="email" VALUE="<?php echo $row_user[email]; ?>" SIZE="25" MAXLENGTH="55">

<p><A href="user_changepw.php?user_id=<?php print $user_id; ?>">[Change User Password]</A><br>
<A href="/account/pending-resend.php?form_user=<?php print user_getname($user_id); ?>">[Resend Pending Mail]</a>
</p>

<p>
<b>
This pages allows to change only direct properties of user object. To edit
properties spanning across user/group pair, visit admin page of that
group.
</b>
</p>
<input type="submit" value="Update">
</FORM>

<hr>

<p>
<h3>Current Groups:</h3>

<?php
/*
	Iterate and show groups this user is in
*/
$res_cat = db_query("SELECT groups.unix_group_name, groups.group_name AS group_name, "
	. "groups.group_id AS group_id, "
	. "user_group.admin_flags AS admin_flags FROM "
	. "groups,user_group WHERE user_group.user_id=$user_id AND "
	. "groups.group_id=user_group.group_id");

	print "<table>";
	while ($row_cat = db_fetch_array($res_cat)) {
		print ("<tr><td><b>$row_cat[group_name]</b> ("
			."<a href=\"/projects/".$row_cat[unix_group_name]."\">"
			.$row_cat[unix_group_name]."</a>)</td>"
			. "<td><a href=\"/project/admin/?group_id=$row_cat[group_id]\">[Remove User from Group]</a></td>");
		print '<td><A HREF="/project/admin/userperms.php?group_id='.$row_cat['group_id'].'">[Edit Permissions]</A></td></tr>';
	}
	print "</table>";

html_feedback_bottom($feedback);

site_admin_footer(array());

?>
