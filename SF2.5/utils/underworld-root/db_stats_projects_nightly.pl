#!/usr/bin/perl
#
# $Id: db_stats_projects_nightly.pl,v 1.2 2003/11/13 11:01:42 helix Exp $
#
# use strict;
use DBI;
use Time::Local;
use POSIX qw( strftime );

require("../include.pl");
$dbh = &db_connect();

my ($sql, $rel);
my ($day_begin, $day_end, $mday, $year, $mon, $week, $day);
my $verbose = 1;

print "\n\nCreate stats of projects...\n";

##
## Set begin and end times (in epoch seconds) of day to be run
## Either specified on the command line, or auto-calculated
## to run yesterday's data.
##
if ( $ARGV[0] && $ARGV[1] && $ARGV[2] ) {

	$day_begin = timegm( 0, 0, 0, $ARGV[2], $ARGV[1] - 1, $ARGV[0] - 1900 );
	$day_end = timegm( 0, 0, 0, (gmtime( $day_begin + 86400 ))[3,4,5] );

} else {

	   ## Start at midnight last night.
	$day_end = timegm( 0, 0, 0, (gmtime( time() ))[3,4,5] );
	   ## go until midnight yesterday.
	$day_begin = timegm( 0, 0, 0, (gmtime( time() - 86400 ))[3,4,5] );

}
print "Day begin: $day_begin - Day end: $day_end\n" if $verbose;

   ## Preformat the important date strings.
$year	= strftime("%Y", gmtime( $day_begin ) );
$mon	= strftime("%m", gmtime( $day_begin ) );
$week	= strftime("%U", gmtime( $day_begin ) );    ## GNU ext.
$day	= strftime("%d", gmtime( $day_begin ) );
print "Running week $week, day $day month $mon year $year \n" if $verbose;



##
## Now we're going to pull in every column...
##

## group_ranking
$sql = "INSERT INTO stats_project_build_tmp 
	SELECT group_id,'group_ranking',ranking 
	FROM project_metric";
$rel = $dbh->prepare($sql)->execute();
print "Inserted group_ranking from project_metric...\n$sql\n" if $verbose;

## group_metric
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'group_metric',percentile 
	FROM project_metric";
$rel = $dbh->prepare($sql)->execute();
print "Inserted percentile from project_metric...\n$sql\n" if $verbose;

## developers
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'developers',COUNT(user_id) 
	FROM user_group 
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Inserted developers from user_group...\n$sql\n" if $verbose;

## file_releases
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'file_releases',COUNT(release_id) 
	FROM frs_release,frs_package
	WHERE ( frs_release.release_date > $day_begin AND frs_release.release_date < $day_end 
		AND frs_release.package_id = frs_package.package_id )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert file_releases from frs_release,frs_package...\n$sql\n" if $verbose;

## downloads
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'downloads',downloads
	FROM frs_dlstats_group_agg 
	WHERE ( day = '$year$mon$day' )";
##	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert downloads from frs_dlstats_group_agg...\n$sql\n" if $verbose;

## site_views 
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'site_views',count
	FROM stats_agg_logo_by_group
	WHERE ( day = '$year$mon$day' )";
##	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert site_views from activity_log...\n$sql\n" if $verbose;

if ( $ARGV[0] && $ARGV[1] && $ARGV[2] ) {
	## register_time
	$sql = "INSERT INTO stats_project_build_tmp
		SELECT group_id,'register_time',register_time 
		FROM groups
		GROUP BY group_id";
	$rel = $dbh->prepare($sql)->execute();
	print "Insert register_time from groups...\n$sql\n" if $verbose;

} else {
	## site_views
	$sql = "INSERT INTO stats_project_build_tmp
		SELECT group_id,'site_views',COUNT(group_id) 
		FROM activity_log_old
		WHERE ( day = '$year$mon$day' AND type = 0 )
		GROUP BY group_id";
	$rel = $dbh->prepare($sql)->execute();
	print "Insert site_views from activity_log...\n$sql\n" if $verbose;
}

print "Postponed: subdomain_views need to be inserted later from the project server logs...\n" if $verbose;

