#!/usr/bin/perl -w
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#

require("config.pl");  # Include configuration defaults

sub done {	# Done Function for db_parse.pl
	my ($file_name, @file_array) = @_;
	
	write_array_file($file_name, @file_array);
}

sub open_array_file {	# File open function, spews the entire file to an array.
        my $filename = shift(@_);

	# Now read in the file as a big array
        open (FD, $filename) || die "Can't open $filename: $!.\n";
        @tmp_array = <FD>;
        close(FD);
        
        return @tmp_array;
}       


sub write_array_file {	# File write function.
        my ($file_name, @file_array) = @_;

	# Write this array out to $filename
        open(FD, ">$file_name") || die "Can't open $file_name: $!.\n";
        foreach (@file_array) { 
                if ($_ ne '') { 
                        print FD;
                }       
        }       
        close(FD);
}      
