#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create new CVS user account in /etc/passwd
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating CVS Accounts for Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist) = split(":", $ln);
	$groupid += $config{'first_cvsproject_gid'};
	$pwdent = "/usr/sbin/useradd -c \"CVS Account for Project ".$groupname." at BerliOS\" -d ".$config{'cvsroot'}."/".$groupname." -g cvs".$groupname." -s ".$config{'noshell'}." -u ".$groupid." cvs".$groupname;
	print("$pwdent\n");
	system($pwdent);
}

print ("Done\n");
undef @group_dump;
