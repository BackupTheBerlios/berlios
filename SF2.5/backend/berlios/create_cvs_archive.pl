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
  ($groupname, $status, $groupid, $userlist, $use_cvs) = split(":", $ln);
  $dir = $config{'cvsroot'}."/".$groupname;
#  print ("Group: ".$groupname.":".$status.":".$groupid.":".$userlist.":".$use_cvs."\n");
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
    $chmod = "chmod 2775 ".$dir;
    print("$chmod\n");
    system($chmod);
    $chmod = "chmod 2775 ".$dir."/CVSROOT";
    print("$chmod\n");
    system($chmod);
    if (! $use_cvs) {
        $chmod = "chmod -R o-rwx ".$dir;
        print("$chmod\n");
        system($chmod);
    }
    $chmod = "chmod 666 ".$dir."/CVSROOT/history";
    print("$chmod\n");
    system($chmod);
    $chmod = "chmod 666 ".$dir."/CVSROOT/val-tags";
    print("$chmod\n");
    system($chmod);
    $chmod = "chmod 666 ".$dir."/CVSROOT/commitlog";
    print("$chmod\n");
    system($chmod);
  }
  closedir(DIR);
}

print ("Done\n");
undef @group_dump;
