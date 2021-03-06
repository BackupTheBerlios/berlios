#!/bin/bash
#ident @(#)apache.sh	1.7 03/08/21 

PATH=$PATH:/opt/csw/bin
mkdir -p /tmp/.xxzzy

uname=`uname`

echo ""
echo "Checking Project Web Directories: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v '^ftp$'` ; do
	echo "Project: $i"

	chown -h $i:$i  /home/groups/$i
	chmod 0555      /home/groups/$i

	if [ ! -d /home/groups/$i/log ] ; then
		mkdir	/home/groups/$i/log
	fi
	chown -h $i:$i	/home/groups/$i/log
	chmod 0755	/home/groups/$i/log
	chmod g+s	/home/groups/$i/log

	if [ ! -d /home/groups/$i/cgi-bin ] ; then
		mkdir	/home/groups/$i/cgi-bin
	fi
	chown -h $i:$i	/home/groups/$i/cgi-bin
	chmod 0575	/home/groups/$i/cgi-bin
	chmod g+s	/home/groups/$i/cgi-bin

	if [ ! -d /home/groups/$i/htdocs ] ; then
		mkdir	/home/groups/$i/htdocs
	fi
	chown -h $i:$i	/home/groups/$i/htdocs
	chmod 0575	/home/groups/$i/htdocs
	chmod g+s	/home/groups/$i/htdocs

	if [ "`ls /home/groups/$i/htdocs/`" = "" ] ; then
		cp /usr/local/httpd/SF2.5/backend/berlios/default_page.php /home/groups/$i/htdocs/index.php
		chown -h $i:$i	/home/groups/$i/htdocs/index.php
		chmod    0664	/home/groups/$i/htdocs/index.php
	fi

	if [ ! -d /home/groups/$i/htdocs/usage ] ; then
                mkdir   /home/groups/$i/htdocs/usage
        fi
	chown -h $i:$i  /home/groups/$i/htdocs/usage
	chmod 0755      /home/groups/$i/htdocs/usage
	chmod g+s       /home/groups/$i/htdocs/usage

	if [ ."$uname" = .Linux ]; then

	# Linux does not have a POSIX find....

	find /home/groups/$i/cgi-bin/. /home/groups/$i/htdocs/. ! -group $i -print0 | xargs -0 chgrp -h $i /tmp/.xxzzy
	find /home/groups/$i/htdocs/. -name usage -prune -o -type f ! -perm -0060 -print0 | xargs -0 chmod g+rw /tmp/.xxzzy
	find /home/groups/$i/htdocs/. -name usage -prune -o -type d ! -perm -2070 -print0 | xargs -0 chmod g+rwxs /tmp/.xxzzy
        find /home/groups/$i/htdocs/usage \( ! -user $i -o ! -group $i \) -print0 | xargs -0 chown -h $i:$i /tmp/.xxzzy
        find /home/groups/$i/htdocs/usage ! -perm 0644 -a -type f -print0 | xargs -0 chmod 0644 /tmp/.xxzzy

	else

	# For any other OS assume a POSIX find

	find /home/groups/$i/cgi-bin/. /home/groups/$i/htdocs/. ! -group $i -exec chgrp -h $i {} +
	find /home/groups/$i/htdocs/. -name usage -prune -o -type f ! -perm -0060 -exec chmod g+rw {} +
	find /home/groups/$i/htdocs/. -name usage -prune -o -type d ! -perm -2070 -exec chmod g+rwxs {} +
        find /home/groups/$i/htdocs/usage \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
        find /home/groups/$i/htdocs/usage ! -perm 0644 -a -type f -exec chmod 0644 {} +

	fi

	find /home/groups/$i/log \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
	find /home/groups/$i/log ! -perm 0644 -a -type f -exec chmod 0644 {} +
done

echo "Done"
