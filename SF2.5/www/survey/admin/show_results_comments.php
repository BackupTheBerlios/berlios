<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: show_results_comments.php,v 1.3 2003/11/27 15:05:42 helix Exp $

require('pre.php');
require('HTML_Graphs.php');
require('../survey_utils.php');
$is_admin_page='y';

if ($group_id && $survey_id && $question_id) {

if (!user_isloggedin() || !user_ismember($group_id,'A')) {
	exit_permission_denied();
	exit;
}

survey_header(array('title'=>'Survey Aggregate Results'));

Function  ShowResultComments($result) {
	global $survey_id;

	$rows  =  db_numrows($result);
	$cols  =  db_numfields($result);
	echo "<h3>$rows Found</h3>";

        $title_arr=array();
        $title_arr[]='User ID';
        $title_arr[]='Response';

        echo html_build_list_table_top ($title_arr);

        for($j=0; $j<$rows; $j++)  {

                echo "<tr BGCOLOR=\"". html_get_alt_row_color($j) ."\">\n";
		for($i=0; $i<$cols; $i++)  {
		    printf("<TD>%s</TD>\n",db_result($result,$j,$i));
		}
		echo "</tr>";
	}
	echo "</table>"; //</TD></TR></TABLE>";
}

$sql="SELECT question FROM survey_questions WHERE question_id='$question_id'";
$result=db_query($sql);
echo "<h2>Question: ".db_result($result,0,"question")."</H2>";
echo "<P>";

$sql="SELECT user_id,response FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$question_id' AND group_id='$group_id'";
$result=db_query($sql);
ShowResultComments($result);

survey_footer(array());

} else {
	exit_no_group();
}

?>
