#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create mailing lists for project groups (mailman)
# based on infos available from /home/dummy/dumps25/list_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @list_dump = open_array_file($config{'list_dump'});
my ($user, $listname, $passwd, $is_public, $status);

print("\nCreating mailing lists for Projects/Groups\n");
while ($ln = shift(@list_dump)) {
	chop($ln);
	($user, $listname, $passwd, $is_public, $status) = split(":", $ln);
	if ($status == 1) {
		if ($is_public == 0) {
	  		$cmd = "newlist -q $listname $user\@mail.berlios.de $passwd 1";
		}
		if ($is_public == 1) {
                        $cmd = "newlist -q $listname $user\@mail.berlios.de $passwd 1";
                }
                if ($is_public == 8) {
                        $cmd = "rmlist $listname";
	                system($cmd);
			print("$cmd\n");
			$cmd = "newlist -q $listname $user\@mail.berlios.de $passwd 1";
                }

		if ($is_public == 9) {
                        $cmd = "rmlist -a $listname";
                }
	  	system($cmd);
	  	print("$cmd\n");
	}
}

print ("Done\n");
undef @list_dump;
