#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create project quota on NFS server
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

  print("$groupname ($groupid)\n");
#  print("/usr/bin/quota -v $groupid > /tmp/nfsproject_quota.tst\n");
  system("/usr/bin/quota -v $groupid > /tmp/nfsproject_quota.tst");
  my @quota = open_array_file("/tmp/nfsproject_quota.tst");
  if ($status eq "A" && @quota < 3) {
    print("/usr/sbin/edquota -p ".$config{'project_quota_temp_id'}." $groupid ($groupname)\n");
    system("/usr/sbin/edquota -p ".$config{'project_quota_temp_id'}." $groupid");
  }
}

print ("Done\n");
undef @group_dump;
