#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create CVS tarballs for every Project/Group
# (/usr/local/httpd/htdocs/cvstarballs/<projectname>-cvsroot.tar.gz)
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating CVS tarballs for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist) = split(":", $ln);
	if ($status eq "A") {
		$tarcmd = "tar zcf ".$config{'cvs_tarballs'}."/$groupname-cvsroot.tar.gz -C ".$config{'cvsroot'}." $groupname";
		print("$tarcmd\n");
		system($tarcmd);
	} elsif ($status eq "D") {
		$file = $config{'cvs_tarballs'}."/$groupname-cvsroot.tar.gz";
		print("rm $file\n");
		system("rm $file");
	}
}

print ("Done\n");
undef @group_dump;
