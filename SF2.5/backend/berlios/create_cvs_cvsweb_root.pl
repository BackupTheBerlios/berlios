#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create CVSWEB root config file /etc/httpd/cvswebroot.conf based on infos
# available in /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @cvsroot = ("\%CVSROOT = (\n");
my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist $use_cvs);

print("\nCreating CVSWEB root config file for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist, $use_cvs) = split(":", $ln);
  if ($status eq "A") {
    if ($use_cvs) {
      $rootent = "\"".$groupname."\" => \"".$config{'cvsroot'}."/".$groupname."\",\n";
      push(@cvsroot, $rootent);
      print($rootent);
    }
  }
}
push(@cvsroot, ");\n");
write_array_file($config{'cvsweb_root'}, @cvsroot);
print ("Done\n");
undef @group_dump;
