#!/usr/bin/perl
#
# $Id: db_dlstats_filetotal.pl,v 1.2 2003/11/13 11:01:42 helix Exp $
#
use DBI;

require("../include.pl");  # Include all the predefined functions

&db_connect;

# doing this for all days for now
my $query = "SELECT file_id, SUM(downloads_http + downloads_ftp) AS downloads "
	."FROM frs_dlstats_agg GROUP BY file_id";
my $rel = $dbh->prepare($query);
$rel->execute();

my $query = "DELETE FROM frs_dlstats_filetotal_agg";
my $reldel = $dbh->prepare($query);
$reldel->execute();

# for each day
while(my ($file_id,$downloads) = $rel->fetchrow()) {
	my $query = "INSERT INTO frs_dlstats_filetotal_agg (file_id,downloads) "
		."VALUES (".$file_id.",".$downloads.")";
	my $reldb = $dbh->prepare($query);
	$reldb->execute();
}
