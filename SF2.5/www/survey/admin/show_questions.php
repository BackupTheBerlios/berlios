<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: show_questions.php,v 1.4 2003/11/28 10:01:50 helix Exp $

require('pre.php');
require('../survey_utils.php');
$is_admin_page='y';

if ($group_id) {

if (!user_isloggedin() || !user_ismember($group_id,'A')) {
	exit_permission_denied();
	exit;
}

survey_header(array('title'=>'Survey Questions'));

?>

<H2>Existing Questions</H2>
<P>
You may use any of these questions on your surveys.
<P>
<B><FONT COLOR="RED">NOTE: use these Question ID's when you create a new survey.</FONT></B>
<P> 
<?php

Function  ShowResultsEditQuestion($result) {
	global $group_id;
	$rows  =  db_numrows($result);
	$cols  =  db_numfields($result);
	echo "<h3>$rows Found</h3>";

        $title_arr=array();
        $title_arr[]='Question ID';
        $title_arr[]='Question';
        $title_arr[]='Type';

        echo html_build_list_table_top ($title_arr);

	for($j=0; $j<$rows; $j++)  {

		echo( "<tr BGCOLOR=\"". html_get_alt_row_color($j) ."\">\n");

		echo "<TD><A HREF=\"edit_question.php?group_id=$group_id&question_id=".db_result($result,$j,"question_id")."\">".sprintf("%06d",db_result($result,$j,"question_id"))."</A></TD>\n";

		for($i=1; $i<$cols; $i++)  {
			printf("<TD>%s</TD>\n",db_result($result,$j,$i));
		}

		echo( "</tr>");
	}
	echo "</table>"; //</TD></TR></TABLE>");
}

/*
	Select this survey from the database
*/

$sql="SELECT survey_questions.question_id,survey_questions.question,survey_question_types.type ".
	"FROM survey_questions,survey_question_types ".
	"WHERE survey_question_types.id=survey_questions.question_type AND survey_questions.group_id='$group_id' ORDER BY survey_questions.question_id DESC";

$result=db_query($sql);

ShowResultsEditQuestion($result);

survey_footer(array());

} else {
	exit_no_group();
}

?>
