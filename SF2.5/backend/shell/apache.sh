#!/bin/bash
#ident @(#)apache.sh	1.7 03/08/21 

PATH=$PATH:/opt/csw/bin
mkdir -p /tmp/.xxzzy

uname=`uname`

echo ""
echo "Checking Project Web Directories: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v '^ftp'` ; do
	echo "Project: $i"
	if [ ! -d /home/groups/$i/log ] ; then
		mkdir			/home/groups/$i/log
		chown -h wwwrun:$i	/home/groups/$i/log
		chmod    2775		/home/groups/$i/log
	fi

	if [ ! -d /home/groups/$i/cgi-bin ] ; then
		mkdir			/home/groups/$i/cgi-bin
		chown -h $i:$i		/home/groups/$i/cgi-bin
		chmod    2775		/home/groups/$i/cgi-bin
	fi

	if [ ! -d /home/groups/$i/htdocs ] ; then
		mkdir			/home/groups/$i/htdocs
		chown -h $i:$i		/home/groups/$i/htdocs
		chmod    2775		/home/groups/$i/htdocs
	fi

	if [ "`ls /home/groups/$i/htdocs/`" = "" ] ; then
		cp /usr/local/httpd/SF2.5/backend/berlios/default_page.php /home/groups/$i/htdocs/index.php
		chown -h $i:$i		/home/groups/$i/htdocs/index.php
		chmod    0664		/home/groups/$i/htdocs/index.php
	fi

	if [ ."$uname" = .Linux ]; then

	# Linux does not have a POSIX find....

	find /home/groups/$i/cgi-bin/. /home/groups/$i/htdocs/. ! -group $i -print0 | xargs -0 chgrp -h $i /tmp/.xxzzy
	find /home/groups/$i/htdocs/. -type f ! -perm -0060 -print0 | xargs -0 chmod g+rw /tmp/.xxzzy
	find /home/groups/$i/htdocs/. -type d ! -perm -2070 -print0 | xargs -0 chmod g+rwxs /tmp/.xxzzy

	else

	# For any other OS assume a POSIX find

	find /home/groups/$i/cgi-bin/. /home/groups/$i/htdocs/. ! -group $i -exec chgrp -h $i {} +
	find /home/groups/$i/htdocs/. -type f ! -perm -0060 -exec chmod g+rw {} +
	find /home/groups/$i/htdocs/. -type d ! -perm -2070 -exec chmod g+rwxs {} +

	fi

	chown -h wwwrun:$i		/home/groups/$i/log/*
	chmod    0664			/home/groups/$i/log/*
	
done

echo "Done"