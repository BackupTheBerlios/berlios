#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#

########################
# global configuration #
########################
$config{'domain_name'}        = 'berlios.de'; # domain name of BerliOS
$config{'project_hostname'}   = 'www.'.$config{'domain_name'}; # hostname hosting project groups
$config{'project_hostaddr'}   = '195.37.77.138'; # hostaddr hosting projectgroup
$config{'wwwuid'}             = 'wwwrun'; # userid of www server
$config{'sysgroup'}           = '/etc/group'; # system group file name
$config{'cvsroot'}            = '/cvsroot'; # CVS root directory
$config{'cvs_allow_root'}     = '/etc/cvs-allow-root'; # CVS allow root config file
$config{'cvsweb_root'}        = '/etc/httpd/cvswebroot.conf'; # cvsweb root config file
$config{'cvs_quota_temp'}     = 'cvsberlios'; # CVS quota template project group
$config{'cvs_quota_temp_id'}  = '10001'; # CVS quota template project group userid
$config{'cvs_tarballs'}       = '/usr/local/httpd/htdocs.berlios/cvstarballs'; # CVS tarballs directory
$config{'project_home'}       = '/home/groups'; # project home directory
$config{'project_ftp'}        = '/usr/local/ftp/pub'; # project ftp directory
$config{'project_quota_temp'} = 'berlios'; # quota template project group
$config{'project_quota_temp_id'} = '1001'; # quota template project group userid
$config{'dns_db_domain'}      = '/etc/db.berlios'; # dns name-to-address mapping filename for project groups
$config{'user_home'}          = '/home/users'; # users home directory
$config{'user_quota_temp'}    = 'helix'; # quota template user
$config{'user_quota_temp_id'} = '20002'; # quota template userid
$config{'shell'}              = '/bin/bash'; # default shell
$config{'noshell'}            = '/bin/false'; # default noshell
$config{'dummy_accnt'}        = '/home/dummy'; # dummy account
$config{'incoming_ftp'}       = '/usr/local/ftp/incoming'; # release incoming ftp directory
$config{'release_download'}   = '/usr/local/httpd/htdocs.download'; # project release download directory
$config{'release_archive'}    = '/usr/local/httpd/htdocs.archive'; # project release archive directory
$config{'group_dump'}         = $config{'dummy_accnt'}.'/dumps25/group_dump'; # group dump filename
$config{'user_dump'}          = $config{'dummy_accnt'}.'/dumps25/user_dump'; # user dump filename
$config{'list_dump'}          = $config{'dummy_accnt'}.'/dumps25/list_dump'; # mailing list dump filename
$config{'first_project_gid'}  = 1000; # first uid/gid of project groups
$config{'first_cvsproject_gid'}  = 10000; # first cvs uid/gid of project groups
$config{'shell_user_skel'}    = '/etc/shell_user_skel'; # skeleton directory for users on Shell server
