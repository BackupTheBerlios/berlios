#!/usr/bin/perl
#
# SourceForge: Breaking Down the Barriers to Open Source Development
# Copyright 1999-2000 (c) The SourceForge Crew
# http://sourceforge.net
#
# $Id: DatabaseDump.pl,v 1.3 2004/05/13 13:23:58 helix Exp $
#
use DBI;
use Sys::Hostname;
use POSIX qw(strftime);

require("include.pl");

&open_log_file;

# All of the files that we will be creating
my @file_array = ("user_dump", "group_dump", "ssh_dump", "list_dump", "user.aliases", "access.conf", "vhost.conf", "mailman.aliases", "dns.zone", "dns_berlios_de", "rsyncd.conf");

# Check to make sure that the environment is clean
if (! -d $config{'database_dump_dir'}) {
	&logme("The file directory doesn't exist: $file_dir");
	exit 1;
}

foreach(@file_array) {
	if (-f $config{'database_dump_dir'}.$_) {
		&logme("Another Dump File Exists (Overwriting): $_");
	}
}

my ($foo, $bar, $x);
	
# open up database include file and get the database variables
open(FILE, $config{'database_include'}) || die "Can't open $config{'database_include'}: $!\n";
while ($x = <FILE>) {
	($foo, $bar) = split /=/, $x;
	if ($foo) { eval $x; }
}
close(FILE);

# connect to the Postgres database
$dbh ||= DBI->connect("DBI:Pg:dbname=$sys_dbname;host=$sys_dbhost", "$sys_dbuser", "$sys_dbpasswd");


my $date = localtime();
print("\nDumping Table Data on $date\n");

# Okay lets dump and configure all the tables now.

my ($query, $c, @tmp_array);

###################################
# First the User Information.
###################################
print("Dumping User Data: ");

$query = "select unix_uid, status, user_name, shell, unix_pw, realname from users where unix_status != 'N'";
$c = $dbh->prepare($query);
$c->execute();

while(my ($id, $status, $username, $shell, $passwd, $realname) = $c->fetchrow()) {
	$username =~ s/://g;
	push @tmp_array, "$id:$status:$username:$shell:$passwd:$realname\n";
}

&done("user_dump", @tmp_array);
undef @tmp_array;


###################################
# Now Dump the Group Information.
###################################
print("Dumping Group Data: ");

$query = "select group_id,unix_group_name,status from groups";
$c = $dbh->prepare($query);
$c->execute();

while(my ($group_id, $group_name, $status) = $c->fetchrow()) {
	$new_query = "select users.user_name AS user_name FROM users,user_group WHERE users.user_id=user_group.user_id AND group_id=$group_id AND users.status='A'";
	$d = $dbh->prepare($new_query);
	$d->execute();

	$user_list = "";
	while($user_name = $d->fetchrow()) {
		$user_list .= "$user_name,";
	}

	$group_list = "$group_name:$status:$group_id:$user_list\n";
	$group_list =~ s/,$//;	# regex out the last comma on the line

	push @tmp_array, $group_list;
}

&done("group_dump", @tmp_array);
undef @tmp_array;

###################################
# Dump the SSH authorized_keys Data
###################################
print("Dumping SSH Data: ");

$query = "SELECT user_name,authorized_keys FROM users WHERE authorized_keys != ''";
$c = $dbh->prepare($query);
$c->execute();

while(my ($username, $ssh_key) = $c->fetchrow()) {
	$ssh_key =~ s/://g;
	push @tmp_array, "$username:$ssh_key\n";
}

# Now write out the files
&done("ssh_dump", @tmp_array);
undef @tmp_array;


###################################
# Dump the Mailing list Information
###################################
print("Dumping Mailing List Data: ");

$query = "SELECT users.user_name,mail_group_list.list_name,mail_group_list.password,mail_group_list.is_public,mail_group_list.status FROM mail_group_list,users WHERE mail_group_list.list_admin=users.user_id";
$c = $dbh->prepare($query);
$c->execute();

while(my ($list_name, $list_admin, $password, $is_public, $status) = $c->fetchrow()) {
	push @tmp_array, "$list_name:$list_admin:$password:$is_public:$status\n";
}

&done("list_dump", @tmp_array);
undef @tmp_array;

###################################
# Apache Dump and configuration
###################################
print("Dumping access.conf Data: ");

