#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create user quota on NFS server
# based on infos available from /home/dummy/dumps25/user_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @user_dump = open_array_file($config{'user_dump'});
my ($uid, $status, $username, $shell, $pwd, $realname);

print("\nCreating User Quota for Users\n");
while ($ln = shift(@user_dump)) {
	chop($ln);
	($uid, $status, $username, $shell, $pwd, $realname) = split(":", $ln);
	print("$username ($uid)\n");
	if ($uid != "0") {
#	  print("/usr/bin/quota -v $uid > /tmp/nfsuser_quota.tst\n");
	  system("/usr/bin/quota -v $uid > /tmp/nfsuser_quota.tst");
	  my @quota = open_array_file("/tmp/nfsuser_quota.tst");
	  if ($status eq "A" && @quota < 3) {
	    print("/usr/sbin/edquota -p ".$config{'user_quota_temp_id'}." $uid ($username)\n");
	    system("/usr/sbin/edquota -p ".$config{'user_quota_temp_id'}." $uid");
	  }
	}
}

print ("Done\n");
undef @user_dump;
