<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.3 2003/11/24 10:27:48 helix Exp $

require ('pre.php');    

if ($group_id) {
//only projects can use the bug tracker, and only if they have it turned on
$project=project_get_object($group_id);

if (!$project->isProject()) {
 	exit_error('Error','Only Projects Can Use CVS');
}
if (!$project->usesCVS()) {
	exit_error('Error','This Project Has Turned Off CVS');
}

site_project_header(array('title'=>'CVS Repository','group'=>$group_id,'toptab'=>'cvs'));

$res_grp = db_query("SELECT * FROM groups WHERE group_id=$group_id");

$row_grp = db_fetch_array($res_grp);

// ######################## table for summary info

print '<TABLE width="100%"><TR valign="top"><TD width="65%">'."\n";

// ######################## anonymous CVS instructions

if ($row_grp['is_public']) {

// 2003-03-24 add module description by helix
	print '<P><B>Anonymous CVS Access</B>
<P>This project\'s '.$sys_default_name.' CVS repository can be checked out through anonymous
(pserver) CVS with the following instruction set. The module you wish
to check out must be specified as the <I>modulename</I>
(modules are top level directories in the repository).
To see the list of the modules available in the repository
use the <a href="http://'.$sys_cvs_host.'/cgi-bin/viewcvs.cgi/'
.$row_grp['unix_group_name'].'">web-based CVS repository access</a>.
When prompted
for a password for <I>anonymous</I>, simply press the Enter key.

<P><TT>cvs -d:pserver:anonymous@cvs.'.$row_grp['http_domain'].':/cvsroot/'.$row_grp['unix_group_name'].' login
<BR>&nbsp;<BR>cvs -z3 -d:pserver:anonymous@cvs.'.$row_grp['http_domain'].':/cvsroot/'.$row_grp['unix_group_name'].' co <I>modulename</I>
</TT>

<P>Updates from within the module\'s directory do not need the -d parameter.';
}

// ############################ developer access

print '<P><B>Developer CVS Access via SSH</B>
<P>Only project developers can access the CVS tree via this method. SSH2 must
be installed on your client machine. Substitute <I>modulename</I> and
<I>developername</I> with the proper values. Enter your site password when
prompted.

<P><TT>export CVS_RSH=ssh
<BR>&nbsp;<BR>cvs -z3 -d<I>developername</I>@cvs.'.$row_grp['http_domain'].':/cvsroot/'.$row_grp['unix_group_name'].' co <I>modulename</I>
</TT>';

// ################## summary info

print '</TD><TD width="35%">';
print $HTML->box1_top("Repository History");

// ################ is there commit info?

$res_cvshist = db_query("SELECT * FROM group_cvs_history WHERE group_id='$group_id'");
if (db_numrows($res_cvshist) < 1) {
	print '<P>This project has no CVS history.';
} else {

print '<P><B>Developer (30 day/Commits) (30 day/Adds)</B><BR>&nbsp;';

while ($row_cvshist = db_fetch_array($res_cvshist)) {
	print '<BR>'.$row_cvshist['user_name'].' ('.$row_cvshist['cvs_commits_wk'].'/'
		.$row_cvshist['cvs_commits'].') ('.$row_cvshist['cvs_adds_wk'].'/'
		.$row_cvshist['cvs_adds'].')';
}

} // ### else no cvs history

// ############################## CVS Browsing

if ($row_grp['is_public']) {
	print '<HR><B>Browse the CVS Tree</B>
<P>Browsing the CVS tree gives you a great view into the current status
of this project\'s code. You may also view the complete histories of any
file in the repository.
<UL>
<LI><A href="http://'.$sys_cvs_host.'/cgi-bin/viewcvs.cgi/'
.$row_grp['unix_group_name'].'"><B>Browse CVS Repository</B>';
}

print $HTML->box1_bottom();

print '</TD></TR></TABLE>';

} else {
	exit_no_group();
}

site_project_footer(array());

?>
