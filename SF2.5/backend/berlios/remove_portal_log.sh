#!/bin/bash

echo "Remove BerliOS portal log files older than 14 days"

logdir=/var/log/httpd

for i in berlios devel forum news wiki maintenance ; do
	echo "Remove log files for portal: $i"
#	echo "find $logdir/$i -atime +14 -a -type f -exec rm {}"
	find $logdir/$i -atime +14 -a -type f -exec rm {} \;
done

echo ""
echo "Done."
