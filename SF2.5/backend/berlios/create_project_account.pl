#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create new project account in /etc/passwd
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file("/home/dummy/dumps25/group_dump");
my ($groupname, $status, $groupid, $userlist);

print("\nCreating Accounts for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  $groupid += $config{'first_project_gid'};
  if ($status eq "A") {
	$pwdent = "/usr/sbin/useradd -c \"Project ".$groupname." at BerliOS\" -d ".$config{'project_home'}."/".$groupname." -g ".$groupname." -s ".$config{'noshell'}." -u ".$groupid." ".$groupname;
  } elsif ($status eq "D") {
	$pwdent = "/usr/sbin/userdel ".$groupname;
  }
	print("$pwdent\n");
	system($pwdent);
}

print ("Done\n");
undef @group_dump;
