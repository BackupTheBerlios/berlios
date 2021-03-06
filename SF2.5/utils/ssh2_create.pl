#!/usr/bin/perl
#
# $Id: ssh2_create.pl,v 1.4 2004/05/06 11:39:48 helix Exp $
#
# ssh2_create.pl - Dumps SSH2 authorized_keys into users homedirs on the cvs server.
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

	$home_dir = "/home/users/$username";
	$ssh_dir = "/home/users/$username/.ssh";

	if (-d $home_dir) {
		if (! -d $ssh_dir) {
			system("mkdir $ssh_dir");
			print("mkdir $ssh_dir\n");
		}
		system("chmod 0700 $ssh_dir");
		print("chmod 0700 $ssh_dir\n");

		print("Writing authorized_keys2 for $username:\n");

		write_array_file("$ssh_dir/authorized_keys2", @user_authorized_keys);
#		system("chown $username:$username ~$username");
#		print("chown $username:$username ~$username\n");
		system("chown $username:$username $ssh_dir");
		print("chown $username:$username $ssh_dir\n");
		system("chmod 0600 $ssh_dir/authorized_keys2");
		print("chmod 0600 $ssh_dir/authorized_keys2\n");
		system("chown $username:$username $ssh_dir/authorized_keys2");
		print("chown $username:$username $ssh_dir/authorized_keys2\n");
	}
}
print ("Done\n");
undef @user_authorized_keys;
