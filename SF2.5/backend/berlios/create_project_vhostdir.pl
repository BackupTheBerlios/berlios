#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000-2004 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create group/project VHOST home directories
# (/home/groups/<projectname>/vhost/<vhost_name>/[htdocs,cgi-bin,log])
# based on infos available in /home/dummy/dumps25/vhost_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @vhost_dump = open_array_file($config{'vhost_dump'});
my ($docdir, $cgidir, $logdir, $group_id, $state);

print("\nCreating VHOST Home Directories for Projects/Groups\n");
while ($ln = shift(@vhost_dump)) {
    chop($ln);
    ($docdir, $cgidir, $logdir, $group_id, $state) = split(":", $ln);
    $gid = $group_id + $config{'first_project_gid'};

    if ( $state eq "1" ) {
        if ( ! -d $docdir ) {
            print("mkdir -m 0555 -p $docdir\n");
            system("mkdir -m 0555 -p $docdir");
        }
#       print("chmod g+s $docdir\n");
#       system("find $docdir -type d | xargs chmod g+s");
        print("chown -h $gid:$gid $docdir\n");
        system("chown -h $gid:$gid $docdir");

        if ( ! -d $cgidir ) {
            print("mkdir -m 0555 -p $cgidir\n");
            system("mkdir -m 0555 -p $cgidir");
        }
#       print("chmod g+s $homdir\n");
#       system("find $cgidir -type d | xargs chmod g+s");
        print("chown -h $gid:$gid $cgidir\n");
        system("chown -h $gid:$gid $cgidir");

        if ( ! -d $logdir ) {
            print("mkdir -m 0555 -p $logdir\n");
            system("mkdir -m 0555 -p $logdir");
        }
#       print("chmod g+s $logdir\n");
#       system("find $logdir -type d | xargs chmod g+s");
        print("chown -h $gid:$gid $logdir\n");
        system("chown -h $gid:$gid $logdir");
    } elsif ( $state eq "2" ) {
        $docdir=~s/\/htdocs\///;
	if ( -d $docdir ) {
            print("rm -rf $docdir\n");
            system("rm -rf $docdir");
        }
    } 
}

print ("Done\n");
undef @vhost_dump;
