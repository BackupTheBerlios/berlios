#!/usr/bin/perl -w
#
# SourceForge: Breaking Down the Barriers to Open Source Development
# Copyright 1999-2000 (c) The SourceForge Crew
# http://sourceforge.net
#
# $Id: include.pl,v 1.2 2004/04/21 10:24:24 helix Exp $
#

########################
# global configuration #
########################
$config{'database_include'} 	= '/etc/sourceforge/local25.inc';		# database include file
$config{'lock_file'}		= '/tmp/sf-backend25';		# lockfile location
$config{'log_file'}		= '/home/dummy/backend25.log';	# logfile location
$config{'group_dir_prefix'} 	= '/home/groups';               # prefix for group directories
$config{'user_dir_prefix'} 	= '/home/users';		# prefix for user directories
$config{'database_dump_dir'}	= '/home/dummy/dumps25';		# where are the database dumps kept
$config{'www_ip_addr'}          = '195.37.77.138';		# ip-addr of web server
$config{'delete_tar_dir'}	= '/tmp';			# place to stick tarballs of deleted accounts/groups
$config{'dummy_uid'}		= getpwnam('dummy');		# userid of the dummy user
$config{'days_since_epoch'} 	= int(time()/3600/24);		# number of days since the epoch
$config{'hostname'}		= hostname();			# machine hostname

####################
# open the logfile #
####################
sub open_log_file {
	open(Log, ">>$config{'log_file'}") || die "Couldn't Open Logfile: $!\n";
	select(Log);
	$| = 1;
	return;
}

##############################
# log message to the logfile #
##############################
sub logme {
	my $msg = shift(@_);
	my $time = strftime "%Y-%m-%d - %T", localtime;
	print "$time\t$msg\n";
	return;
}

##########################
# exit the script nicely #
##########################
sub exit_nicely {
	&logme("------ Script Ended -------\n");
	close(Log);
	exit 0;
}

#########################################
# open a file and read it into an array #
#########################################
sub open_array {
	my $filename = shift(@_);

	# Now read in the file as a big array
	open (FD, $filename) || die &logme("Can't open $filename: $!");
	@tmp_array = <FD>;
        close(FD);

	&logme("Opened $filename with $@tmp_array Lines");
        return @tmp_array;
}               

################################
# write an array out to a file #
################################
sub write_array {
	my ($filename, @filearray) = @_;

	# Write this array out to $filename
	open(FD, ">$filename") || die &logme("Can't open $filename: $!");
	foreach (@filearray) {
		if ($_ ne '') {
			print FD;
		}
	}
	&logme("Wrote $filename with $#filearray Lines");
	close(FD);
}