$query = "SELECT http_domain,unix_group_name,group_name,status FROM groups WHERE unix_box='unicorn' AND http_domain LIKE '%.%'";
$c = $dbh->prepare($query);
$c->execute();

while(my ($http_domain,$unix_group_name,$group_name,$status) = $c->fetchrow()) {
	if ($status eq "A") {
		push @tmp_array, "\n\n### Host entries for: $group_name\n\n";
		push @tmp_array, "<Directory \"$config{'group_dir_prefix'}/$unix_group_name/htdocs\">\n";
		if ($unix_group_name eq "sourceagency") {
			push @tmp_array, "    AllowOverride AuthConfig FileInfo Indexes Limit Options\n";
		} else {
			push @tmp_array, "    AllowOverride AuthConfig FileInfo Indexes Limit Options\n";
		}
		push @tmp_array, "    Options Indexes Includes FollowSymLinks MultiViews\n";
		push @tmp_array, "    Order allow,deny\n";
		push @tmp_array, "    Allow from all\n";
		push @tmp_array, "</Directory>\n";
		push @tmp_array, "<Directory \"$config{'group_dir_prefix'}/$unix_group_name/cgi-bin\">\n";
		push @tmp_array, "    AllowOverride AuthConfig FileInfo\n";
		push @tmp_array, "    Options ExecCGI\n";
		push @tmp_array, "    Order allow,deny\n";
		push @tmp_array, "    Allow from all\n";
		push @tmp_array, "</Directory>\n";
		push @tmp_array, "<VirtualHost $config{'www_ip_addr'}>\n";
		push @tmp_array, "    DocumentRoot \"$config{'group_dir_prefix'}/$unix_group_name/htdocs/\"\n";
		push @tmp_array, "    CustomLog $config{'group_dir_prefix'}/$unix_group_name/log/combined.log combined\n";
###		push @tmp_array, "    ErrorLog $config{'group_dir_prefix'}/$unix_group_name/log/error.log\n";
		push @tmp_array, "    ScriptAlias /cgi-bin/ \"$config{'group_dir_prefix'}/$unix_group_name/cgi-bin/\"\n";
		push @tmp_array, "    ServerName $http_domain\n";
		push @tmp_array, "</VirtualHost>\n";

		@sslgroups = ('cineclan', 'sourcewell', 'sourcebiz', 'sourcelines', 'docswell', 'devcounter', 'sourceagency', 'tuxbox', 'linuxnetmag' );
		if ( grep(/$unix_group_name/, @sslgroups) ) {
                  push @tmp_array, "<VirtualHost $config{'www_ip_addr'}:443>\n";
                  push @tmp_array, "    DocumentRoot \"$config{'group_dir_prefix'}/$unix_group_name/htdocs/\"\n";
		  push @tmp_array, "    CustomLog $config{'group_dir_prefix'}/$unix_group_name/log/combined.log combined\n";
###		  push @tmp_array, "    ErrorLog $config{'group_dir_prefix'}/$unix_group_name/log/error.log\n";
                  push @tmp_array, "    ScriptAlias /cgi-bin/ \"$config{'group_dir_prefix'}/$unix_group_name/cgi-bin/\"\n";
                  push @tmp_array, "    ServerName $http_domain\n";
                  push @tmp_array, "    SSLEngine on\n";
                  push @tmp_array, "    SSLCipherSuite ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP:+eNULL\n";
##		  push @tmp_array, "    SSLCertificateFile /etc/httpd/ssl.crt/server.crt\n";
##		  push @tmp_array, "    SSLCertificateKeyFile /etc/httpd/ssl.key/server.key\n";
                  push @tmp_array, "    <Files ~ \"\\.(cgi|shtml|phtml|php3?)\$\">\n";
                  push @tmp_array, "        SSLOptions +StdEnvVars\n";
                  push @tmp_array, "    </Files>\n";
                  push @tmp_array, "    <Directory \"$config{'group_dir_prefix'}/$unix_group_name/cgi-bin\">\n";
                  push @tmp_array, "        SSLOptions +StdEnvVars\n";
                  push @tmp_array, "    </Directory>\n";
                  push @tmp_array, "    SetEnvIf User-Agent \".*MSIE.*\" nokeepalive ssl-unclean-shutdown downgrade-1.0 force-response-1.0\n";
                  push @tmp_array, "</VirtualHost>\n";
		}
	}
}

&done("access.conf", @tmp_array);
undef @tmp_array;

