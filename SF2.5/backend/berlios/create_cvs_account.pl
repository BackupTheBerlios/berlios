#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create CVS accounts in /cvsroot/<projectname>/CVSROOT/passwd based on infos
# available in /home/dummy/dumps25/group_dump and user_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $grpstatus, $groupid, $userlist);
my @user_dump = open_array_file($config{'user_dump'});
my ($uid, $usrstatus, $username, $shell, $pwd, $realname, $use_cvs);

print("\nCreating CVS accounts for Projects/Groups\n");
while ($grp = shift(@group_dump)) {
	chop($grp);
	($groupname, $status, $groupid, $userlist, $use_cvs) = split(":", $grp);
	if ($status eq "A") {
	  @users = split(",", $userlist);
	  $pwdfn = $config{'cvsroot'}."/".$groupname."/CVSROOT/passwd";
	  print("$pwdfn:\n");
	  @passwd = ();
	  if ($use_cvs) {
	    srand (time());
	    my $randletter = "(int (rand (26)) + (int (rand (1) + .5) % 2 ? 65 : 97))";
	    my $salt = sprintf ("%c%c", eval $randletter, eval $randletter);
	    $pwd = crypt("", $salt);
	    $pwdent = "anonymous:".$pwd.":".$groupname."\n";
	  } else {
	    $pwdent = "\n";
	  }
	  push(@passwd, $pwdent);
	  print("$pwdent");
#         while ($usr = shift(@users)) {
#	  chop($usr);
#	  @usrent = grep(/$usr/, @user_dump);
#	  ($uid, $usrstatus, $username, $shell, $pwd, $realname) = split(":", @usrent[0]);
#	  $pwdent = $username.":".$pwd.":cvs".$groupname."\n";
#	  push(@passwd, $pwdent);
#	  print("$pwdent");
#	}
	  write_array_file($pwdfn, @passwd);
	  system("chown ".$groupname.":".$groupname." ".$pwdfn);
	}
}

print ("Done\n");
undef @group_dump;
undef @user_dump;
