#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create CVS allow root config file based on infos
# available in /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @allow_root = ();
my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating CVS allow root config file for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  if ($status eq "A") {
	$rootent = $config{'cvsroot'}."/".$groupname."\n";
	push(@allow_root, $rootent);
	print("$groupname root dir: $rootent");
  }
}

write_array_file($config{'cvs_allow_root'}, @allow_root);
print ("Done\n");
undef @group_dump;