## msg_posted
$sql = "INSERT INTO stats_project_build_tmp
	SELECT forum_group_list.group_id,'msg_posted',COUNT(forum.msg_id)
	FROM forum_group_list, forum
	WHERE ( forum_group_list.group_forum_id = forum.group_forum_id 
		AND forum.date > $day_begin AND forum.date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert msg_posted from forum_group_list and forum...\n$sql\n" if $verbose;

## msg_uniq_auth
$sql = "INSERT INTO stats_project_build_tmp
 	SELECT forum_group_list.group_id,'msg_uniq_auth',COUNT( DISTINCT(forum.posted_by) )
 	FROM forum_group_list, forum
 	WHERE ( forum_group_list.group_forum_id = forum.group_forum_id 
		AND forum.date > $day_begin AND forum.date < $day_end )
 	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert msg_uniq_auth from forum_group_list and forum...\n$sql\n" if $verbose;

## bugs_opened
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'bugs_opened',COUNT(bug_id) 
	FROM bug
	WHERE ( date > $day_begin AND date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert bugs_opened from bug...\n$sql\n" if $verbose;

## bugs_closed
$sql = "INSERT INTO stats_project_build_tmp 
	SELECT group_id,'bugs_closed',COUNT(bug_id) 
	FROM bug
	WHERE ( close_date > $day_begin AND close_date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert bugs_closed from bug...\n$sql\n" if $verbose;

## support_opened
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'support_opened',COUNT(support_id) 
	FROM support
	WHERE ( open_date > $day_begin AND open_date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert support_opened from support...\n$sql\n" if $verbose;

## support_closed
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'support_closed',COUNT(support_id) 
	FROM support
	WHERE ( close_date > $day_begin AND close_date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert support_closed from support...\n$sql\n" if $verbose;

## patches_opened
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'patches_opened',COUNT(patch_id) 
	FROM patch
	WHERE ( open_date > $day_begin AND open_date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert patches_opened from patch...\n$sql\n" if $verbose;

## patches_closed
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'patches_closed',COUNT(patch_id) 
	FROM patch
	WHERE ( close_date > $day_begin AND close_date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert patches_closed from patch...\n$sql\n" if $verbose;

## tasks_opened
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_project_id as group_id,'tasks_opened',
		COUNT(project_task_id) 
	FROM project_task
	WHERE ( start_date > $day_begin AND start_date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert tasks_opened from project_task...\n$sql\n" if $verbose;

## tasks_closed
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_project_id as group_id,'tasks_closed',
		COUNT(project_task_id) 
	FROM project_task
	WHERE ( end_date > $day_begin AND end_date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert tasks_closed from project_task...\n$sql\n" if $verbose;

## help_requests
$sql = "INSERT INTO stats_project_build_tmp
	SELECT group_id,'help_requests',
		COUNT(job_id) 
	FROM people_job
	WHERE ( date > $day_begin AND date < $day_end )
	GROUP BY group_id";
$rel = $dbh->prepare($sql)->execute();
print "Insert help_requests from people_job...\n$sql\n" if $verbose;

##
## Create the daily tmp table for the update.
##
$sql="DROP TABLE stats_project_tmp";
$rel = $dbh->prepare($sql)->execute();
print "Dropping stats_project_tmp in preparation...\n$sql\n" if $verbose;

$sql = "CREATE TABLE stats_project_tmp (
        month integer DEFAULT '0' NOT NULL,
        week integer DEFAULT '0' NOT NULL,
        day integer DEFAULT '0' NOT NULL,
        group_id integer DEFAULT '0' NOT NULL,
        group_ranking integer DEFAULT '0' NOT NULL,
        group_metric double precision DEFAULT '0.00000' NOT NULL,
        developers integer DEFAULT '0' NOT NULL,
        file_releases integer DEFAULT '0' NOT NULL,
        downloads integer DEFAULT '0' NOT NULL,
        site_views integer DEFAULT '0' NOT NULL,
        subdomain_views integer DEFAULT '0' NOT NULL,
        msg_posted integer DEFAULT '0' NOT NULL,
        msg_uniq_auth integer DEFAULT '0' NOT NULL,
        bugs_opened integer DEFAULT '0' NOT NULL,
        bugs_closed integer DEFAULT '0' NOT NULL,
        support_opened integer DEFAULT '0' NOT NULL,
        support_closed integer DEFAULT '0' NOT NULL,
        patches_opened integer DEFAULT '0' NOT NULL,
        patches_closed integer DEFAULT '0' NOT NULL,
        tasks_opened integer DEFAULT '0' NOT NULL,
        tasks_closed integer DEFAULT '0' NOT NULL,
        help_requests integer DEFAULT '0' NOT NULL,
        cvs_checkouts integer DEFAULT '0' NOT NULL,
        cvs_commits integer DEFAULT '0' NOT NULL,
        cvs_adds integer DEFAULT '0' NOT NULL
)";

$rel = $dbh->prepare($sql)->execute();
print "Created stats_project_tmp for agregation...\n$sql\n" if $verbose;

$sql = "CREATE INDEX stats_project_tmp_group_id 
	on stats_project_tmp(group_id)";
$rel = $dbh->prepare($sql)->execute();
print "Added index to stats_project_tmp...\n$sql\n" if $verbose;


##
## Populate the stats_archive_project_tmp table the old
## fashioned way. (It's cleaner/faster than making the 3! tmp tables
## needed to merge the stats_project_build_tmp into the 
## stats_archive_project_tmp with MySQL.. if you can
## believe that.)
##

my (%stat_data, $group_id, $column, $value, @ar);

$sql = "SELECT DISTINCT group_id FROM stats_project_build_tmp";
$rel = $dbh->prepare($sql);
$rel->execute() or die "db_archive_stats_update.pl: Failed to run agregates.\n";

while ( @ar = $rel->fetchrow_array ) {
	$group_id = $ar[0];
	$stat_data{$group_id} = {};
	$stat_data{$group_id}{"month"} = "$year$mon";
	$stat_data{$group_id}{"week"} = $week;
	$stat_data{$group_id}{"day"} = $day;
}
print "Begining collation of " . $rel->rows . " project records..." if $verbose;
$rel->finish();


foreach $group_id ( keys %stat_data ) {
	
	$sql = "SELECT * FROM stats_project_build_tmp WHERE group_id=$group_id";
	$rel = $dbh->prepare($sql);
	$rel->execute();
	while ( ($column, $value) = ($rel->fetchrow_array)[1,2] ) {
		$stat_data{$group_id}{$column} = $value;
	}
	$rel->finish();

	if ( $stat_data{$group_id}{"register_time"} < $day_end ) {

		delete $stat_data{$group_id}{"register_time"};
##		$sql  = "INSERT INTO stats_project_tmp SET ";
		$sql  = "INSERT INTO stats_project_tmp ";
		$keys = "group_id,";
		$values = "$group_id,";
##		$sql .= "group_id=$group_id,";
##		$sql .= join( ",",  
##			map { "$_\=\'$stat_data{$group_id}{$_}\'" } (keys %{$stat_data{$group_id}}) 
##			);
		$keys .= join( ",", keys %{$stat_data{$group_id}}); 
		$values .= join( ",",
			 map { "\'$stat_data{$group_id}{$_}\'" } (keys %{$stat_data{$group_id}})
			 );
		$sql .= "( ".$keys." ) VALUES ( ".$values." )";
		$rel = $dbh->prepare($sql);
		$rel->execute();
print "$sql\n";
	}
}
print "Finished.\n" if $verbose;


##
## Drop the tmp table.
##
#$sql = "DROP TABLE stats_project_build_tmp";
#$rel = $dbh->prepare($sql)->execute();
#print "Dropped stats_project_build_tmp...\n$sql\n" if $verbose;


##
## Build the rest of the indexes on the temp table before we merge
## back into the live table. (to reduce locking time on live table)
##

$sql = "CREATE INDEX idx_project_stats_day 
	on stats_project_tmp(day)";
$rel = $dbh->prepare($sql)->execute();
print "Added further indexes to stats_project_tmp...\n$sql\n" if $verbose;

$sql = "CREATE INDEX idx_project_stats_week
	on stats_project_tmp(week)";
$rel = $dbh->prepare($sql)->execute();
print "Added further indexes to stats_project_tmp...\n$sql\n" if $verbose;

$sql = "CREATE INDEX idx_project_stats_month
	on stats_project_tmp(month)";
$rel = $dbh->prepare($sql)->execute();
print "Added further indexes to stats_project_tmp...\n$sql\n" if $verbose;

##
## Merge tmp table back into the live stat table
##
$sql = "DELETE FROM stats_project WHERE month='$year$mon' AND day='$day'";
$rel = $dbh->prepare($sql)->execute();
print "Cleared Old data from stats_project...\n$sql\n" if $verbose;

$sql = "INSERT INTO stats_project
	SELECT * FROM stats_project_tmp";
$rel = $dbh->prepare($sql)->execute();
print "Wrote back new data to stats_project...\n$sql\n" if $verbose;

print "Create stats of projects done.\n";

##
## EOF
##
