#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000-2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create group/project ftp directory (/home/groups/ftp/pub/berlios/<projectname>)
# based on infos available in /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

print("\nCreating FTP Directories for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
  chop($ln);
  ($groupname, $status, $groupid, $userlist) = split(":", $ln);
  $ftpdir = $config{'project_ftp'}."/".$groupname;
  if ($status eq "A") {
	if ( ! -d $ftpdir ) {
		print("mkdir -m 0775 $ftpdir\n");
		system("mkdir -m 0775 $ftpdir");
	}
	print("chmod g+s $ftpdir\n");
	system("find $ftpdir -type d | xargs chmod g+s");
	print("chown $groupname:$groupname $ftpdir\n");
	system("chown $groupname:$groupname $ftpdir");
        print("chgrp -R $groupname $ftpdir\n");
        system("chgrp -R $groupname $ftpdir");
        print("chmod -R a+r $ftpdir\n");
        system("chmod -R a+r $ftpdir");
	print("chmod -R ug+w $ftpdir\n");
	system("chmod -R ug+w $ftpdir");
  } elsif ($status eq "D") {
	if ( -d $ftpdir ) {
		print("rm -r $ftpdir\n");
		system("rm -r $ftpdir");
	}
  }
}

print ("Done\n");
undef @group_dump;
