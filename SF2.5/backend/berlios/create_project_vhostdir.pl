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
    $homdir = $docdir;
    $homdir=~s/\/htdocs\///;

    if ( $state eq "1" ) {
        if ( ! -d $homdir ) {
            print("mkdir -m 0555 -p $homdir\n");
            system("mkdir -m 0555 -p $homdir");
        }
        print("chown -h $gid:$gid $homdir\n");
        system("chown -h $gid:$gid $homdir");

        if ( ! -d $docdir ) {
            print("mkdir -m 0555 -p $docdir\n");
            system("mkdir -m 0555 -p $docdir");
        }
        print("chown -h $gid:$gid $docdir\n");
        system("chown -h $gid:$gid $docdir");

        if ( ! -d $cgidir ) {
            print("mkdir -m 0555 -p $cgidir\n");
            system("mkdir -m 0555 -p $cgidir");
        }
        print("chown -h $gid:$gid $cgidir\n");
        system("chown -h $gid:$gid $cgidir");

        if ( ! -d $logdir ) {
            print("mkdir -m 0555 -p $logdir\n");
            system("mkdir -m 0555 -p $logdir");
        }
        print("chown -h $gid:$gid $logdir\n");
        system("chown -h $gid:$gid $logdir");
    } elsif ( $state eq "2" ) {
	if ( -d $homdir ) {
            print("rm -rf $homdir\n");
            system("rm -rf $homdir");
        }
    } 
}

print ("Done\n");
undef @vhost_dump;
