#!/usr/bin/perl 
use Time::Local;

##$script		= "stats_http_logparse.pl";
$year		= $ARGV[0];
$month		= $ARGV[1];
$day		= $ARGV[2];
$eyear          = $ARGV[3];
$emonth         = $ARGV[4];
$eday           = $ARGV[5];
$script         = $ARGV[6];

$| = 0;
print "Processing from $month/$day/$year until $emonth/$eday/$eyear with $script...\n";

while ( $year ne $eyear || $month ne $emonth || $day ne $eday ) {
	$command = "perl $script $year $month $day";
	print STDERR "Running \'$command\' from the current directory...\n";
	print STDERR `$command`;

	($year,$month,$day) = (gmtime( timegm(0,0,0,$day + 1,$month - 1,$year - 1900) ))[5,4,3];
	$year += 1900;
	$month += 1;
}

