#!/usr/bin/perl
##
## cvs_history_days_parse.pl 
##
## NIGHTLY SCRIPT
##
## Recurses through the /cvsroot directory tree and parses each projects
## '~/CVSROOT/history' file, building agregate stats on the number of 
## checkouts, commits, and adds to each project over the past days.
##
##
## $Id 
##
use strict;
use Time::Local;
use POSIX qw( strftime );

my ($year, $month, $day, $day_begin, $day_end);
my $verbose = 1;

$|=0 if $verbose;

   ## Set the time to collect stats for
if ( $ARGV[0] ) {

          ## Start at midnight n days ago
        $day_begin = timegm( 0, 0, 0, (gmtime( time() - ($ARGV[0] * 86400) ))[3,4,5] );

} else {

           ## Start at midnight 30 days ago.
        $day_begin = timegm( 0, 0, 0, (gmtime( time() - (30 * 86400) ))[3,4,5] );
           ## go until midnight yesterday.
        $day_end = timegm( 0, 0, 0, (gmtime( time() ))[3,4,5] );

	$year	= strftime("%Y", gmtime( $day_begin ) );
	$month	= strftime("%m", gmtime( $day_begin ) );
	$day	= strftime("%d", gmtime( $day_begin ) );

}

print "Parsing cvs logs looking for traffic starting at $year-$month-$day.\n" if $verbose;

print "cvs_history_parse.pl $year $month $day\n" if $verbose;
system("cd /usr/local/httpd/SF2.5/utils/cvs1;./cvs_history_parse.pl $year $month $day");
print "Done processing cvs history file starting this date.\n" if $verbose;

##
## EOF
##
