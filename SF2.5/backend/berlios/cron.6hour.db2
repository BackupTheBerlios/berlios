#!/bin/bash
#
# BerliOS tasks to be executed every 6 hours a day on
# postgres database server: db2.berlios.de
#

echo "BerliOS tasks to be executed every 6 hours a day on Postgres Database server"

#
# Dumps user information, group information, SSH authorized_keys data,
# mailing list information, Apache configuration, project mail aliases,
# user mail aliases and DNS information out of SF database.
#
su -c "cd /usr/local/httpd/SF2.5/backend; ./DatabaseDump.pl" - dummy

#
# Create/Update/delete MySQL databases
#
cd /usr/local/httpd/SF2.5/utils
./mysql_create.pl

echo ""
echo "Done."
