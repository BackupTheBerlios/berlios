<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: project_home.php,v 1.5 2004/03/17 16:40:38 helix Exp $

require ('vote_function.php');
require ('vars.php');
require ($DOCUMENT_ROOT.'/news/news_utils.php');
require ('trove.php');
require ('project_summary.php');

//make sure this project is NOT a foundry
if (!$project->isProject()) {
	header ("Location: /foundry/". $project->getUnixName() ."/");
	exit;
}       

$title = 'Project Info - '. $project->getPublicName();

site_project_header(array('title'=>$title,'group'=>$group_id,'toptab'=>'home'));


// ########################################### end top area

// two column deal
?>

<TABLE WIDTH="100%" BORDER="0">
<TR><TD WIDTH="99%" VALIGN="top">
<?php 

// ########################################## top area, not in box 
$res_admin = db_query("SELECT users.user_id AS user_id,users.user_name AS user_name "
	. "FROM users,user_group "
	. "WHERE user_group.user_id=users.user_id AND user_group.group_id=$group_id AND "
	. "user_group.admin_flags = 'A'");

if ($project->getStatus() == 'H') {
	print "<P>NOTE: This project entry is maintained by the ".$GLOBALS['sys_default_name']." staff. We are not "
		. "the official site "
		. "for this product. Additional copyright information may be found on this project's homepage.\n";
}

if ($project->getDescription()) {
	print "<P>" . $project->getDescription();
} else {
	print "<P>This project has not yet submitted a description.";
}

// trove info
print '<BR>&nbsp;<BR>';
trove_getcatlisting($group_id,0,1);
//print '<BR>&nbsp;';

// Get the activity percentile
$actv = db_query("SELECT percentile FROM project_weekly_metric WHERE group_id='$group_id'");
$actv_res = db_result($actv,0,"percentile");
if (!$actv_res) $actv_res=0;

print("Registered: " . date($sys_datefmt, $project->getStartDate()));
print '<br>Activity Percentile (last week): ' . $actv_res . '%';
print '<br>View project activity <a href="/project/stats/?group_id='.$group_id.'">statistics</a>';
print '<br>View project web <a href="http://'.$project->getUnixName().'.berlios.de/usage">statistics</a>';
print '<br>View list of <a href="/export/rss_project.php?group_id='.$group_id.'">RSS feeds</a> available for this project';

$jobs_res = db_query("SELECT name ".
                "FROM people_job,people_job_category ".
                "WHERE people_job.category_id=people_job_category.category_id ".
                "AND people_job.status_id=1 ".
                "AND group_id='$group_id' ".
                "GROUP BY name",2);
if ($jobs_res) {
	$num=db_numrows($jobs_res);
        if ($num>0) {
        	print '<br><br>HELP WANTED: This project is looking for ';
                if ($num==1) {
                	print '<a href="/people/?group_id='.$group_id.'">'.
                              db_result($jobs_res,0,"name").'(s)</a>';
                } else {
                	print 'People to fill '.
                        '<a href="/people/?group_id='.$group_id.'">several '.
                        'different positions</a>';
                }
        }
}


print '</TD><TD NoWrap VALIGN="top">';

// ########################### Developers on this project

echo $HTML->box1_top($Language->DEVELOPER_INFO);
?>
<?php
if (db_numrows($res_admin) > 0) {

	?>
	<SPAN CLASS="develtitle"><?php echo $Language->PROJECT_ADMINS; ?>:</SPAN><BR>
	<?php
		while ($row_admin = db_fetch_array($res_admin)) {
			print "<A href=\"/users/$row_admin[user_name]/\">$row_admin[user_name]</A><BR>";
		}
	?>
	<HR WIDTH="100%" SIZE="1" NoShade>
	<?php

}

?>
<SPAN CLASS="develtitle"><?php echo $Language->DEVELOPERS; ?>:</SPAN><BR>
<?php
//count of developers on this project
$res_count = db_query("SELECT user_id FROM user_group WHERE group_id=$group_id");
print db_numrows($res_count);

