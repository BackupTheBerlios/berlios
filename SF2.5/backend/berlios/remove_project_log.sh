#!/bin/bash

echo "Remove BerliOS projects log files older than 14 days"

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp`; do
	echo "Remove log files for project: $i"
	find /home/groups/$i/log -atime +14 -and -type f -exec rm {} \;
done

echo ""
echo "Done."
