<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: survey_utils.php,v 1.3 2003/11/27 15:05:42 helix Exp $

/*
	Survey System
	By Tim Perdue, Sourceforge, 11/99
*/

function survey_header($params) {
	global $group_id,$is_admin_page,$DOCUMENT_ROOT;

	$params['toptab']='surveys';
	$params['group']=$group_id;

	$project=project_get_object($group_id);

	if (!$project->usesSurvey()) {
		exit_error('Error','This Group Has Turned Off Surveys');
	}

	site_project_header($params);

	echo "<P><B><A HREF=\"/survey/admin/?group_id=$group_id\">Admin</A>";

	if ($is_admin_page && $group_id) {
		echo " | <A HREF=\"/survey/admin/add_survey.php?group_id=$group_id\">Add Surveys</A>";
		echo " | <A HREF=\"/survey/admin/edit_survey.php?group_id=$group_id\">Edit Surveys</A>";
		echo " | <A HREF=\"/survey/admin/add_question.php?group_id=$group_id\">Add Questions</A>";
		echo " | <A HREF=\"/survey/admin/show_questions.php?group_id=$group_id\">Edit Questions</A>";
		echo " | <A HREF=\"/survey/admin/show_results.php?group_id=$group_id\">Show Results</A></B>";
	}

	echo "<P>";

}

function survey_footer($params) {
	site_project_footer($params);
}

function ShowResultsEditSurvey($result) {
        global $group_id,$PHP_SELF;
        $rows  =  db_NumRows($result);
        $cols  =  db_NumFields($result);
        echo "<h3>$rows Found</h3>";

        $title_arr=array();
        $title_arr[]='Survey ID';
        $title_arr[]='Group ID';
        $title_arr[]='Survey Title';
        $title_arr[]='Survey Questions';
        $title_arr[]='Is Active';

        echo html_build_list_table_top ($title_arr);

        for ($j=0; $j<$rows; $j++)  {

                echo "<tr BGCOLOR=\"". html_get_alt_row_color($j) ."\">\n";

                echo "<TD><A HREF=\"edit_survey.php?group_id=$group_id&survey_id
=".
                        db_result($result,$j,0)."\">".sprintf("%06d",db_result($result,$j,0))."</A></TD>";
                for ($i=1; $i<$cols; $i++)  {
                        printf("<TD>%s</TD>\n",db_result($result,$j,$i));
                }

                echo "</tr>";
        }
        echo "</table>"; //</TD></TR></TABLE>";
}
?>
