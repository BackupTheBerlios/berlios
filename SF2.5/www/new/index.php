<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.4 2004/11/16 12:01:10 helix Exp $

require "pre.php";    
require "vote_function.php";
require "donate.php";    
$HTML->header(array("title"=>"New File Releases"));

if ( !$offset || $offset < 0 ) {
	$offset = 0;
}

// For expediancy, list only the filereleases in the past three days.
//$start_time = time() - (7 * 86400);
$start_time = 0;

$query	= "SELECT groups.group_name,"
	. "groups.group_id,"
	. "groups.unix_group_name,"
	. "groups.short_description,"
	. "users.user_name,"
	. "users.user_id,"
	. "frs_release.release_id,"
	. "frs_release.name AS release_version,"
	. "frs_release.release_date,"
	. "frs_release.released_by,"
	. "frs_package.name AS module_name "
	. "FROM groups,users,frs_package,frs_release "
	. "WHERE ( frs_release.release_date > $start_time "
	. "AND frs_release.package_id = frs_package.package_id "
	. "AND frs_package.group_id = groups.group_id "
	. "AND frs_release.released_by = users.user_id "
	. "AND frs_package.status_id=1 "
	. "AND frs_release.status_id=1 ) "
//
//appears that this group by is unnecessary in this query
//	. "GROUP BY groups.group_name,groups.group_id,groups.unix_group_name,"
//	."groups.short_description,users.user_name,users.user_id,frs_release.release_id "
	. "ORDER BY frs_release.release_date DESC";
$res_new = db_query($query,21,$offset);

if (!$res_new || db_numrows($res_new) < 1) {
	echo $query . "<BR><BR>";
	echo db_error();
	echo "<H1>No new releases found. </H1>";
} else {

	if ( db_numrows($res_new) > 20 ) {
		$rows = 20;
	} else {
		$rows = db_numrows($res_new);
	}

	print "\t<p><TABLE width=100% cellpadding=0 cellspacing=0 border=0>";
	for ($i=0; $i<$rows; $i++) {
		$row_new = db_fetch_array($res_new);
		// don't show releases of projects without short description
		// 2004-11-16 by helix
		// avoid duplicates of different file types
		// 2003-04-25 use release id instead of group id by helix
		if ($row_new[short_description] && !($G_RELEASE["$row_new[release_id]"])) {
			print "<TR valign=top>";
			print "<TD colspan=2>";
			print "<A href=\"/projects/$row_new[unix_group_name]/\"><B>$row_new[group_name]</B></A>".req_project_donate($row_new[group_id])
				. "\n</TD><TD nowrap><I>Released by: <A href=\"/users/$row_new[user_name]/\">"
				. "$row_new[user_name]</A></I>".is_project_donor($row_new[user_id]).is_user_donor($row_new[user_id]).req_user_donate($row_new[user_id])."</TD></TR>\n";	

			print "<TR><TD>Module: $row_new[module_name]</TD>\n";
			print "<TD>Version: $row_new[release_version]</TD>\n";
			print "<TD>" . date("M d, h:iA",$row_new[release_date]) . "</TD>\n";
			print "</TR>";

			print "<TR valign=top>";
			print "<TD colspan=2>&nbsp;<BR>";
			if ($row_new[short_description]) {
				print "<I>$row_new[short_description]</I>";
			} else {
				print "<I>This project has not submitted a description.</I>";
			}
			// print "<P>Release rating: ";
			// print vote_show_thumbs($row_new[filerelease_id],2);
			print "</TD>";
			print '<TD align=center nowrap border=1>';
			// print '&nbsp;<BR>Rate this Release!<BR>';
			// print vote_show_release_radios($row_new[filerelease_id],2);
			print "&nbsp;</TD>";
			print "</TR>";

			print '<TR><TD colspan=3>';
			// link to whole file list for downloads
			print "&nbsp;<BR><A href=\"/project/showfiles.php?group_id=$row_new[group_id]&release_id=$row_new[release_id]\">";
			print "Download</A> ";
			print '(Project Total: '.$row_new[downloads].') | ';
			// notes for this release
			print "<A href=\"/project/shownotes.php?release_id=".$row_new[release_id]."\">";
			print "Notes & Changes</A>";
			print '<HR></TD></TR>';

			// 2003-04-25 use release id instead of group id by helix
			$G_RELEASE["$row_new[release_id]"] = 1;
		}
	}

	echo "<TR BGCOLOR=\"#EEEEEE\"><TD>";
        if ($offset != 0) {
		echo "<B>";
        	echo "<A HREF=\"/new/?offset=".($offset-20)."\"><B>" . 
			html_image("images/t2.gif","15","15",array("BORDER"=>"0","ALIGN"=>"MIDDLE")) . 
			" Newer Releases</A></B>";
        } else {
        	echo "&nbsp;";
        }

	echo "</TD><TD COLSPAN=\"2\" ALIGN=\"RIGHT\">";
	if (db_numrows($res_new)>$rows) {
		echo "<B>";
		echo "<A HREF=\"/new/?offset=".($offset+20)."\"><B>Older Releases " .
		html_image("images/t.gif","15","15",array("BORDER"=>"0","ALIGN"=>"MIDDLE")) . 
		"</A></B>";
	} else {
		echo "&nbsp;";
	}
	echo "</TD></TR></TABLE>";

}

$HTML->footer(array());

?>
