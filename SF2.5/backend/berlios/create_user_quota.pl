#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create user quota
# based on infos available from /home/dummy/dumps25/user_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @user_dump = open_array_file($config{'user_dump'});
my ($uid, $status, $username, $shell, $pwd, $realname);

print("\nCreating User Quota for Users\n");
while ($ln = shift(@user_dump)) {
	chop($ln);
	($uid, $status, $username, $shell, $pwd, $realname) = split(":", $ln);
	if ($uid != "0") {
	  print("/usr/sbin/edquota -p ".$config{'user_quota_temp'}." $username\n");
	  system("/usr/sbin/edquota -p ".$config{'user_quota_temp'}." $username");
	}
}

print ("Done\n");
undef @user_dump;
