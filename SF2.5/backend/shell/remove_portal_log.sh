#!/bin/bash

echo "Remove BerliOS portal log files older than 14 days"

logdir=/var/log/httpd

for i in berlios devel news maintenance ; do
	echo "Remove log files for portal: $i"
#	echo "find $logdir/$i -mtime +14 -type f -exec rm {}"
	find $logdir/$i -mtime +14 -type f -exec rm {} \;
done

echo ""
echo "Done."
