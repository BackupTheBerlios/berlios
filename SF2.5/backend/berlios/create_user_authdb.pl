#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2002 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create new user authentication db in /etc/httpd/passwd/passwd_authdb
# based on infos available from /home/dummy/dumps25/user_dump
#

require("include.pl");  # Include all the predefined functions and variables

my $passwd_authdb = "/etc/httpd/passwd/passwd_authdb";
my @user_dump = open_array_file("/home/dummy/dumps25/user_dump");
my @group_dump = open_array_file("/home/dummy/dumps25/group_dump");
my ($uid, $statusu, $username, $shell, $pwd, $realname);
my ($groupname, $statusg, $groupid, $userlist);

print("\nCreating Authentication DB for Users\n");

while ($lnu = shift(@user_dump)) {
  chop($lnu);
  ($uid, $statusu, $username, $shell, $pwd, $realname) = split(":", $lnu);
  if ($uid != "0") {
    if ($statusu eq "A") {
      $grplst = "";
      @group = grep(/$username/, @group_dump);

      while ($lng = shift(@group)) {
	chop($lng);
	($groupname, $statusg, $groupid, $userlist) = split(":", $lng);
	if ($statusg eq "A") {
	  if ($grplst ne "") {
	    $grplst .= ",";
	  }
	  $grplst .= $groupname;
	}
      }

      $pwdent = "/usr/bin/dbmmanage ".$passwd_authdb." add ".$username." ".$pwd." ".$grplst;
      print("$pwdent\n");
      system($pwdent);

      $pwdent = "/usr/bin/dbmmanage ".$passwd_authdb." update ".$username." ".$pwd." ".$grplst;
      print("$pwdent\n");
      system($pwdent);
    } elsif ($statusu eq "D") {
      $pwdent = "/usr/bin/dbmmanage ".$passwd_authdb." delete ".$username;
      print("$pwdent\n");
      system($pwdent);
    }
  }
}

print ("Done\n");
undef @user_dump;
