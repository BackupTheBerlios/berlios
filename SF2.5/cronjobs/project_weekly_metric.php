<?php

require ('squal_pre.php');

/*
if (!strstr($REMOTE_ADDR,$sys_internal_network)) {
        exit_permission_denied();
}
*/

$last_week= ( time() - (86400 * 7) );  
$this_week = (time());

$last_day=date('Ymd',$last_week);

print "<br>last_week: $last_week $last_day ";
print "<br><br>this_week: $this_week $this_day";



$sql="DROP TABLE project_counts_weekly_tmp";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



$sql="DROP TABLE project_metric_weekly_tmp";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



$sql="DROP SEQUENCE pmw_pk_seq";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



$sql="CREATE SEQUENCE pmw_pk_seq";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



$sql="DROP TABLE project_metric_weekly_tmp1";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



$sql="DROP SEQUENCE pmw_tmp1_pk_seq";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



$sql="CREATE SEQUENCE pmw_tmp1_pk_seq";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# create a table to put the aggregates in
#
$sql="CREATE TABLE project_counts_weekly_tmp (group_id integer,type text,count double precision)";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# forum messages
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT forum_group_list.group_id,'forum',log(3.0*count(forum.msg_id)) AS count 
FROM forum,forum_group_list 
WHERE forum.group_forum_id=forum_group_list.group_forum_id 
AND date > '$last_week' 
GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# project manager tasks
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT project_group_list.group_id,'tasks',log(4.0*count(project_task.project_task_id)) AS count 
FROM project_task,project_group_list 
WHERE project_task.group_project_id=project_group_list.group_project_id 
AND end_date > '$last_week'
AND end_date < '$this_week' 
GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# bugs
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT group_id,'bugs',log(3.0*count(*)) AS count 
FROM bug 
WHERE date > '$last_week'
GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# patches
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT group_id,'patches',log(10.0*count(*)) AS count 
FROM patch 
WHERE open_date > '$last_week' 
GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();


# support
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT group_id,'support',log(5.0*count(*)) AS count 
FROM support 
WHERE open_date > '$last_week'
GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# cvs commits
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT group_id,'cvs',log(sum(cvs_commits_wk)) AS count 
FROM group_cvs_history 
GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# developers
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT group_id,'developers',log((5.0*count(*))) AS count FROM user_group GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# file releases
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT frs_package.group_id,'filereleases',log( 5.0 * COUNT(frs_release.release_id) ) 
FROM frs_release,frs_package
WHERE ( frs_package.package_id = frs_release.package_id AND frs_release.release_date > '$last_week' )
GROUP BY frs_package.group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# file downloads
#
$sql="INSERT INTO project_counts_weekly_tmp 
SELECT group_id,'downloads',log( 0.3 * CAST(SUM(downloads) AS double precision) ) 
FROM frs_dlstats_group_agg
WHERE ( day >= '$last_day' )
GROUP BY group_id";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# create a new table to insert the final records into
#
$sql="CREATE TABLE project_metric_weekly_tmp1 (ranking integer DEFAULT nextval('pmw_tmp1_pk_seq'::text) NOT NULL,
group_id integer DEFAULT '0' NOT NULL,
value double precision,
PRIMARY KEY (ranking))";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



/*
# insert the rows into the table in order, adding a sequential rank #
#
$sql="INSERT INTO project_metric_weekly_tmp1 (group_id,value) 
SELECT project_counts_weekly_tmp.group_id,(survey_rating_aggregate.response * sum(project_counts_weekly_tmp.count)) AS value 
FROM project_counts_weekly_tmp,survey_rating_aggregate 
WHERE survey_rating_aggregate.id=project_counts_weekly_tmp.group_id 
AND survey_rating_aggregate.type=1 
AND survey_rating_aggregate.response > 0
AND project_counts_weekly_tmp.count > 0
GROUP BY group_id,response ORDER BY value DESC";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();
*/



# insert the rows into the table in order, adding a sequential rank #
#
$sql="INSERT INTO project_metric_weekly_tmp1 (group_id,value) 
SELECT project_counts_weekly_tmp.group_id,sum(project_counts_weekly_tmp.count) AS value 
FROM project_counts_weekly_tmp 
WHERE project_counts_weekly_tmp.count > 0
GROUP BY group_id ORDER BY value DESC";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# numrows in the set
#
$sql="SELECT count(*) FROM project_metric_weekly_tmp1";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();

$counts = db_result($rel,0,0);
print "\n<br>\n<br>\nCounts: ".$counts;



# drop the old metrics table
#
$sql="DROP TABLE project_weekly_metric";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# create a new table to insert the final records into
#
$sql="CREATE TABLE project_metric_weekly_tmp (ranking integer DEFAULT nextval('pmw_pk_seq'::text) NOT NULL,
	percentile double precision,
	group_id integer DEFAULT '0' NOT NULL,
        PRIMARY KEY (ranking))";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



$sql="INSERT INTO project_metric_weekly_tmp (ranking,percentile,group_id)
SELECT ranking,round((100.0-(100.0*((ranking-1.0)/$counts.0))),3),group_id 
FROM project_metric_weekly_tmp1 ORDER BY ranking ASC";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# create an index
#
$sql="CREATE INDEX idx_project_metric_weekly_group ON project_metric_weekly_tmp(group_id)";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();



# move the new ratings to the correct table name
#
$sql="ALTER TABLE project_metric_weekly_tmp RENAME TO project_weekly_metric";
print "\n<br>\n<br>\n".$sql;
$rel = db_query($sql);
if (!$rel) echo "<br>".db_error();
?>
