<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.1 2004/03/16 15:12:21 helix Exp $

require ('pre.php');    

if ($group_id) {
//only projects can use the bug tracker, and only if they have it turned on
$project=project_get_object($group_id);

if (!$project->isProject()) {
 	exit_error('Error','Only Projects Can Use SVN');
}
if (!$project->usesSVN()) {
	exit_error('Error','This Project Has Turned Off SVN');
}

site_project_header(array('title'=>'SVN Repository','group'=>$group_id,'toptab'=>'svn'));

$res_grp = db_query("SELECT * FROM groups WHERE group_id=$group_id");

$row_grp = db_fetch_array($res_grp);

// ######################## table for summary info

print '<TABLE width="100%"><TR valign="top"><TD width="65%">'."\n";

// ######################## Service not yet provided

print '
<h2><font color="red">The Subversion Service is in Test Phase!<br>
Please report <a href="http://developer.berlios.de/bugs/?group_id=1">Bugs</a>.</font></h2>';

// ######################## anonymous SVN instructions

if ($row_grp['is_public']) {

// 2003-03-24 add module description by helix
	print '<P><B>Anonymous SVN Access</B>
<P>This project\'s '.$sys_default_name.' SVN repository can be checked out through anonymous
(svnserve) SVN with the following instruction. The project you wish
to check out must be specified as the <I>projectname</I>.
To see the list of the projects available in the repository
use the <a href="http://'.$sys_svn_host.'/viewcvs/'
.$row_grp['unix_group_name'].'">web-based SVN repository access</a>.

<P><TT>svn checkout svn://'.$sys_svn_host.'/'.$row_grp['unix_group_name'].'
</TT>';
}

// ############################ developer access

print '<P><B>Developer SVN Access via SSH</B>
<P>Only project developers can access the SVN tree via this method. SSH2 must
be installed on your client machine. Substitute <I>projectname</I> and
<I>developername</I> with the proper values. Enter your site password when
prompted.

<P><TT>export SVN_SSH="ssh -l <I>developername</I>"
<BR>&nbsp;<BR>svn checkout svn+ssh://'.$sys_svn_host.'/svnroot/repos/'.$row_grp['unix_group_name'].'
</TT>';

// ################## summary info

print '</TD><TD width="35%">';
print $HTML->box1_top("Repository History");

// ################ is there commit info?

$res_svnhist = db_query("SELECT * FROM group_svn_history WHERE group_id='$group_id'");
if (db_numrows($res_svnhist) < 1) {
	print '<P>This project has no SVN history.';
} else {

print '<P><B>Developer (30 day/Commits) (30 day/Adds)</B><BR>&nbsp;';

while ($row_svnhist = db_fetch_array($res_svnhist)) {
	print '<BR>'.$row_svnhist['user_name'].' ('.$row_svnhist['svn_commits_wk'].'/'
		.$row_svnhist['svn_commits'].') ('.$row_svnhist['svn_adds_wk'].'/'
		.$row_svnhist['svn_adds'].')';
}

} // ### else no svn history

// ############################## SVN Browsing

if ($row_grp['is_public']) {
	print '<HR><B>Browse the SVN Tree</B>
<P>Browsing the SVN tree gives you a great view into the current status
of this project\'s code. You may also view the complete histories of any
file in the repository.
<UL>
<LI><A href="http://'.$sys_svn_host.'/viewcvs/'
.$row_grp['unix_group_name'].'"><B>Browse SVN Repository</B>';
}

print $HTML->box1_bottom();

print '</TD></TR></TABLE>';

} else {
	exit_no_group();
}

site_project_footer(array());

?>
