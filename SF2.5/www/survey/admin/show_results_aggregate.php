<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: show_results_aggregate.php,v 1.4 2003/11/27 15:05:42 helix Exp $

require('pre.php');
require('HTML_Graphs.php');
require('../survey_utils.php');
$is_admin_page='y';

if ($group_id && $survey_id) {

if (!user_isloggedin() || !user_ismember($group_id,'A')) {
	exit_permission_denied();
	exit;
}

survey_header(array('title'=>'Survey Aggregate Results'));

//$result=db_query($sql);

/*
	Select this survey from the database
*/

$sql="SELECT * FROM surveys WHERE survey_id='$survey_id' AND group_id='$group_id'";
$result=db_query($sql);

echo "<H2>".db_result($result, 0, "survey_title")."</H2><P>";

/*
echo "<H3><A HREF=\"show_results_csv.php?survey_id=$survey_id&group_id=$group_id\">.CSV File</A></H3><P>";
*/

/*
	Select the questions for this survey
*/

$questions=db_result($result, 0, "survey_questions");
$quest_array=explode(',', $questions);
$count=count($quest_array);

echo "\n\n<TABLE>";

$q_num=1;

for ($i=0; $i<$count; $i++) {

	/*
		Build the questions on the HTML form
	*/

	$sql="SELECT question_type,question,question_id FROM survey_questions WHERE question_id=$quest_array[$i] AND group_id=$group_id";

	$result=db_query($sql);

	$question_type=db_result($result, 0, "question_type");

	if ($question_type == "4") {
		/*
			Don't show question number if it's just a comment
		*/

		echo "\n<TR><TD VALIGN=TOP>&nbsp;</TD>\n<TD>"; 

	} else {

		echo "\n<TR><TD VALIGN=TOP><B>";

		/*
			If it's a 1-5 question box and first in series, move Quest
			number down a bit
		*/

		if (($question_type != $last_question_type) && (($question_type == "1") || ($question_type == "3"))) {
			echo "&nbsp;<br>";
		}

		echo $q_num."&nbsp;&nbsp;&nbsp;&nbsp;<BR></TD>\n<TD>";
		$q_num++;

	}
	
	if ($question_type == "1") {

		/*
			This is a r�dio-button question. Values 1-5.	
		*/

		# Show the 1-5 markers only if this is the first in a series

		if ($question_type != $last_question_type) {
			echo "\n<B>1 &nbsp; 2 &nbsp; 3 &nbsp; 4 &nbsp; 5</B>\n";
			echo "<BR>";

		}

		/*
			Select the number of responses to this question
		*/
		$sql="SELECT count(*) AS count FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$quest_array[$i]' AND group_id='$group_id' AND response IN ('1','2','3','4','5')";

		$result2=db_query($sql);
		if (!$result2 || db_numrows($result2) < 1) {
			echo "Database ";
			echo db_error();
		} else {
			echo "<B>".db_result($result2, 0, 'count')."</B> Responses<BR>";
		}
		/*
			average
		*/
/* 2003-11-26 helix
		$sql="SELECT avg(response) AS avg FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$quest_array[$i]' AND group_id='$group_id'";

		$result2=db_query($sql);
		if (!$result2 || db_numrows($result2) < 1) {
			echo "Database ";
			echo db_error();
		} else {
			echo "<B>".db_result($result2, 0, 'avg')."</B> Average";
		}
*/

		$sql="SELECT response,count(*) AS count FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$quest_array[$i]' AND group_id='$group_id' AND response IN ('1','2','3','4','5') GROUP BY response";

		$result2=db_query($sql);
		if (!$result2 || db_numrows($result2) < 1) {
			echo "Database ";
			echo db_error();
		} else {
			GraphResult($result2,stripslashes(db_result($result, 0, "question")));
		}

	} else if ($question_type == "2") {
		/*
			This is a text-area question.
		*/

		echo db_result($result, 0, "question")."<BR>\n";

		echo "<A HREF=\"show_results_comments.php?survey_id=$survey_id&question_id=$quest_array[$i]&group_id=$group_id\">View Comments</A>";

	} else if ($question_type == "3") {
		/*
			This is a Yes/No question.
		*/

		/*
			Show the Yes/No only if this is the first in a series
		*/

		if ($question_type != $last_question_type) {
			echo "<B>Yes / No</B><BR>\n";
		}

		/*
			Select the count and average of responses to this question
		*/
		$sql="SELECT count(*) AS count FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$quest_array[$i]' AND group_id='$group_id' AND response IN ('1','2','3','4','5')";

		$result2=db_query($sql);
		if (!$result2 || db_numrows($result2) < 1) {
			echo "Database ";
			echo db_error();
		} else {
			echo "<B>".db_result($result2, 0, 0)."</B> Responses<BR>";
		}
		/*
			average
		*/
/* 2003-11-26 helix
		$sql="SELECT avg(response) AS avg FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$quest_array[$i]' AND group_id='$group_id'";

		$result2=db_query($sql);
		if (!$result2 || db_numrows($result2) < 1) {
			echo "Database ";
                        echo db_error();
		} else {
			echo "<B>".db_result($result2, 0, 0)."</B> Average";
		}
*/

		/*
			Get the YES responses
		*/
		$sql="SELECT count(*) AS count FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$quest_array[$i]' AND group_id='$group_id' AND response='1'";

		$result2=db_query($sql);

		$name_array[0]="Yes";

		if (!$result2 || db_numrows($result2) < 1) {
			$value_array[0]=0;
		} else {
			$value_array[0]=db_result($result2, 0, "count");
		}

		/*
			Get the NO responses
		*/
		$sql="SELECT count(*) AS count FROM survey_responses WHERE survey_id='$survey_id' AND question_id='$quest_array[$i]' AND group_id='$group_id' AND response='5'";

		$result2=db_query($sql);

		$name_array[1]="No";

		if (!$result2 || db_numrows($result2) < 1) {
			$value_array[1]=0;
		} else {
			$value_array[1]=db_result($result2, 0, "count");
		}

		GraphIt($name_array,$value_array,stripslashes(db_result($result, 0, "question")));

	} else if ($question_type == "4") {

		/*
			This is a comment only.
		*/

		echo "&nbsp;<P><B>".db_result($result, 0, "question")."</B>\n";
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"_".$quest_array[$i]."\" VALUE=\"-666\">";

	} else if ($question_type == "5") {

		/*
			This is a text-field question.
		*/

		echo db_result($result, 0, "question")."<BR>\n";

		echo "<A HREF=\"show_results_comments.php?survey_id=$survey_id&question_id=$quest_array[$i]&group_id=$group_id\">View Comments</A>";

	}

	echo "</TD></TR>";

	$last_question_type=$question_type;

}

echo "\n\n</TABLE>";

survey_footer(array());

} else {
	exit_no_group();
}

?>
