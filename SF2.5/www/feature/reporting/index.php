<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.1 2004/03/16 14:51:58 helix Exp $

require('pre.php');
require('../feature_utils.php');
require('../../project/stats/project_stats_utils.php');
require('tool_reports.php');

$page_title="Feature Reporting System";
//$bar_colors=array("cyan","magenta");
$bar_colors=array("#F76DF7","#54BFBF");

function feature_reporting_header($group_id) {
        reports_header($group_id,
        	array('aging','tech','category'),
                array('Aging Report','Feature Requests by Technician','Feature Requests by Category')
	);
}

function feature_quick_report($group_id,$title,$subtitle1,$sql1,$subtitle2,$sql2) {
        global $bar_colors;

       	feature_header(array ("title"=>$title));
       	feature_reporting_header($group_id);
       	echo "\n<H2>$title</H2>";

        reports_quick_graph($subtitle1,$sql1,$sql2,$bar_colors);

	feature_footer(array());
}


if ($group_id && user_ismember($group_id/*,"S2"*/)) {

	include ($DOCUMENT_ROOT.'/include/HTML_Graphs.php');

	if ($what) {
		/*
			Update the database
		*/

                $period_clause=period2sql($period,$span,"open_date");

		if ($what=="aging") {

			feature_header(array ("title"=>"Aging Report"));
			feature_reporting_header($group_id);
			echo "\n<H2>Aging Report</H2>";

			$time_now=time();
//			echo $time_now."<P>";

		        if (!$period || $period=="lifespan") {
		        	$period="month";
                                $span=12;
		        }

			if (!$span) $span=1;
                        $sub_duration=period2seconds($period,1);
//                        echo $sub_duration,"<br>";

			for ($counter=1; $counter<=$span; $counter++) {

				$start=($time_now-($counter*$sub_duration));
				$end=($time_now-(($counter-1)*$sub_duration));

				$sql="SELECT avg((close_date-open_date)/(24*60*60)) ".
                                     "FROM feature ".
                                     "WHERE close_date > 0 ".
                                     "AND (open_date >= $start AND open_date <= $end) ".
                                     "AND group_id='$group_id'";

				$result = db_query($sql);

				$names[$counter-1]=date("Y-m-d",($start))." to ".date("Y-m-d",($end));
				$values[$counter-1]=((int)(db_result($result, 0,0)*1000))/1000;
			}

			GraphIt($names, $values,
                        "Average Turnaround Time For Closed Feature Requests (days)");

			echo "<P>";

			for ($counter=1; $counter<=$span; $counter++) {

				$start=($time_now-($counter*$sub_duration));
				$end=($time_now-(($counter-1)*$sub_duration));

				$sql="SELECT count(*) ".
                                     "FROM feature ".
                                     "WHERE open_date >= $start ".
                                     "AND open_date <= $end ".
                                     "AND group_id='$group_id'";

				$result = db_query($sql);

				$names[$counter-1]=date("Y-m-d",($start))." to ".date("Y-m-d",($end));
				$values[$counter-1]=db_result($result, 0,0);
			}

			GraphIt($names, $values, "Number of Feature Requests Submitted");

			echo "<P>";

			for ($counter=1; $counter<=$span; $counter++) {

				$start=($time_now-($counter*$sub_duration));
				$end=($time_now-(($counter-1)*$sub_duration));

				$sql="SELECT count(*) ".
                                     "FROM feature ".
                                     "WHERE open_date <= $end ".
                                     "AND (close_date >= $end OR close_date < 1 OR close_date is null) ".
                                     "AND group_id='$group_id'";

				$result = db_query($sql);

				$names[$counter-1]=date("Y-m-d",($end));
				$values[$counter-1]=db_result($result, 0,0);
			}

			GraphIt($names, $values, "Number of Feature Requests Still Open");

			echo "<P>";

			feature_footer(array());

		} else if ($what=="category") {

			$sql1="SELECT feature_category.category_name AS Category, count(*) AS Count ".
                              "FROM feature_category,feature ".
			      "WHERE feature_category.feature_category_id=feature.feature_category_id ".
                              "AND feature.feature_status_id = '1' ".
                              "AND feature.group_id='$group_id' ".
                              $period_clause .
			      "GROUP BY Category";
			$sql2="SELECT feature_category.category_name AS Category, count(*) AS Count ".
                              "FROM feature_category,feature ".
			      "WHERE feature_category.feature_category_id=feature.feature_category_id ".
                              "AND feature.feature_status_id <> '3' ".
                              "AND feature.group_id='$group_id' ".
                              $period_clause .
		 	      "GROUP BY Category";

                	feature_quick_report($group_id,
                        		  "Feature Requests By Category",
                        		  "Open Feature Requests By Category",$sql1,
                        		  "All Feature Requests By Category",$sql2);

		} else if ($what=="tech") {

			$sql1="SELECT users.user_name AS Technician, count(*) AS Count ".
                              "FROM users,feature ".
			      "WHERE users.user_id=feature.assigned_to ".
                              "AND feature.feature_status_id = '1' ".
                              "AND feature.group_id='$group_id' ".
                              $period_clause .
			      "GROUP BY Technician";

			$sql2="SELECT users.user_name AS Technician, count(*) AS Count ".
                              "FROM users,feature ".
			      "WHERE users.user_id=feature.assigned_to ".
                              "AND feature.feature_status_id <> '3' ".
                              "AND feature.group_id='$group_id' ".
                              $period_clause .
			      "GROUP BY Technician";

                	feature_quick_report($group_id,
                        		  "Feature Requests By Technician",
                        		  "Open Feature Requests By Technician",$sql1,
                        		  "All Feature Requests By Technician",$sql2);

		} else {
                	exit_missing_param();
                }

	} else {
		/*
			Show main page
		*/
		feature_header(array ("title"=>$page_title));

		echo "\n<H2>$page_title</H2>";
		feature_reporting_header($group_id);

		feature_footer(array());

	}

} else {

	// Cannot show reports

	if (!$group_id) {
		exit_no_group();
	} else {
		exit_permission_denied();
	}

}
?>
