#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2002 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create new project groups authentication db in /etc/httpd/passwd/passwd_authdb
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my $passwd_authdb = "/etc/httpd/passwd/passwd_authdb";
my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating Authentication Group DB for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  $groupid += $config{'first_project_gid'};
  if ($status eq "A") {
	$grpent = "/usr/bin/dbmmanage update ". $auth_groupdb_file ." ". $groupname . " " . $userlist . "\n";
	@group = grep(!/^$groupname:/, @group);
	print("$grpent");
	system("$grpent");
  }
}

print ("Done\n");
undef @group_dump;
