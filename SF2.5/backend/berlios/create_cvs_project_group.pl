#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create new CVS project groups in /etc/group (/home/dummy/dumps25/group)
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group = open_array_file($config{'sysgroup'});
my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

write_array_file($config{'sysgroup'}.".cvsproject.old", @group);
print("\nCreating CVS Groups for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist) = split(":", $ln);
	$groupid += $config{'first_cvsproject_gid'};
	$grpent = "cvs" . $groupname . "::" . $groupid . ":" . "cvs" . $groupname . ",".$config{'wwwuid'}."\n";
	@group = grep(!/^cvs$groupname:/, @group);
	push(@group, $grpent);
	print("$grpent");
}

write_array_file($config{'sysgroup'}, @group);
print ("Done\n");
undef @group;
undef @group_dump;