?>

<A HREF="/project/memberlist.php?group_id=<?php print $group_id; ?>">[View Members]</A>
<?php 

echo $HTML->box1_bottom();

print '
</TD></TR>
</TABLE>
<P>
';

// 2004-02-12 by helix

$res_donate = db_query("SELECT * FROM group_donate WHERE group_id='$group_id'");
if ($res_donate && db_numrows($res_donate) > 0) {
  echo '
<p><table cellspacing="1" cellpadding="5" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td valign="top"><a href="/donate/projects.php?group_id='.$group_id.'"><img src="/images/x-click-but7.gif" align="top" border="0" width="72" height="29" alt="[Donate to '.$project->getPublicName().']"></a></td>
<td valign="top">Do you like "'.$project->getPublicName().'" and what we do for the Open Source community? Consider supporting the project so that we can spend more time to develop Open Source software. Besides doing a good deed for the community, you\'ll also be listed on the <a href="/donate/contributors.php?group_id='.$group_id.'">project\'s contributors page</a>.</td>
</tr></table>
';
}

// 2004-02-12 by helix (end)

// 2003-07-16 by Masato

echo $HTML->box1_top("Berlios Services for Developers");
echo '<TABLE cellspacing="5" cellpadding="5" width="100%" border="0">'.
	'<TR bgcolor="'.$GLOBALS['COLOR_LTBACK1'].'">'.
	'<TD align="center" WIDTH="33%">'.
	'<A HREF="http://devcounter.berlios.de/">DevCounter</A>'.
	'</TD><TD align="center" WIDTH="33%">'.
	'<A HREF="http://sourcewell.berlios.de/">SourceWell</A>'.
	'</TD><TD align="center">'.
	'<A HREF="http://docswell.berlios.de/">Docswell</A>'.
	'</TD></TR>'.
	'<TR bgcolor="'.$GLOBALS['COLOR_LTBACK1'].'">'.
	'<TD align="center">'.
	'An open, free & independent developer pool '.
	'created to help developers find other developers, help, '.
	'testers and new project members.'.
	'</TD><TD align="center" VALIGN="TOP">'.
	'Open Source Software Announcement & Retrieval Sytem'.
	'</TD><TD align="center" VALIGN="TOP">'.
	'Documents Announcement & Retrieval System'.
	'</TD></TR>'.
	'</TABLE>';
echo $HTML->box1_bottom();
echo '<BR>';

// 2003-07-16 by Masato (end)

// ############################# File Releases

