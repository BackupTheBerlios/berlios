#!/usr/bin/perl
#
# BerliOS: The Open Source Mediator
# Copyright 2000 (c) The BerliOS Crew
# http://www.berlios.de/
#
# Create DNS mapping for project groups in
# /etc/db.berlios and /etc/db.berlios.inaddr
# based on infos available from /home/dummy/dumps25/group_dump
#

require("include.pl");  # Include all the predefined functions and variables

my @domain = open_array_file($config{'dns_db_domain'});
my @group_dump = open_array_file($config{'group_dump'});
my ($groupname, $status, $groupid, $userlist);

write_array_file($config{'dns_db_domain'}.".old", @domain);
@domain = ();
push(@domain, ";\n");
push(@domain, "; Aliases of BerliOS Project Groups\n");
push(@domain, ";\n");
print("\nCreating DNS mapping for Projects/Groups\n");
while ($ln = shift(@group_dump)) {
	chop($ln);
	($groupname, $status, $groupid, $userlist) = split(":", $ln);
	$dnsent = $groupname.".".$config{'domain_name'}.".\tIN A\t".$config{'project_hostaddr'}."\n";
	push(@domain, $dnsent);
	print("$dnsent");
}

write_array_file($config{'dns_db_domain'}, @domain);

print ("Done\n");
undef @domain;
undef @group_dump;
