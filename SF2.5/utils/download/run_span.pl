#!/usr/bin/perl 
use Time::Local;

##$script		= "stats_http_logparse.pl";
$span		= $ARGV[0];
$year		= $ARGV[1];
$month		= $ARGV[2];
$day		= $ARGV[3];
$script         = $ARGV[4];

$| = 0;
print "Processing $span day span from $month/$day/$year with $script...\n";

for ( $i = 1; $i <= $span; $i++ ) {

	$command = "perl $script $year $month $day";
	print STDERR "Running \'$command\' from the current directory...\n";
	print STDERR `$command`;

	($year,$month,$day) = (gmtime( timegm(0,0,0,$day + 1,$month - 1,$year - 1900) ))[5,4,3];
	$year += 1900;
	$month += 1;
}

