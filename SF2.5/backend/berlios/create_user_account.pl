#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create new user account in /etc/passwd
# based on infos available from /home/dummy/dumps25/user_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @user_dump = open_array_file("/home/dummy/dumps25/user_dump");
my ($uid, $status, $username, $shell, $pwd, $realname);

print("\nCreating Accounts for Users\n");
while ($ln = shift(@user_dump)) {
	chop($ln);
	($uid, $status, $username, $shell, $pwd, $realname) = split(":", $ln);
	if ($uid != "0") {
	  if ($status eq "A") {
	    $pwdent = "/usr/sbin/useradd -c \"".$realname . " at BerliOS\" -d ".$config{'user_home'}."/".$username." -g ".$username." -m -k ".$config{'shell_user_skel'}." -p '".$pwd."' -s ".$config{'shell'}." -u ".$uid." ".$username;
	    print("$pwdent\n");
	    system($pwdent);
            $pwdent = "/usr/sbin/usermod -g ".$username." -u ".$uid." -p '".$pwd."' ".$username;
            print("$pwdent\n");
            system($pwdent);
	    $cmd = "/bin/chown -R $uid:$username $config{'user_home'}/$username";
	    print("$cmd\n");
	    system($cmd);
	  } elsif ($status eq "S") {
            $pwdent = "/usr/sbin/usermod -g ".$username." -u ".$uid." -p '!' ".$username;
            print("$pwdent\n");
            system($pwdent);
	  } elsif ($status eq "D") {
            $pwdent = "/usr/sbin/usermod -g ".$username." -u ".$uid." -p '!' ".$username;
            print("$pwdent\n");
            system($pwdent);
	  }
	}
}

print ("Done\n");
undef @user_dump;
