#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create CVS readers/writers in /cvsroot/<projectname>/CVSROOT based on infos
# available in /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating CVS Permissions for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist) = split(":", $ln);
	if ($status eq "A") {
	  $readersfn = $config{'cvsroot'}."/".$groupname."/CVSROOT/readers";
	  @users = ( "anonymous\n" );
	  print("$readersfn:\n");
	  foreach ( @users ) {
	    print("$_");
	  }
	  write_array_file($readersfn, @users);
	  system("chown ".$groupname.":".$groupname." ".$readersfn);
#	$writersfn = $config{'cvsroot'}."/".$groupname."/CVSROOT/writers";
#	@users = split(",", $userlist);
#	@users = map { $_ . "\n" } @users;
#	print("$writersfn:\n");
#	foreach ( @users ) {
#	  print("$_");
#	}
#	write_array_file($writersfn, @users);
#	system("chown cvs".$groupname.":cvs".$groupname." ".$writersfn);
	}
}

print ("Done\n");
undef @group_dump;
