#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create CVS Archive at /cvsroot/<projectname>
# based on infos available in /home/dummy/dumps25/group_dump 
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating CVS Archives for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  $dir = $config{'cvsroot'}."/".$groupname;
#  print ("Group: ".$groupname.":".$status.":".$groupid.":".$userlist."\n");
  if (!opendir(DIR, $dir)) {
    if ($status eq "A") {
      $cvsinit = "cvs -d ".$dir." init";
      print("$status: $cvsinit\n");
      system($cvsinit);
    }
  }
  if ($status eq "D") {
    $remove = "rm -r ".$dir;
    print("$remove\n");
	system($remove);
  } elsif ($status eq "A") {
    $chown = "chown -R ".$groupname.":".$groupname." ".$dir;
    print("$chown\n");
    system($chown);
    $chmod = "chmod g+s ".$dir;
    print("$chmod\n");
    system($chmod);
    $chmod = "chmod g+s ".$dir."/CVSROOT";
    print("$chmod\n");
    system($chmod);
    $chmod = "chmod a+w ".$dir."/CVSROOT/history";
    print("$chmod\n");
    system($chmod);
    $chmod = "chmod a+w ".$dir."/CVSROOT/val-tags";
    print("$chmod\n");
    system($chmod);
    $chmod = "chmod a+w ".$dir."/CVSROOT/commitlog";
    print("$chmod\n");
    system($chmod);
  }
  closedir(DIR);
}

print ("Done\n");
undef @group_dump;
