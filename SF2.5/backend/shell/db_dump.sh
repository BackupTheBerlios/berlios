#!/bin/bash

PATH=$PATH:/opt/csw/bin

echo ""
echo "Dump Postgres Database... "

dumpfile=/var/lib/pgsql/backups/dumpall.sql

/bin/su -c "pg_dumpall > $dumpfile" - postgres

echo "/bin/su postgres; pg_dumpall > $dumpfile"
echo "Done."
