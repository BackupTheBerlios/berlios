#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create SVN Archive at /svnroot/repos/<projectname>
# based on infos available in /home/dummy/dumps25/group_dump 
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating SVN Archives for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  $dir = $config{'svnroot'}."/repos/".$groupname;
  print ("\nGroup: ".$groupname.":".$status.":".$groupid.":".$userlist."\n");
  if (!opendir(DIR, $dir)) {
    if ($status eq "A") {
      $svncreate = "svnadmin create ".$dir;
      print("$status: $svncreate\n");
      system($svncreate);
    }
  }
  if ($status eq "D") {
    $remove = "rm -r ".$dir;
    print("$remove\n");
    system($remove);
  } elsif ($status eq "A") {
    $chown = "chown -R wwwrun:".$groupname." ".$dir;
    print("$chown\n");
    system($chown);
    $chmod = "chmod -R u+rw,g+rw ".$dir;
    print("$chmod\n");
    system($chmod);
    $chmod = "find $dir -type d | xargs chmod 2775";
    print("$chmod\n");
    system($chmod);
  }
  closedir(DIR);
}

print ("Done\n");
undef @group_dump;
