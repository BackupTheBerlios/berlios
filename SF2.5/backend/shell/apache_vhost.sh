#!/bin/bash
#ident @(#)apache_vhost.sh	1.7 03/08/21 

PATH=$PATH:/opt/csw/bin
mkdir -p /tmp/.xxzzy

uname=`uname`

echo ""
echo "Checking VHOST Project Web Directories: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v 'ftp$'` ; do
	echo "Project: $i"

	if [ -d /home/groups/$i/vhost ] ; then
        chown -h $i:$i	/home/groups/$i/vhost
        chmod 0555	/home/groups/$i/vhost
	for k in `cd /home/groups/$i/vhost ; ls | grep -v lost+found | grep -v quota.group | grep -v 'ftp$'` ; do
		echo "  VHost: $k"

                chown -h $i:$i	/home/groups/$i/vhost/$k
                chmod 0555	/home/groups/$i/vhost/$k

		if [ ! -d /home/groups/$i/vhost/$k/log ] ; then
			mkdir	/home/groups/$i/vhost/$k/log
		fi
		chown -h $i:$i	/home/groups/$i/vhost/$k/log
		chmod 0755	/home/groups/$i/vhost/$k/log
		chmod g+s	/home/groups/$i/vhost/$k/log

		if [ ! -d /home/groups/$i/vhost/$k/cgi-bin ] ; then
			mkdir	/home/groups/$i/vhost/$k/cgi-bin
		fi
		chown -h $i:$i	/home/groups/$i/vhost/$k/cgi-bin
		chmod 0575	/home/groups/$i/vhost/$k/cgi-bin
		chmod g+s	/home/groups/$i/vhost/$k/cgi-bin

		if [ ! -d /home/groups/$i/vhost/$k/htdocs ] ; then
			mkdir	/home/groups/$i/vhost/$k/htdocs
		fi
		chown -h $i:$i	/home/groups/$i/vhost/$k/htdocs
		chmod 0575	/home/groups/$i/vhost/$k/htdocs
		chmod g+s	/home/groups/$i/vhost/$k/htdocs

		if [ "`ls /home/groups/$i/vhost/$k/htdocs/`" = "" ] ; then
			cp /usr/local/httpd/SF2.5/backend/berlios/default_page.php /home/groups/$i/vhost/$k/htdocs/index.php
			chown -h $i:$i	/home/groups/$i/vhost/$k/htdocs/index.php
			chmod    0664	/home/groups/$i/vhost/$k/htdocs/index.php
		fi

		if [ ."$uname" = .Linux ]; then

		# Linux does not have a POSIX find....

		find /home/groups/$i/vhost/$k/cgi-bin/. /home/groups/$i/vhost/$k/htdocs/. ! -group $i -print0 | xargs -0 chgrp -h $i /tmp/.xxzzy
		find /home/groups/$i/vhost/$k/htdocs/. -name usage -prune -o -type f ! -perm -0060 -print0 | xargs -0 chmod g+rw /tmp/.xxzzy
		find /home/groups/$i/vhost/$k/htdocs/. -name usage -prune -o -type d ! -perm -2070 -print0 | xargs -0 chmod g+rwxs /tmp/.xxzzy

		else

		# For any other OS assume a POSIX find

		find /home/groups/$i/vhost/$k/cgi-bin/. /home/groups/$i/vhost/$k/htdocs/. ! -group $i -exec chgrp -h $i {} \;
		find /home/groups/$i/vhost/$k/htdocs/. -name usage -prune -o -type f ! -perm -0060 -exec chmod g+rw {} \;
		find /home/groups/$i/vhost/$k/htdocs/. -name usage -prune -o -type d ! -perm -2070 -exec chmod g+rwxs {} \;

		fi

		chown -h $i:$i		/home/groups/$i/vhost/$k/log/*
		chmod    0644		/home/groups/$i/vhost/$k/log/*
	done
	fi
done

echo "Done"
