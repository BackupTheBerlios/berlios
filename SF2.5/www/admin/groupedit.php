<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: groupedit.php,v 1.4 2005/02/11 12:54:58 helix Exp $

require "pre.php";    
require "vars.php";
require($DOCUMENT_ROOT.'/admin/admin_utils.php');
require($DOCUMENT_ROOT.'/project/admin/project_admin_utils.php');

session_require(array('group'=>'1','admin_flags'=>'A'));

// group public choice
if ($Update) {
	$res_grp = db_query("SELECT * FROM groups WHERE group_id=$group_id");

	//audit trail
	if (db_result($res_grp,0,'status') != $form_status)
		{ group_add_history ('status',db_result($res_grp,0,'status'),$group_id);  }
	if (db_result($res_grp,0,'is_public') != $form_public)
		{ group_add_history ('is_public',db_result($res_grp,0,'is_public'),$group_id);  }
	if (db_result($res_grp,0,'type') != $group_type)
		{ group_add_history ('type',db_result($res_grp,0,'type'),$group_id);  }
	if (db_result($res_grp,0,'http_domain') != $form_domain)
		{ group_add_history ('http_domain',db_result($res_grp,0,'http_domain'),$group_id);  }
	if (db_result($res_grp,0,'unix_box') != $form_box)
		{ group_add_history ('unix_box',db_result($res_grp,0,'unix_box'),$group_id);  }

	if ($form_status=='A' && !sf_ldap_check_group($group_id)) {
		if (!sf_ldap_create_group($group_id)) {
			$feedback.=sf_ldap_get_error_msg();
		} else {
		//
		//	need to properly add all the admins to the group
		//	so their unix_uid gets set up
		//
		    $group=group_get_object($group_id,$res_grp);

		    $res_admin=db_query("SELECT users.user_name ".
			"FROM user_group,users ".
			"WHERE users.user_id=user_group.user_id ".
			"AND user_group.group_id='$group_id' ".
			"AND user_group.admin_flags='A'");

		    while ($row_admin=db_fetch_array($res_admin)) {
			    if (!$group->addUser($row_admin['user_name'])) {
				    echo $group->getErrorMessage();
		    	    }
		    }
		}

	} else if (sf_ldap_check_group($group_id)) {
		sf_ldap_remove_group($group_id);
	}

	if (sf_ldap_get_error_msg()) {
		$feedback .= sf_ldap_get_error_msg();
		group_add_history ('ldap:',sf_ldap_get_error_msg(),$group_id);
	} else {
		db_query("UPDATE groups SET is_public=$form_public,status='$form_status',"
		. "license='$form_license',type='$group_type',"
		. "unix_box='$form_box',http_domain='$form_domain' WHERE group_id=$group_id");
		if ($form_status == "D") {
			db_query("UPDATE prweb_vhost SET state=2 WHERE group_id=$group_id");
		}
		$feedback .= 'Updated Project Info<br>';
	}

	/*
		If this is a foundry, see if they have a preferences row, if not, create one
	*/
	if ($group_type=='2') {
		$res=db_query("SELECT * FROM foundry_data WHERE foundry_id='$group_id'");
		if (db_numrows($res) < 1) {
			group_add_history ('added foundry_data row','',$group_id);

			$feedback .= ' CREATING NEW FOUNDRY_DATA ROW ';
			$r=db_query("INSERT INTO foundry_data (foundry_id) VALUES ('$group_id')");
			if (!$r || db_affected_rows($r) < 1) {
				echo 'COULD NOT INSERT NEW FOUNDRY_DATA ROW: '.db_error();
			}
		}
	}
}

// get current information
$res_grp = db_query("SELECT * FROM groups WHERE group_id=$group_id");

if (db_numrows($res_grp) < 1) {
	exit_error("Invalid Group","Invalid group was passed in.");
}

$row_grp = db_fetch_array($res_grp);

site_admin_header(array('title'=>"Editing Group"));

echo '<h2>Group: '.$row_grp['group_name'].'</h2>' ;?>

<?php print "<h3><A href=\"/project/admin/?group_id=$group_id\">[Group Admin]</A>"; ?>

<br><A href="userlist.php?group_id=<?php print $group_id; ?>">[View/Edit Group Members]</A></h3>

<p>
<FORM action="<?php echo $PHP_SELF; ?>" method="POST">
Group Type:
<?php

echo show_group_type_box('group_type',$row_grp['type']);

?>

Status:
<SELECT name="form_status">
<OPTION <?php if ($row_grp['status'] == "I") print "selected "; ?> value="I">Incomplete</OPTION>
<OPTION <?php if ($row_grp['status'] == "A") print "selected "; ?> value="A">Active
<OPTION <?php if ($row_grp['status'] == "P") print "selected "; ?> value="P">Pending
<OPTION <?php if ($row_grp['status'] == "H") print "selected "; ?> value="H">Holding
<OPTION <?php if ($row_grp['status'] == "D") print "selected "; ?> value="D">Deleted
</SELECT>

Public:
<SELECT name="form_public">
<OPTION <?php if ($row_grp['is_public'] == 1) print "selected "; ?> value="1">Yes
<OPTION <?php if ($row_grp['is_public'] == 0) print "selected "; ?> value="0">No
</SELECT>

<p>License:
<SELECT name="form_license">
<OPTION value="none">N/A
<OPTION value="other">Other
<?php
	while (list($k,$v) = each($LICENSE)) {
		print "<OPTION value=\"$k\"";
		if ($k == $row_grp['license']) print " selected";
		print ">$v\n";
	}
?>
</SELECT>

<INPUT type="hidden" name="group_id" value="<?php print $group_id; ?>">
<p>Home Box: <INPUT type="text" name="form_box" value="<?php print $row_grp['unix_box']; ?>">
<p>HTTP Domain: <INPUT size=40 type="text" name="form_domain" value="<?php print $row_grp['http_domain']; ?>">
<p><INPUT type="submit" name="Update" value="Update">
</FORM>

<p><a href="newprojectmail.php?group_id=<?php print $group_id; ?>">[Send New Group Instruction Email]</a>

<?php

// ########################## OTHER INFO

print "<hr><h3>Other Information</h3>";

print "<p>Registered: ".date($sys_datefmt, $row_grp['register_time']);

print "<p>Unix Group Name: $row_grp[unix_group_name]";

print "<p>Submitted Description:</p> <blockquote>$row_grp[register_purpose]</blockquote>";

print "<p>License Other: </p> <blockquote>$row_grp[license_other]</blockquote>";

echo '
<p>'.show_grouphistory ($group_id);

site_admin_footer(array());

?>
