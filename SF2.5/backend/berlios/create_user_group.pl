#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create new user groups in /etc/group (/home/dummy/dumps25/group)
# based on infos available from /home/dummy/dumps25/user_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group = open_array_file($config{'sysgroup'});
my @user_dump = open_array_file($config{'user_dump'});
my ($uid, $status, $username, $shell, $pwd, $realname);

write_array_file($config{'sysgroup'}.".user.old", @group);
print("\nCreating Groups for Users\n");
while ($ln = shift(@user_dump)) {
	chop($ln);
	($uid, $status, $username, $shell, $pwd, $realname) = split(":", $ln);
	if ($uid != "0" && $status eq "A") {
	  $grpent = $username . "::" . $uid . ":" . "\n";
	  @group = grep(!/^$username:/, @group);
	  push(@group, $grpent);
	  print("$grpent");
	}
}

write_array_file($config{'sysgroup'}, @group);
print ("Done\n");
undef @group;
undef @user_dump;
