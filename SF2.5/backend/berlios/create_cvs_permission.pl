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
my ($groupname, $status, $groupid, $userlist, $use_cvs);

print("\nCreating CVS Permissions for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist, $use_cvs) = split(":", $ln);
	if ($status eq "A") {
	  $readersfn = $config{'cvsroot'}."/".$groupname."/CVSROOT/readers";
	  if ($use_cvs) {
	    @users = ( "anonymous\n" );
	    $perm = "2775";
	  } else {
	    @users = ( "\n" );
	    $perm = "2770";
	  }
	  print("$readersfn:\n");
	  foreach ( @users ) {
	    print("$_");
	  }
	  write_array_file($readersfn, @users);
	  system("chown ".$groupname.":".$groupname." ".$readersfn);
          print("chown ".$groupname.":".$groupname." ".$readersfn."\n");
	  system("chown -R ".$groupname.":".$groupname." ".$config{'cvsroot'}."/".$groupname);
          print("chown -R ".$groupname.":".$groupname." ".$config{'cvsroot'}."/".$groupname."\n");
          system("find ".$config{'cvsroot'}."/".$groupname." -type d -print0 | xargs -0 chmod ".$perm);
          print("find ".$config{'cvsroot'}."/".$groupname." -type d -print0 | xargs -0 chmod ".$perm."\n");
#	$writersfn = $config{'cvsroot'}."/".$groupname."/CVSROOT/writers";
#	@users = split(",", $userlist);
#	@users = map { $_ . "\n" } @users;
#	print("$writersfn:\n");
#	foreach ( @users ) {
#	  print("$_");
#	}
#	write_array_file($writersfn, @users);
#	system("chown cvs".$groupname.":cvs".$groupname." ".$writersfn);
#       print("chown cvs".$groupname.":cvs".$groupname." ".$writersfn."\n");
	}
}

print ("Done\n");
undef @group_dump;
