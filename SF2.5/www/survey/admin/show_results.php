<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: show_results.php,v 1.3 2003/11/27 15:05:42 helix Exp $

require('pre.php');
require('../survey_utils.php');
$is_admin_page='y';

if ($group_id) {

if (!user_isloggedin() || !user_ismember($group_id,'A')) {
	exit_permission_denied();
	exit;
}

survey_header(array('title'=>'Survey Results'));

echo "<H2>Survey Results</H2>";


Function  ShowResultsSurvey($result) {
	global $group_id;
	$rows  =  db_numrows($result);
	$cols  =  db_numfields($result);
	echo "<h3>$rows Found</h3>";

        $title_arr=array();
        $title_arr[]='Survey ID';
        $title_arr[]='Survey Title';

        echo html_build_list_table_top ($title_arr);

	for($j=0; $j<$rows; $j++)  {

                echo "<tr BGCOLOR=\"". html_get_alt_row_color($j) ."\">\n";

		echo "<TD><A HREF=\"show_results_individual.php?group_id=$group_id&survey_id=".db_result($result,$j,"survey_id")."\">".sprintf("%06d",db_result($result,$j,"survey_id"))."</A></TD>\n";

		for($i  =  1;  $i  <  $cols;  $i++)  {
			printf("<TD>%s</TD>\n",db_result($result,$j,$i));
		}

		echo "</tr>";
	}
	echo "</table>"; //</TD></TR></TABLE>";
}


Function  ShowResultsAggregate($result) {
	global $group_id;
	$rows  =  db_numrows($result);
	$cols  =  db_numfields($result);
	echo "<h3>$rows Found</h3>";

        $title_arr=array();
        $title_arr[]='Survey ID';
        $title_arr[]='Survey Title';

        echo html_build_list_table_top ($title_arr);

	for($j=0; $j<$rows; $j++)  {

		echo "<tr BGCOLOR=\"". html_get_alt_row_color($j) ."\">\n";

		echo "<TD><A HREF=\"show_results_aggregate.php?group_id=$group_id&survey_id=".db_result($result,$j,"survey_id")."\">".sprintf("%06d",db_result($result,$j,"survey_id"))."</A></TD>\n";

		for($i=1; $i<$cols; $i++) {
			printf("<TD>%s</TD>\n",db_result($result,$j,$i));
		}

		echo "</tr>";
	}
	echo "</table>"; //</TD></TR></TABLE>";
}


Function  ShowResultsCustomer($result) {
	global $survey_id,$group_id;

	$rows  =  db_numrows($result);
	$cols  =  db_numfields($result);
	echo "<h3>$rows Found</h3>";

        $title_arr=array();
        $title_arr[]='Survey ID';
        $title_arr[]='Survey Title';

        echo html_build_list_table_top ($title_arr);

	for($j=0; $j<$rows;$j++)  {

                echo "<tr BGCOLOR=\"". html_get_alt_row_color($j) ."\">\n";

		echo "<TD><A HREF=\"show_results_individual.php?group_id=$group_id&survey_id=$survey_id&customer_id=".db_result($result,$j,"cust_id")."\">".sprintf("%06d",db_result($result,$j,"cust_id"))."</A></TD>\n";

		for($i=1; $i<$cols; $i++)  {
			printf("<TD>%s</TD>\n",db_result($result,$j,$i));
		}

		echo "</tr>";
	}
	echo "</table>"; //</TD></TR></TABLE>";
}



if (!$survey_id) {

	/*
		Select a list of surveys, so they can click in and view a particular set of responses
	*/

	$sql="SELECT survey_id,survey_title FROM surveys WHERE group_id='$group_id'";

	$result=db_query($sql);

	echo "\n<h3>View Individual Responses</h3>\n\n";
	ShowResultsSurvey($result);

	echo "\n<h3>View Aggregate Responses</h3>\n\n";
	ShowResultsAggregate($result);

} /* else {

	/ *
		Pull up a list of customer IDs for people that responded to this survey
	* /

	$sql="select people.cust_id, people.first_name, people.last_name ".
		"FROM people,responses where responses.customer_id=people.cust_id AND responses.survey_id='$survey_id' ".
		"GROUP BY people.cust_id, people.first_name, people.last_name";

	$result=db_query($sql);

	ShowResultsCustomer($result);

} */

survey_footer(array());

} else {
	exit_no_group();
}
?>