###################################
# Apache Virtual Host Dump and configuration
###################################
print("Dumping vhost.conf Data: ");

$query = "SELECT * FROM prweb_vhost";
$c = $dbh->prepare($query);
$c->execute();

while(my ($vhostid,$vhost_name,$docdir,$cgidir,$logdir,$group_id) = $c->fetchrow()) {
    push @tmp_array, "\n\n### Virtual Host entries for: $vhost_name\n\n";
	push @tmp_array, "<VirtualHost $config{'www_ip_addr'}:80>\n";
	push @tmp_array, "    DocumentRoot \"$docdir\"\n";
	push @tmp_array, "    CustomLog ${logdir}combined.log combined\n";
	push @tmp_array, "    ErrorLog ${logdir}error.log\n";
	push @tmp_array, "    ScriptAlias /cgi-bin/ \"$cgidir\"\n";
	push @tmp_array, "    ServerName $vhost_name\n";
	push @tmp_array, "</VirtualHost>\n";

        push @tmp_array, "<VirtualHost $config{'www_ip_addr'}:443>\n";
	push @tmp_array, "    DocumentRoot \"$docdir\"\n";
	push @tmp_array, "    CustomLog ${logdir}combined.log combined\n";
	push @tmp_array, "    ErrorLog ${logdir}error.log\n";
	push @tmp_array, "    ScriptAlias /cgi-bin/ \"$cgidir\"\n";
	push @tmp_array, "    ServerName $vhost_name\n";
        push @tmp_array, "    SSLEngine on\n";
        push @tmp_array, "    SSLCipherSuite ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP:+eNULL\n";
##	push @tmp_array, "    SSLCertificateFile /etc/httpd/ssl.crt/server.crt\n";
##	push @tmp_array, "    SSLCertificateKeyFile /etc/httpd/ssl.key/server.key\n";
        push @tmp_array, "    <Files ~ \"\\.(cgi|shtml|phtml|php3?)\$\">\n";
        push @tmp_array, "        SSLOptions +StdEnvVars\n";
        push @tmp_array, "    </Files>\n";
        push @tmp_array, "    <Directory \"$cgidir\">\n";
        push @tmp_array, "        SSLOptions +StdEnvVars\n";
        push @tmp_array, "    </Directory>\n";
        push @tmp_array, "    SetEnvIf User-Agent \".*MSIE.*\" nokeepalive ssl-unclean-shutdown downgrade-1.0 force-response-1.0\n";
        push @tmp_array, "</VirtualHost>\n";
}

&done("vhost.conf", @tmp_array);
undef @tmp_array;

###################################
# Dump User Mail Aliases
###################################
print("Dumping User Mail Alias Data: ");

$query = "SELECT user_name,email FROM users WHERE status = 'A' AND email != ''";
$c = $dbh->prepare($query);
$c->execute();
while(my ($username, $email) = $c->fetchrow()) {
#	push @tmp_array, "$username:mail.berlios.de:$username\n";
	push @tmp_array, "$username:\t$email\n";
}

&done("user.aliases", @tmp_array);
undef @tmp_array;

###################################
# Dump Mailing List Aliases
###################################
print("Dumping Mailing List Aliases Data: ");

# First lets Dump the Mailing List Info
push @tmp_array, "\n\n### Mailman Mailing List Aliases ###\n";

$query = "SELECT list_name from mail_group_list where is_public != '9'";
$c = $dbh->prepare($query);
$c->execute();
while(my ($list_name) = $c->fetchrow()) {
        push @tmp_array, "\n## $list_name Mailing List\n";
	push @tmp_array, sprintf("%-40s%-10s","$list_name:", "\"|/home/mailman/mail/wrapper post $list_name\"\n");
	push @tmp_array, sprintf("%-40s%-10s","$list_name-admin:", "\"|/home/mailman/mail/wrapper mailowner $list_name\"\n");
	push @tmp_array, sprintf("%-40s%-10s","$list_name-request:", "\"|/home/mailman/mail/wrapper mailcmd $list_name\"\n");
	push @tmp_array, sprintf("%-40s%-10s","$list_name-owner:", "$list_name-admin\n");
}

&done("mailman.aliases", @tmp_array);
undef @tmp_array;

###################################
# Dump DNS Information
###################################
print("Dumping DNS Zone File Data: ");

@tmp_array = open_array_file("./zones/dns.zone");

