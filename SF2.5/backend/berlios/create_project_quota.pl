#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create project quota
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating Group Quota for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  $groupid += $config{'first_project_gid'};	
  if ($status eq "A") {
    print("/usr/sbin/edquota -p ".$config{'project_quota_temp'}." $groupname\n");
    system("/usr/sbin/edquota -p ".$config{'project_quota_temp'}." $groupname");
  }
}

print ("Done\n");
undef @group_dump;
