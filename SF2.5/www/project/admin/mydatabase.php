<?php
/**
  *
  * Project Admin page to manage group's databases
  *
  * This page allows to request, change password of, and delete database.
  * Group may have single database of each type that provided (e.g., mysql,
  * pgsql, etc).
  *
  * SourceForge: Breaking Down the Barriers to Open Source Development
  * Copyright 1999-2001 (c) VA Linux Systems
  * http://sourceforge.net
  *
  * @version   $Id: mydatabase.php,v 1.2 2004/01/13 13:15:25 helix Exp $
  *
  */


require_once('pre.php');
require_once($DOCUMENT_ROOT.'/project/admin/project_admin_utils.php');

session_require(array('group'=>$group_id,'admin_flags'=>'A'));

$group = &group_get_object($group_id);
$user = &user_get_object(user_getid());

if ($createdb) {

	//mysql takes issue with database names that have dashes in them - so strip the dashes, replace with ""
	//e.g. free-mysql becomes freemysql (it's a workaround) 
	//if there is a dash in the groupname
	$dbname = str_replace("-", "", $group->getUnixName());
	
	//check there is no name double up - if there is - add an incrementing to the number to the end

	$dbname = prdb_namespace_seek($dbname);
	$randompw = random_pwgen();

	$res = db_query("
		INSERT INTO prdb_dbs(group_id,dbname,dbusername,dbuserpass,requestdate,dbtype,created_by,state) 
		VALUES($group_id,'$dbname','$dbname','$randompw','".time()."',$newdbtypeid,'".user_getid()."',2)
	");

	if (!$res || db_affected_rows($res) < 1) {
		$feedback .= 'Cannot add database entry: '.db_error();
	} else {
		$res = db_query("
			SELECT * 
			FROM prdb_types 
			WHERE dbtypeid='$newdbtypeid'
		");
		$row_db = db_fetch_array($res);
	
		$feedback .= "Database scheduled for creation.";
		group_add_history('Created database '.$dbname.' (type: '.$row_db['dbsoftware'].')','',$group_id);

		$message = "A database for the project will be created\n"
                . "automatically in about 6 hours. The database can\n"
                . "be managed either by mysql from your shell account\n"
                . "at $sys_default_name or via Web using\n\n"
                . "https://$sys_default_host/phpMyAdmin\n\n"
                . "The data to access your database are the following\n\n"
                . "    Hostname:    db.berlios.de\n"
                . "    Database:    $dbname\n"
                . "    Username:    $dbname\n"
                . "    Password:    $randompw\n\n"
                . " -- the $sys_default_name staff\n";

		mail($user->getEmail(),$sys_default_name." Database Creation",$message,"From: admin@$GLOBALS[sys_default_domain]");
	
	}

}

if ($updatedbrec) {

	if (strlen($pw) >= 8) {
	if ($pw == $pwconfirm) {

		if ($state == 2 || $state ==3 ) $dbstate = $state;
		else $dbstate = 4;

		//sync new password, and flag it as 'pending (an) update'

		$res = db_query("
			UPDATE prdb_dbs 
			SET dbuserpass = '$pw', 
				state = '$dbstate'
			WHERE dbid = '$dbid' 
			AND group_id = '$group_id'
		"); 

		if (!$res || db_affected_rows($res) < 1) {
			$feedback .= "Update failure - ".db_error()."";
		} else {
			$res = db_query("
				SELECT * 
				FROM prdb_types 
				WHERE dbtypeid='$dbtypeid'
			");
			$row_db = db_fetch_array($res);
			group_add_history('Updated database - (type: '.$row_db['dbsoftware'].')','',$group_id);

                        $message = "The database for the project will be updated\n"
                        . "automatically in about 6 hours. The database can\n"
                        . "be managed either by mysql from your shell account\n"
                        . "at $sys_default_name or via Web using\n\n"
                        . "https://$sys_default_host/phpMyAdmin\n\n"
                        . "The data to access your database are the following\n\n"
                        . "    Hostname:    db.berlios.de\n"
                        . "    Database:    $dbname\n"
                        . "    Username:    $dbname\n"
                        . "    Password:    $pw\n\n"
                        . " -- the $sys_default_name staff\n";

                        mail($user->getEmail(),$sys_default_name." Database Update",$message,"From: admin@$GLOBALS[sys_default_domain]");

		}
	} else {

		$feedback .= "Operation failed. Password and Password Confirm are not the same";

	}
	} else {

		$feedback .= "Operation failed. Password less than 8 characters";

	}

}

if ($deletedbconfirm) {

	//schedule for deletion

	$res = db_query("
		UPDATE prdb_dbs 
		SET state=3 
		WHERE dbid='$dbid'
		AND group_id='$group_id'
	");

	if (!$res || db_affected_rows($res) < 1) {
		$feedback .= 'Cannot delete database: '.db_error();
	} else {
		$feedback .= "Database scheduled for deletion";
	}

}

project_admin_header(array('title'=>'Editing Database Info','group'=>$group_id,'pagename'=>'project_admin_database','sectionvals'=>array(group_getname($group_id))));

if ($deletedb == 1) {

	print "<hr><b><center>Click to confirm deletion [ <a href=\"".$PHP_SELF."?deletedbconfirm=1&group_id=".$group_id."&dbid=$dbid\">CONFIRM DELETE</a> ] </center></b> <hr>";

}

print "<H2>Database Admin</H2>\n";

$res_db = db_query("
	SELECT * 
	FROM prdb_types 
	WHERE dbsoftware NOT IN (
		SELECT dbsoftware 
		FROM prdb_dbs,prdb_types 
		WHERE dbtypeid=dbtype 
		AND group_id=$group_id
		AND state IN (1,2,4)
	)
");

if (db_numrows($res_db) > 0) {

	print '
		<h3>Add New Database</h3>
		<p>
		<i>Clicking on "create" will schedule the creation of the database, and email the
		details to the project administrators.</i>

		<p>
		<b>Database Type:</b>
		<p>

		<FORM action="'.$PHP_SELF.'" method="post">
		<INPUT type="hidden" name="createdb" value="1">
		<INPUT type="hidden" name="group_id" value="'.$group_id.'">

		<select name="newdbtypeid">

	';

	while ($res_row = db_fetch_array($res_db)) {

		print "<option value=\"".$res_row['dbtypeid']."\">".$res_row['dbsoftware']."</option>"; 
	}

	print '

		</select>

		&nbsp; <INPUT type="submit" name="Create" value="Create">

		</p>
		</form>
	';	

} else {

  print "Maximum number of databases of all types have been allocated. <p>\n";

}

print "Your database can be managed with <a href=\"http://www.phpmyadmin.net/\">phpMyAdmin</a>:<br>\n";
print "<a href=\"https://".$GLOBALS['sys_default_host']."/phpMyAdmin\">https://".$GLOBALS['sys_default_host']."/phpMyAdmin</a><p>\n";

$res_db = db_query("
	SELECT * 
	FROM prdb_dbs,prdb_states,prdb_types 
	WHERE group_id=$group_id 
	AND stateid=state 
	AND dbtype=dbtypeid
");

if (db_numrows($res_db) > 0) {

	$title=array();
        $title[]='DB Name';
	$title[]='DB Type';
	$title[]='State';
	$title[]='New Password';
	$title[]='Confirm New';
	$title[]='Operations';

	echo html_build_list_table_top($title);

	while ($row_db = db_fetch_array($res_db)) {

		print '
			<tr bgcolor="'.html_get_alt_row_color($i++).'">

                        <td>'.$row_db['dbname'].'</td>
			<td>'.$row_db['dbsoftware'].'</td>
			<td>'.$row_db['statename'].'</td>
			';

		//if database is active or pending update allow the record to be deleted or password changed
		
		if (($row_db['state'] == 1) || ($row_db['state'] == 4) || ($row_db['state'] == 2)) {

			print '<form name="dbupdate" method="POST" action="'.$PHP_SELF.'?group_id='.$group_id.'">
				     <input type="hidden" name="dbid" value="'.$row_db['dbid'].'">
                                     <input type="hidden" name="dbname" value="'.$row_db['dbname'].'">
				     <input type="hidden" name="dbtypeid" value="'.$row_db['dbtypeid'].'">
                                     <input type="hidden" name="state" value="'.$row_db['state'].'">
				     <input type="hidden" name="updatedbrec" value="1">
				     <td><input type="text" name="pw" size="8" maxlength="16"> </td>
				     <td><input type="text" name="pwconfirm" size="8" maxlength="16"> </td>
				     <td>
				       <input type="submit" name="submit" value="Update">
				     </td>
				  </form> 
			';

		} else {
			print '
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			';
		}
		
		print '</tr>';

	}


	print '</table>';

} else {

	print '<h3>Current Databases</h3><p>There are no databases currently allocated to this group.';

}

project_admin_footer(array());

?>
