#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000-2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create .htaccess in /home/groups/<projectname>/vhost/<vhost>/htdocs/usage
# based on info available in /home/dummy/dumps25/vhost_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @vhost_dump = open_array_file($config{'vhost_dump'});
my ($docdir, $cgidir, $logdir, $group_id, $state);

print("\nCreating .htaccess in vhost htdocs/usage for Projects/Groups\n");
while ($ln = shift(@vhost_dump)) {
	chop($ln);
	($docdir, $cgidir, $logdir, $group_id, $state) = split(":", $ln);
	if ($state eq 1) {
	  $gid = $group_id + $config{'first_project_gid'};
	  ($groupname, $passwd, $gid, $members) = getgrgid($gid);
	  $dir = $docdir."usage/";
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
undef @vhost_dump;
