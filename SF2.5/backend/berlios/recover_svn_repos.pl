#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Recover SVN repository for every Project/Group
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nRecover SVN repository for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist) = split(":", $ln);
        if ($status eq "A") {
		$cmd = "svnadmin recover ".$config{'svnroot'}."/repos/$groupname";
		print("$cmd\n");
		system($cmd);
	}
}

print ("Done\n");
undef @group_dump;
