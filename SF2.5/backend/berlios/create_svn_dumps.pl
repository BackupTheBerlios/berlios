#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create SVN dumps for every Project/Group
# (/usr/local/httpd/htdocs.berlios/svndumps/<projectname>-repos.gz)
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating SVN dumps for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist) = split(":", $ln);
	($username) = split(",", $userlist);
	if ($status eq "A") {
		$dumpcmd = "svnadmin dump ".$config{'svnroot'}."/repos/$groupname >/tmp/$groupname-repos";
		print("\n$dumpcmd\n");
		system($dumpcmd);
		$gzipcmd = "gzip -f /tmp/$groupname-repos";
	        print("$gzipcmd\n");
                system($gzipcmd);
                $cpcmd = "su -c \"cp /tmp/$groupname-repos.gz ".$config{'svn_dumps'}."\" - dummy";
                print("$cpcmd\n");
                system($cpcmd);
                $rmcmd = "rm /tmp/$groupname-repos.gz";
                print("$rmcmd\n");
                system($rmcmd);

	} elsif ($status eq "D") {
		$filecmd = "su -c \"rm ".$config{'svn_dumps'}."/$groupname-repos.gz\" - dummy";
		print("\n$filecmd\n");
		system("$filecmd");
	}
}

print ("Done\n");
undef @group_dump;
