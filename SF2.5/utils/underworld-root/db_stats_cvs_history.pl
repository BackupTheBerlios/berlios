#!/usr/bin/perl
##
## db_cvs_history.pl
##
## NIGHTLY SCRIPT
##
## Pulls the parsed CVS datafile (generated by cvs_history_parse.pl ) from the
## cvs server, and parses it into the database
##
## Written by Matthew Snelham <matthew@valinux.com>
##
#use strict; ## annoying include requirements
use DBI;
use Time::Local;
use POSIX qw( strftime );
require("../include.pl");  # Include all the predefined functions
$dbh = &db_connect;

my ($logfile, $sql, $res, $temp, %groups, $group_id, $errors );
my $verbose = 1;

print "\n\nCreate CVS stats for projects...\n";

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

   ## Preformat the important date strings.
$year   = strftime("%Y", gmtime( $day_begin ) );
$mon    = strftime("%m", gmtime( $day_begin ) );
$week   = strftime("%U", gmtime( $day_begin ) );    ## GNU ext.
$day    = strftime("%d", gmtime( $day_begin ) );
print "Running week $week, day $day month $mon year $year \n";


   ## We'll pull down the parsed CVS log from the CVS server via http?!
print "Pulling down preprocessed logfile from cvs...\n" if $verbose;
$logfile = "/tmp/cvs_history.txt";
unlink("$logfile");
`wget -q -O $logfile http://cvs.berlios.de/cvslogs/$year/$mon/cvs_traffic_$year$mon$day.log`;
print `ls -la $logfile`;

   ## Now, we will pull all of the project ID's and names into a *massive*
   ## hash, because it will save us some real time in the log processing.
print "Caching group information from groups table.\n" if $verbose;
$sql = "SELECT group_id,unix_group_name FROM groups";
$res = $dbh->prepare($sql);
$res->execute();
while ( $temp = $res->fetchrow_arrayref() ) {
	$groups{${$temp}[1]} = ${$temp}[0];
}
##
##	wrap this process in a transaction
##
####$dbh->do( "BEGIN WORK;" );

$dbh->{AutoCommit} = 0;

   ## begin parsing the log file line by line.
print "Parsing the information into the database..." if $verbose;
open( LOGFILE, $logfile ) or die "Cannot open $logfile";
while(<LOGFILE>) {

	if ( $_ =~ /^G::/ ) {
		chomp($_);

		   ## (G|U|E)::proj_name::user_name::checkouts::commits::adds
		my ($type, $group, $user, $checkouts, $commits, $adds) = split( /::/, $_, 6 );

		$group_id = $groups{$group};

		if ( $group_id == 0 ) {
			print STDERR "$_";
			print STDERR "db_cvs_history.pl: bad unix_group_name \'$name\' \n";
		}
			
		$sql = "INSERT INTO stats_project_build_tmp
			(group_id,stat,value)
			VALUES ('" . $group_id . "',"
			. "'cvs_checkouts','" . $checkouts . "')";
		$dbh->do( $sql );
		$sql = "INSERT INTO stats_project_build_tmp
			(group_id,stat,value)
			VALUES ('" . $group_id . "',"
			. "'cvs_commits','" . $commits . "')";
		$dbh->do( $sql );

		$sql = "INSERT INTO stats_project_build_tmp
			(group_id,stat,value)
			VALUES ('" . $group_id . "',"
			. "'cvs_adds','" . $adds . "')";
		$dbh->do( $sql );

	} elsif ( $_ =~ /^E::/ ) {
		$errors++;
	}

}
close( LOGFILE );
##
##      wrap this process in a transaction
##
####$dbh->do( "COMMIT WORK;" );
$dbh->commit();

print "Create CVS stats for projects done.\n";

##
## EOF
##