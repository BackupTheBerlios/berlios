<?php
/**
  *
  * Project Admin page to manage group's VHOST entries
  *
  * SourceForge: Breaking Down the Barriers to Open Source Development
  * Copyright 1999-2001 (c) VA Linux Systems
  * http://sourceforge.net
  *
  * @version   $Id: vhost.php,v 1.4 2004/05/25 15:18:33 helix Exp $
  *
  */


require_once('pre.php');
require_once('account.php');
require_once($DOCUMENT_ROOT.'/project/admin/project_admin_utils.php');

session_require(array('group'=>$group_id,'admin_flags'=>'A'));

$group = &group_get_object($group_id);

if (!$group || !is_object($group)) {
        exit_error('Error','Error creating group object');
} else if ($group->isError()) {
        exit_error('ERROR',$group->getErrorMessage());
}

if ($createvhost) {

	$homedir = account_group_homedir($group->getUnixName());

	switch ($vhost_type) {
	case '1':
		$docdir = $homedir.'/htdocs/';
		$cgidir = $homedir.'/cgi-bin/';
		$logdir = $homedir.'/log/';
		break;
	case '2':
		$docdir = $homedir.'/vhost/'.$vhost_name.'/htdocs/';
		$cgidir = $homedir.'/vhost/'.$vhost_name.'/cgi-bin/';
                $logdir = $homedir.'/vhost/'.$vhost_name.'/log/';
	}

	if (valid_hostname($vhost_name)) {

		$res = db_query("
			SELECT vhost_name FROM prweb_vhost WHERE vhost_name='$vhost_name'
		");

		if ($res && db_numrows($res) < 1) {

			$res = db_query("
				INSERT INTO prweb_vhost(vhost_name, docdir, cgidir, logdir, group_id, state) 
				values ('$vhost_name','$docdir','$cgidir','$logdir','$group_id','1')
			"); 

			if (!$res || db_affected_rows($res) < 1) {
				$feedback .= "Cannot insert Virtual Host entry: ".db_error();
			} else {
				$feedback .= "Virtual Host scheduled for creation.";
				$group->addHistory('Added vhost '.$vhost_name.' ','');
			}
		} else {
			$feedback .= "Virtual Hostname already in use - $vhost_name";
		}
	} else {
		$feedback .= "Not a valid hostname - $vhost_name"; 
	}
}


if ($deletevhost) {

	//schedule for deletion

	$res =	db_query("
		SELECT * 
		FROM prweb_vhost 
		WHERE vhostid='$vhostid'
	");

	$row_vh = db_fetch_array($res);

	$res = db_query("
		UPDATE prweb_vhost 
		SET state='2'
		WHERE vhostid='$vhostid' 
		AND group_id='$group_id'
	");

	if (!$res || db_affected_rows($res) < 1) {
		$feedback .= "Could not delete Virtual Host entry:".db_error();
	} else {
		$feedback .= "Virtual Host deleted";	
		$group->addHistory('Virtual Host '.$row_vh['vhost_name'].' Removed','');

	}

}

project_admin_header(array('title'=>'Editing Virtual Host Info','group'=>$group_id,'pagename'=>'project_admin_vhost','sectionvals'=>array(group_getname($group_id))));

?>

<h2>Virtual Host Admin</h2>

<h3>Add New Virtual Host</h3>
<p>
To add a new virtual host - simply point a <b>CNAME</b> for <i>yourhost.org</i> at
<b>www.<?php echo $GLOBALS['sys_default_domain']; ?></b>. <?php echo $GLOBALS['sys_default_name']; ?> does not currently host mail (i.e. cannot be an MX)
or DNS</b>.  
<p>
Clicking on "create" will schedule the creation of the Virtual Host.  This will be
synced to the project webservers - such that <i>yourhost.org</i> will display the 
material at <i><?php echo $group->getUnixName(); ?>.berlios.de</i>.

<p>

<form name="new_vhost" action="<?php echo $PHP_SELF.'?group_id='.$group_id.'&createvhost=1'; ?>" method="post"> 
<table border = 0>
<tr>
	<td> New Virtual Host </td>
	<td> <input type="text" size="15" maxlength="255" name="vhost_name"> </td>
	<td> (e.g. <tt><?php echo $group->getUnixName(); ?>.org)</tt> </td>
</tr>
<tr>
	<td> Virtual Host Type </td>
	<td><select name="vhost_type">
		<option value="1" selected>Default Root</option>
		<option value="2">Additional VHOST Root</option>
	</select></td>
</tr>
<tr>
        <td> &nbsp; </td>
	<td> <input type="submit" value="Create"> </td>
</tr>
</table>
</form>

<h3>Defined Virtual Hosts</h3>

<?php

$res_db = db_query("
	SELECT *
	FROM prweb_vhost 
	WHERE group_id='".$group_id."'
");
	
if (db_numrows($res_db) > 0) {

        print '<table width="50%"><tr><td>';

       	$title=array();
       	$title[]='Virtual Host';
	$title[]='Htdocs Directory';
        $title[]='Cgi Directory';
        $title[]='Log Directory';
	$title[]='State';
       	$title[]='Operations';
	echo html_build_list_table_top($title);

	while ($row_db = db_fetch_array($res_db)) {

		print '	<tr>
			<td>'.$row_db['vhost_name'].'</td>
                        <td>'.$row_db['docdir'].'</td>
                        <td>'.$row_db['cgidir'].'</td>
                        <td>'.$row_db['logdir'].'</td>
		';
		if ($row_db['state'] == '1') {
			print '
                        <td>Active</td>';
		} elseif ($row_db['state'] == '2') {
			print '
                        <td>Deleted</td>';
		} else {
			print '
                        <td>Unknown</td>';
		}
		print '
			<td>[ <b><a href="'.$PHP_SELF.'?group_id='.$group_id.'&vhostid='.$row_db['vhostid'].'&deletevhost=1">Delete</a> </b>]
			</tr>	
		';
			 
	}
	print ' </table>';

        print '</td></tr></table>';

} else {
	echo '<p>No Virtual Hosts defined</p>';
}

project_admin_footer(array());

?>