// 2002-02-25 by helix
if ($project->usesFiles()) {
	echo $HTML->box1_top($Language->LATEST_FILE_RELEASES); 
	$unix_group_name = $project->getUnixName();

	echo '
	<TABLE cellspacing="1" cellpadding="5" width="100%" border="0">
		<TR bgcolor="'.$GLOBALS['COLOR_LTBACK1'].'">
		<TD align="left"">
			'.$Language->FILE_PACKAGE.'
		</td>
		<TD align="center">
			'.$Language->FILE_VERSION.'
		</td>
		<td align="center">
			'.$Language->FILE_REL_DATE.'
		</td>
		<TD align="center">
			'.$Language->FILE_NOTES.' / '.$Language->FILE_MONITOR.'
		</td>
		<TD align="center">
			'.$Language->FILE_DOWNLOAD.'
		</td>
		</TR>';

		$sql="SELECT frs_package.package_id,frs_package.name AS package_name,frs_release.name AS release_name,frs_release.release_id AS release_id,frs_release.release_date AS release_date ".
			"FROM frs_package,frs_release ".
			"WHERE frs_package.package_id=frs_release.package_id ".
			"AND frs_package.group_id='$group_id' ".
			"AND frs_package.status_id=1 ".
			"AND frs_release.status_id=1 ".
			"ORDER BY frs_package.package_id,frs_release.release_date DESC";

		$res_files = db_query($sql);
		$rows_files=db_numrows($res_files);
		if (!$res_files || $rows_files < 1) {
			echo db_error();
			// No releases
			echo '<TR BGCOLOR="'.$GLOBALS['COLOR_LTBACK1'].'"><TD COLSPAN="4"><B>This Project Has Not Released Any Files</B></TD></TR>';

		} else {
			/*
				This query actually contains ALL releases of all packages
				We will test each row and make sure the package has changed before printing the row
			*/
			for ($f=0; $f<$rows_files; $f++) {
				if (db_result($res_files,$f,'package_id')==db_result($res_files,($f-1),'package_id')) {
					//same package as last iteration - don't show this release
				} else {
					$rel_date = getdate(db_result($res_files,$f,'release_date'));
					echo '
					<TR BGCOLOR="'.$GLOBALS['COLOR_LTBACK1'].'" ALIGN="center">
					<TD ALIGN="left">
					<B>' . db_result($res_files,$f,'package_name'). '</B></TD>';
					// Releases to display
					print '<TD>'.db_result($res_files,$f,'release_name') .'
					</TD>
					<td>' . $rel_date["month"] . ' ' . $rel_date["mday"] . ', ' . $rel_date["year"] . '</td>
					<TD><A href="/project/shownotes.php?group_id=' . $group_id . '&release_id=' . db_result($res_files,$f,'release_id') . '">';
					echo html_image("images/ic/manual16c.png",'15','15',array('alt'=>'Release Notes'));
					echo '</A> - <A HREF="/project/filemodule_monitor.php?filemodule_id=' .	db_result($res_files,$f,'package_id') . '">';
					echo html_image("images/ic/mail16d.png",'15','15',array('alt'=>'Monitor This Package'));
					echo '</A>
					</TD>
					<TD><A HREF="/project/showfiles.php?group_id=' . $group_id . '&release_id=' . db_result($res_files,$f,'release_id') . '">'.$Language->FILE_DOWNLOAD.'</A></TD></TR>';
				}
			}

		}
		?></TABLE>
	<p align="center">
	<a href="/project/showfiles.php?group_id=<?php print $group_id; ?>">[View ALL Project Files]</A>
	</p>
<?php
	echo $HTML->box1_bottom();
// 2002-02-25 by helix
}
?>
<P>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR><TD VALIGN="top">

<?php

// ############################## PUBLIC AREAS
echo $HTML->box1_top($Language->PUBLIC_AREA); 

// ################# Homepage Link

