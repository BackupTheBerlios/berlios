#!/usr/bin/perl
#
# $Id: db_stats_prepare.pl,v 1.2 2003/11/13 11:01:42 helix Exp $
#
use DBI;

my $verbose = 1;

require("../include.pl");
$dbh = &db_connect;

print "\n\nPrepare stats creation...\n";

## 
## Drop the tmp table.
##      
$sql = "DROP TABLE stats_project_build_tmp";
$rel = $dbh->prepare($sql)->execute();
print "Dropped stats_project_build_tmp...\n" if $verbose;

##
## Create a temporary table to hold all of our stats
## for agregation at the end.
##      
$sql = "CREATE TABLE stats_project_build_tmp (
        group_id int NOT NULL,
        stat char(14) NOT NULL,
        value int NOT NULL DEFAULT '0'
        )";
$rel = $dbh->prepare($sql)->execute();
##
## Create tables indecies
##      
$sql = 'CREATE INDEX "idx_archive_build_group" on "stats_project_build_tmp" using btree ( "group_id" "int4_ops" )';
$rel = $dbh->prepare($sql)->execute();
$sql = 'CREATE INDEX "idx_archive_build_stat" on "stats_project_build_tmp" using btree ( "stat" "bpchar_ops" )';
$rel = $dbh->prepare($sql)->execute();
print "Created stats_project_build_tmp...\n" if $verbose;

print "Prepare stats creation done.\n";
