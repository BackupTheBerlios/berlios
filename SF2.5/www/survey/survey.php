<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: survey.php,v 1.2 2003/11/13 11:29:27 helix Exp $

require('pre.php');
require('vote_function.php');
require('../survey/survey_utils.php');

// Check to make sure they're logged in.
if (!user_isloggedin()) {
	exit_not_logged_in();
}

survey_header(array('title'=>'Survey'));

if (!$survey_id || !$group_id) {
	echo "<H1>For some reason, the Group ID or Survey ID did not make it to this page</H1>";
} else {
	show_survey($group_id,$survey_id);
}

survey_footer(array());

?>
