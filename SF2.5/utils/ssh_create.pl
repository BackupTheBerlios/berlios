#!/usr/bin/perl
#
# $Id: ssh_create.pl,v 1.3 2004/04/21 10:26:38 helix Exp $
#
# ssh_create.pl - Dumps SSH authorized_keys into users homedirs on the cvs server.
#

require("include.pl");  # Include all the predefined functions and variables

my @ssh_key_file = open_array_file("/home/dummy/dumps25/ssh_dump");
my ($username, $ssh_keys, $ssh_dir);

print("\nProcessing Users\n");
while ($ln = pop(@ssh_key_file)) {
	chop($ln);

	($username, $ssh_key) = split(":", $ln, 2);

	$ssh_key =~ s/\#\#\#/\n/g;
#	$username =~ tr/[A-Z]/[a-z]/;

#	push @user_authorized_keys, $ssh_key . "\n";
	@user_authorized_keys = ($ssh_key . "\n");

	$ssh_dir = "/home/users/$username/.ssh";

	if (! -d $ssh_dir) {
		mkdir $ssh_dir, 0755;
	} else
	        system("chmod 0700 $ssh_dir");
		print("chmod 0700 $ssh_dir\n");
	}

	print("Writing authorized_keys for $username:\n");

	write_array_file("$ssh_dir/authorized_keys", @user_authorized_keys);
#	system("chown $username:$username ~$username");
#	print("chown $username:$username ~$username\n");
	system("chown $username:$username $ssh_dir");
	print("chown $username:$username $ssh_dir\n");
	system("chmod 0644 $ssh_dir/authorized_keys");
	print("chmod 0644 $ssh_dir/authorized_keys\n");
	system("chown $username:$username $ssh_dir/authorized_keys");
	print("chown $username:$username $ssh_dir/authorized_keys\n");
}
print ("Done\n");
undef @user_authorized_keys;
