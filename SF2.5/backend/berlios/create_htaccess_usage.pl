#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000-2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create .htaccess in /home/groups/<projectname>/usage based on infos
# available in /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $grpstatus, $groupid, $userlist);

print("\nCreating .htaccess in htdocs/usage for Projects/Groups\n");
while ($grp = shift(@group_dump)) {
	chop($grp);
	($groupname, $status, $groupid, $userlist, $use_cvs) = split(":", $grp);
	if ($status eq "A" || $status eq "H") {
	  $dir = $config{'project_home'}."/".$groupname."/htdocs/usage/";
	  if ( -d $dir ) {
	    $htaccessfn = $dir.".htaccess";
	    if ( ! -e $htaccessfn ) {
	      print("File created: $htaccessfn\n");
	      @htaccess = ();
	      push(@htaccess, "AuthName \"Members Only\"\n");
	      push(@htaccess, "AuthType Basic\n");
	      push(@htaccess, "AuthDBMUserFile /etc/httpd/passwd/passwd_authdb\n");
	      push(@htaccess, "AuthDBMGroupFile /etc/httpd/passwd/passwd_authdb\n");
	      push(@htaccess, "require group ".$groupname);
	      write_array_file($htaccessfn, @htaccess);
	      system("chown ".$groupname.":".$groupname." ".$htaccessfn);
	      system("chmod 0644 ".$htaccessfn);
	    }
	  }
	}
}

print ("Done\n");
undef @group_dump;
