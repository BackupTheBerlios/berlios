#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000-2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create group/project home directory (/home/groups/<projectname>)
# based on infos available in /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating Home Directories for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  $homdir = $config{'project_home'}."/".$groupname;
  if ($status eq "A") {
	if ( ! -d $homdir ) {
		print("mkdir -m 0555 -p $homdir\n");
		system("mkdir -m 0555 -p $homdir");
	}
	print("chown -h $groupname:$groupname $homdir\n");
	system("chown -h $groupname:$groupname $homdir");
  } elsif ($status eq "D") {
	if ( -d $homdir ) {
		print("rm -rf $homdir\n");
		system("rm -rf $homdir");
	}
  }
}

print ("Done\n");
undef @group_dump;
