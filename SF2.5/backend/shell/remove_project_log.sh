#!/bin/bash

echo "Remove BerliOS projects log files older than 14 days"

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp$`; do
	echo "Remove log files for project: $i"
#	echo "find /home/groups/$i/log -mtime +14 -type f -exec rm {}"
	find /home/groups/$i/log -mtime +14 -type f -exec rm {} \;

	if [ -d /home/groups/$i/vhost ] ; then
		for k in `cd /home/groups/$i/vhost ; ls`; do 
        		echo "Remove log files for VHOST: $k"
#			echo "find /home/groups/$i/vhost/$k/log -mtime +14 -type f -exec rm {}"
        		find /home/groups/$i/vhost/$k/log -mtime +14 -type f -exec rm {} \;
		done
	fi
done

echo ""
echo "Done."
