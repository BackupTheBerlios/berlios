#!/usr/bin/perl 
#
# $Id: stats_agr_filerelease.pl,v 1.2 2003/11/13 11:01:42 helix Exp $
#
use DBI;
require("../include.pl");  # Include all the predefined functions

my $verbose = 1;

print "\n\nAgregate downloads for projects filereleases...\n";

   ## if params were passed, we don't need to be running the agregates.
if ( $ARGV[0] && $ARGV[1] && $ARGV[2] ) {
	print "Skipping the agregate build...\n" if $verbose;
	exit;
}


&db_connect;

##
## Begin by collecting universal data into RAM.
##
$sql	= "SELECT group_id FROM groups WHERE status='A'";
print("\n$sql\n");
$rel = $dbh->prepare($sql) || die "SQL parse error: $!";
$rel->execute() || die "SQL execute error: $!";
while ( @tmp_ar = $rel->fetchrow_array() ) {
	push( @groups, $tmp_ar[0] );
}

##
## CREATE THE frs_dlstats_grouptotal_agg TABLE.
##
$sql	= "DROP TABLE frs_dlstats_grouptotal_agg_tmp";
print("\n$sql\n");
$rel = $dbh->do($sql);
$sql	= "DROP INDEX idx_stats_agr_tmp_gid";
print("\n$sql\n");
$rel = $dbh->do($sql);

   ## Flush the downloads cache.
%downloads = {};

   ## create the temp table;
$sql	= "CREATE TABLE frs_dlstats_grouptotal_agg_tmp ( "
	. "group_id integer DEFAULT '0' NOT NULL,"
	. "downloads integer DEFAULT '0' NOT NULL"
	. ")";
print("\n$sql\n");
$rel = $dbh->do($sql) || die "SQL parse error: $!";

$sql	= "CREATE INDEX idx_stats_agr_tmp_gid "
        . "ON frs_dlstats_grouptotal_agg_tmp(group_id)";
print("\n$sql\n");
$rel = $dbh->do($sql) || die "SQL parse error: $!";

$sql	= "SELECT group_id,SUM(downloads) FROM stats_http_downloads GROUP BY group_id";
$rel = $dbh->prepare($sql) || die "SQL parse error: $!";
print("\n$sql\n");
$rel->execute() || die "SQL execute error: $!";
while ( @tmp_ar = $rel->fetchrow_array() ) {
	$downloads{ $tmp_ar[0] } += $tmp_ar[1];
}

####$sql	= "SELECT group_id,SUM(downloads) FROM stats_ftp_downloads GROUP BY group_id";
####$rel = $dbh->prepare($sql) || die "SQL parse error: $!";
####$rel->execute() || die "SQL execute error: $!";
####while ( @tmp_ar = $rel->fetchrow_array() ) {
####	$downloads{ $tmp_ar[0] } += $tmp_ar[1]; 
####}

foreach $group_id ( @groups ) {
	$xfers = $downloads{$group_id};

	$sql  = "INSERT INTO frs_dlstats_grouptotal_agg_tmp VALUES ('$group_id','$xfers')";
	print("\n$sql\n");
	$rel = $dbh->do($sql) || die "SQL parse error: $!";
}

   ## Drop the old agregate table
$sql="DROP TABLE frs_dlstats_grouptotal_agg";
print("\n$sql\n");
$rel = $dbh->do($sql);
   ## Relocate the new table to take it's place.
$sql="ALTER TABLE frs_dlstats_grouptotal_agg_tmp RENAME TO frs_dlstats_grouptotal_agg";
print("\n$sql\n");
$rel = $dbh->do($sql) || die "SQL parse error: $!";



##
## CREATE THE frs_dlstats_filetotal_agg TABLE.
##
$sql	= "DROP TABLE frs_dlstats_filetotal_agg_tmp";
print("\n$sql\n");
$rel = $dbh->do($sql);

$sql    = "DROP INDEX idx_stats_agr_tmp_fid";
print("\n$sql\n");
$rel = $dbh->do($sql);

   ## Flush the downloads cache.
%downloads = {};

   ## create the temp table;
$sql	= "CREATE TABLE frs_dlstats_filetotal_agg_tmp ( "
	. "file_id integer DEFAULT '0' NOT NULL,"
	. "downloads integer DEFAULT '0' NOT NULL"
	. ")";
print("\n$sql\n");
$rel = $dbh->do($sql) || die "SQL parse error: $!";

$sql	= "CREATE INDEX idx_stats_agr_tmp_fid "
	. "ON frs_dlstats_filetotal_agg_tmp (file_id)";
print("\n$sql\n");
$rel = $dbh->do($sql) || die "SQL parse error: $!";

$sql	= "SELECT filerelease_id,SUM(downloads) FROM stats_http_downloads GROUP BY filerelease_id";
print("\n$sql\n");
$rel = $dbh->prepare($sql) || die "SQL parse error: $!";
$rel->execute() || die "SQL execute error: $!";
while ( @tmp_ar = $rel->fetchrow_array() ) {
	$downloads{ $tmp_ar[0] } += $tmp_ar[1];
}

####$sql	= "SELECT filerelease_id,SUM(downloads) FROM stats_ftp_downloads GROUP BY filerelease_id";
####print("\n$sql\n");
####$rel = $dbh->prepare($sql) || die "SQL parse error: $!";
####$rel->execute() || die "SQL execute error: $!";
####while ( @tmp_ar = $rel->fetchrow_array() ) {
####	$downloads{ $tmp_ar[0] } += $tmp_ar[1]; 
####}

foreach $file_id ( keys %downloads ) {
	$xfers = $downloads{$file_id};
	$sql  = "INSERT INTO frs_dlstats_filetotal_agg_tmp VALUES ('$file_id','$xfers')";
	print("\n$sql\n");
####	$rel = $dbh->do($sql) || die "SQL parse error: $!";
	$rel = $dbh->do($sql);
}

   ## Drop the old agregate table
$sql="DROP TABLE frs_dlstats_filetotal_agg";
print("\n$sql\n");
$rel = $dbh->do($sql);
   ## Relocate the new table to take it's place.
$sql="ALTER TABLE frs_dlstats_filetotal_agg_tmp RENAME TO frs_dlstats_filetotal_agg";
print("\n$sql\n");
$rel = $dbh->do($sql) || die "SQL parse error: $!";

print "Agregate downloads for projects filereleases done.\n";

##
## EOF
##