print "<A href=\"http://" . $project->getHomePage() . "\">";
print html_image("images/ic/home16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_HOMEPAGE));
print '&nbsp;'.$Language->GROUP_LONG_HOMEPAGE.'</A>';

// ################## forums

if ($project->usesForum()) {
	print '<HR SIZE="1" NoShade><A href="/forum/?group_id='.$group_id.'">';
	print html_image("images/ic/notes16.png",'20','20',array('alt'=>$Language->GROUP_SHORT_FORUM)); 
	print '&nbsp;'.$Language->GROUP_LONG_FORUM.'</A>';
	print " ( <B>". project_get_public_forum_count($group_id) ."</B> messages in ";

	print "<B>". project_get_public_forum_message_count($group_id) ."</B> forums )\n";
}

// ##################### Bug tracking

if ($project->usesBugs()) {
	print '<HR SIZE="1" NoShade><A href="/bugs/?group_id='.$group_id.'">';
	print html_image("images/ic/bug16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_BUGS)); 
	print '&nbsp;'.$Language->GROUP_LONG_BUGS.'</A>';
	print " ( <B>". project_get_open_bug_count($group_id) ."</B>";
	print " open bugs, <B>". project_get_total_bug_count($group_id) ."</B> total )";
}

// ##################### Support Manager
 
if ($project->usesSupport()) {
	print '
		<HR SIZE="1" NoShade>
		<A href="/support/?group_id='.$group_id.'">';
	print html_image("images/ic/support16b.jpg",'20','20',array('alt'=>$Language->GROUP_SHORT_SUPPORT));
	print '&nbsp;'.$Language->GROUP_LONG_SUPPORT.'</A>';
	print " ( <B>". project_get_open_support_count($group_id) ."</B>";
	print " open requests, <B>". project_get_total_support_count($group_id) ."</B> total )";
}

// ##################### Feature Requests

if ($project->usesFeature()) {
        print '
                <HR SIZE="1" NoShade>
                <A href="/feature/?group_id='.$group_id.'">';
        print html_image("images/ic/support16b.jpg",'20','20',array('alt'=>$Language->GROUP_SHORT_FEATURE));
        print '&nbsp;'.$Language->GROUP_LONG_FEATURE.'</A>';
        print " ( <B>". project_get_open_feature_count($group_id) ."</B>";
        print " open requests, <B>". project_get_total_feature_count($group_id) ."</B> total )";
}

// ##################### Doc Manager

if ($project->usesDocman()) {
	print '
	<HR SIZE="1" NoShade>
	<A href="/docman/?group_id='.$group_id.'">';
	print html_image("images/ic/docman16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_DOCMAN));
	print '&nbsp;'.$Language->GROUP_LONG_DOCMAN.'</A>';
}

// ##################### Patch Manager

if ($project->usesPatch()) {
	print '
		<HR SIZE="1" NoShade>
		<A href="/patch/?group_id='.$group_id.'">';
	print html_image("images/ic/patch16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_PATCH));
	print '&nbsp;'.$Language->GROUP_LONG_PATCH.'</A>';
	print " ( <B>". project_get_open_patch_count($group_id) ."</B>";
	print " open patches, <B>". project_get_total_patch_count($group_id) ."</B> total )";
}

// ##################### Mailing lists

if ($project->usesMail()) {
	print '<HR SIZE="1" NoShade><A href="/mail/?group_id='.$group_id.'">';
	print html_image("images/ic/mail16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_MAIL)); 
	print '&nbsp;'.$Language->GROUP_LONG_MAIL.'</A>';
	print " ( <B>". project_get_mail_list_count($group_id) ."</B> public mailing lists )";
}

// ##################### Task Manager 

if ($project->usesPm()) {
	print '<HR SIZE="1" NoShade><A href="/pm/?group_id='.$group_id.'">';
	print html_image("images/ic/taskman16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_PM));
	print '&nbsp;'.$Language->GROUP_LONG_PM.'</A>';
	$sql="SELECT * FROM project_group_list WHERE group_id='$group_id' AND is_public=1";
	$result = db_query ($sql);
	$rows = db_numrows($result);
	if (!$result || $rows < 1) {
		echo '<BR><I>There are no public subprojects available</I>';
	} else {
		for ($j = 0; $j < $rows; $j++) {
			echo '
			<BR> &nbsp; - <A HREF="/pm/task.php?group_project_id='.db_result($result, $j, 'group_project_id').
			'&group_id='.$group_id.'&func=browse">'.db_result($result, $j, 'project_name').'</A>';
		}

	}
}

// ######################### Surveys 

if ($project->usesSurvey()) {
	print '<HR SIZE="1" NoShade><A href="/survey/?group_id='.$group_id.'">';
	print html_image("images/ic/survey16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_SURVEY));
	print " ".$Language->GROUP_LONG_SURVEY."</A>";
	echo ' ( <B>'. project_get_survey_count($group_id) .'</B> surveys )';
}

// ######################### CVS 

if ($project->usesCVS()) {
	print '<HR SIZE="1" NoShade><A href="/cvs/?group_id='.$group_id.'">';
	print html_image("images/ic/cvs16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_CVS));
	print " ".$Language->GROUP_LONG_CVS."</A>";
	$sql = "SELECT SUM(cvs_commits) AS commits,SUM(cvs_adds) AS adds from stats_project where group_id='$group_id'";
	$result = db_query($sql);
        $cvs_commit_num=db_result($result,0,0);
        $cvs_add_num=db_result($result,0,1);
        if (!$cvs_commit_num) $cvs_commit_num=0;
        if (!$cvs_add_num) $cvs_add_num=0;
	echo ' ( <B>'.$cvs_commit_num.'</B> commits, <B>'.$cvs_add_num.'</B> adds )';
        if ($cvs_commit_num || $cvs_add_num) {
        	echo '<br> &nbsp; - <a href="http://'.$sys_cvs_host
                     .'/cgi-bin/viewcvs.cgi/'.$project->getUnixName()
                     .'">Browse CVS</a>';
        }

}

// ######################### SVN

if ($project->usesSVN()) {
        print '<HR SIZE="1" NoShade><A href="/svn/?group_id='.$group_id.'">';
        print html_image("images/ic/svn16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_SVN));
        print " ".$Language->GROUP_LONG_SVN."</A>";
//        $sql = "SELECT SUM(cvs_commits) AS commits,SUM(cvs_adds) AS adds from stats_project where group_id='$group_id'";
//        $result = db_query($sql);
//        $cvs_commit_num=db_result($result,0,0);
//        $cvs_add_num=db_result($result,0,1);
//        if (!$cvs_commit_num) $cvs_commit_num=0;
//        if (!$cvs_add_num) $cvs_add_num=0;
//        echo ' ( <B>'.$cvs_commit_num.'</B> commits, <B>'.$cvs_add_num.'</B> adds )';
//        if ($cvs_commit_num || $cvs_add_num) {
                echo '<br> &nbsp; - <a href="http://'.$sys_svn_host
                     .'/viewcvs/'.$project->getUnixName()
                     .'">Browse SVN</a>';
//        }

}

// ######################## AnonFTP 

// 2003-02-21 helix
if ($project->usesFtp()) {
	print '<HR SIZE="1" NoShade>';
	print "<A href=\"ftp://" . $sys_ftp_host . "/pub/". $project->getUnixName() ."/\">";
	print html_image("images/ic/ftp16b.png",'20','20',array('alt'=>$Language->GROUP_LONG_FTP));
	print " ".$Language->GROUP_LONG_FTP."</A>";
}

// 2003-02-21 helix
// ######################### Screenshots

if ($project->usesScreenshots()) {
	print '<HR SIZE="1" NoShade><A href="/screenshots/?group_id='.$group_id.'">';
	print html_image("images/ic/frame_image16b.png",'20','20',array('alt'=>$Language->GROUP_SHORT_SCREENSHOTS));
	print " ".$Language->GROUP_LONG_SCREENSHOTS."</A>";
	echo ' ( <B>'. project_get_screenshots_count($group_id) .'</B> screenshots )';
}

// 2003-01-31 helix
// ######################## Wiki

if ($project->usesWiki()) {
        print '<HR SIZE="1" NoShade><A href="/wiki/?group_id='.$group_id.'">';
        print html_image("images/ic/manual16b.png",'20','20',array('alt'=>$Language->GROUP_LONG_WIKI));
	print " ".$Language->GROUP_LONG_WIKI."</A>";
}

$HTML->box1_bottom();

if ($project->usesNews()) {
	// COLUMN BREAK
	?>

	</TD>
	<TD WIDTH="15">&nbsp;</TD>
	<TD VALIGN="top">

	<?php
	// ############################# Latest News

	echo $HTML->box1_top($Language->GROUP_LONG_NEWS);

	echo news_show_latest($group_id,10,false);

	echo $HTML->box1_bottom();
}

?>
</TD>

</TR></TABLE>

<?php

site_project_footer(array());

?>
