#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Script to start cvs pserver with allowed root directories
# specified in --allow-root-dirs=config_file
#

$cmd = "/usr/bin/cvs";
foreach (@ARGV) {
	if (( $conf_file ) = $_ =~ /^--allow-root-dirs=(.*)/) {
	        open (FD, $conf_file) || die "Can't open $conf_file: $!.\n";
		@conf_root = <FD>;
		while ($dir = shift(@conf_root)) {
			chop($dir);
			$cmd .= " --allow-root=".$dir if $dir ne "";
		}
		close(FD);
	} else {
		$cmd .= " ".$_;
	}
}
system("$cmd");