# Update the Serial Number
$date_line = $tmp_array[2];
$date_line =~ s/\t\t\t/\t/;

my ($blah,$date_str,$comments) = split("	", $date_line);
$date = $date_str;

my $serial = substr($date, 8, 2);
my $old_day = substr($date, 6, 2);

$serial++;

print("serial: $serial\n");

$now_string = strftime "%Y%m%d", localtime;
$new_day = substr($now_string, 6, 2);

if ($old_day != $new_day) { $serial = "01"; }

$new_serial = $now_string.$serial;

$tmp_array[2] = "		$blah	$new_serial	$comments";

&write_array_file("./zones/dns.zone", @tmp_array); # write the new serial out the zone file

$query = "SELECT http_domain,unix_group_name,group_name,unix_box FROM groups WHERE http_domain LIKE '%.%' AND status = 'A'";
$c = $dbh->prepare($query);
$c->execute();

while(my ($http_domain,$unix_group_name,$group_name,$unix_box) = $c->fetchrow()) {
	($foo, $foo, $foo, $foo, @addrs) = gethostbyname("$unix_box.berlios.de");
	@blah = unpack('C4', $addrs[0]);
	$ip = join(".", @blah);
	push @tmp_array, sprintf("%-24s%s",$unix_group_name,"\tIN\tA\t" . "$ip\n");
	push @tmp_array, sprintf("%-24s%s","", "\tIN\tMX\t" . "10 mail.berlios.de.\n");
	push @tmp_array, sprintf("%-24s%s","cvs.".$unix_group_name.".berlios.de.","\tIN\tCNAME\t" . "cvs.berlios.de."."\n\n");
}

&done("dns_berlios_de", @tmp_array);
undef @tmp_array;


###################################
# Now Dump the rsyncd Information.
###################################
print("Dumping rsyncd Data: ");

$query = "select group_id,group_name,unix_group_name,status from groups";
$c = $dbh->prepare($query);
$c->execute();

    push @tmp_array, "motd file = /etc/motd.rsyncd\n";
    push @tmp_array, "max connections = 10\n";
    push @tmp_array, "uid = ftp\n";
    push @tmp_array, "gid = ftpadmin\n";
    push @tmp_array, "use chroot = true\n";
    push @tmp_array, "read only = true\n";
    push @tmp_array, "timeout = 600\n";
    push @tmp_array, "transfer logging = true\n";
    push @tmp_array, "log format = %h %o %f %l %b\n";
    push @tmp_array, "log file = /var/log/rsyncd.log\n";
    push @tmp_array, "\n";
    push @tmp_array, "[incoming]\n";
    push @tmp_array, "        path = /usr/local/ftp/incoming\n";
    push @tmp_array, "        read only = false\n";
    push @tmp_array, "        comment = BerliOS FTP incoming directory\n";
    push @tmp_array, "        exclude = *\n";
    push @tmp_array, "        refuse options = recursive relative backup links perms devices group owner delete delete-excluded force rsync-path temp-dir compare-dest\n";

    push @tmp_array, "\n";
    push @tmp_array, "[csw]\n";
    push @tmp_array, "        path = /home/groups/blastwave/htdocs/csw\n";
    push @tmp_array, "        read only = true\n";
    push @tmp_array, "        use chroot = yes\n";
    push @tmp_array, "        comment = Berlios Blastwave CSW mirror\n";

while(my ($group_id, $group_name, $unix_group_name, $status) = $c->fetchrow()) {
	if ($status eq "A") {
	  push @tmp_array, "\n";
	  push @tmp_array, "[$unix_group_name]\n";
	  push @tmp_array, "        path = /usr/local/httpd/htdocs.download/$unix_group_name\n";
	  push @tmp_array, "        comment = BerliOS Project Download: $group_name\n";
          push @tmp_array, "\n";
          push @tmp_array, "[${unix_group_name}_ftp]\n";
          push @tmp_array, "        path = /usr/local/ftp/pub/$unix_group_name\n";
          push @tmp_array, "        comment = BerliOS Project FTP: $group_name\n";
	}
}

&done("rsyncd.conf", @tmp_array);
undef @tmp_array;



$dbh->disconnect();

sub done {	# Done Function for db_parse.pl
	my ($file_name, @file_array) = @_;
	
	write_array_file($config{'database_dump_dir'}."/".$file_name, @file_array);
	print("Done.\n");
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
