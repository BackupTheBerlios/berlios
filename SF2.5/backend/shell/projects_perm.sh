#!/bin/bash

echo ""
echo "Set permissions for projects: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp` ; do
	echo ""
	echo "Set permissions of directories for project $i"
	chown $i:$i /home/groups/$i
	chmod 0555 /home/groups/$i
	chmod g+s /home/groups/$i
	chown $i:$i /home/groups/$i/cgi-bin
	chmod 0575 /home/groups/$i/cgi-bin
	chmod g+s /home/groups/$i/cgi-bin
	chown $i:$i /home/groups/$i/htdocs
	chmod 0575 /home/groups/$i/htdocs
	chmod g+s /home/groups/$i/htdocs

	echo "Set permissions of log directory for project $i"
	chown $i:$i /home/groups/$i/log
	chmod 0755 /home/groups/$i/log
	chmod g+s /home/groups/$i/log
	chmod 0644 /home/groups/$i/log/*

	if [ -d /home/groups/$i/htdocs/usage ] ; then
		echo "Set permissions of usage directory for project $i"
		chown $i:$i /home/groups/$i/htdocs/usage
		chmod 0755 /home/groups/$i/htdocs/usage
		chmod g+s /home/groups/$i/htdocs/usage

		echo "Set permissions of usage directory files for project $i"
		chown $i:$i /home/groups/$i/htdocs/usage/*
		chmod 0644 /home/groups/$i/htdocs/usage/*
		chown $i:$i /home/groups/$i/htdocs/usage/.htaccess
		chmod 0644 /home/groups/$i/htdocs/usage/.htaccess
	fi

	if [ -d /home/groups/$i/vhost ] ; then
		chown $i:$i /home/groups/$i/vhost
		chmod 0555 /home/groups/$i/vhost
		chmod g+s /home/groups/$i/vhost

		echo "Set permissions of vhost directories for project $i"
		for k in `cd /home/groups/$i/vhost ; ls` ; do
			chown $i:$i /home/groups/$i/vhost/$k
			chmod 0555 /home/groups/$i/vhost/$k
			chmod g+s /home/groups/$i/vhost/$k
			chown $i:$i /home/groups/$i/vhost/$k/cgi-bin
			chmod 0575 /home/groups/$i/vhost/$k/cgi-bin
			chmod g+s /home/groups/$i/vhost/$k/cgi-bin
			chown $i:$i /home/groups/$i/vhost/$k/htdocs
			chmod 0575 /home/groups/$i/vhost/$k/htdocs
			chmod g+s /home/groups/$i/vhost/$k/htdocs

			echo "Set permissions of vhost log directory for project $i"
			chown $i:$i /home/groups/vhost/$k/$i/log
			chmod 0755 /home/groups/$i/vhost/$k/log
			chmod g+s /home/groups/$i/vhost/$k/log
			chmod 0644 /home/groups/$i/vhost/$k/log/*

			echo "Set permissions of vhost usage directory files for project $i"
			if [ -d /home/groups/$i/vhost/$k/htdocs/usage ] ; then
				chown $i:$i /home/groups/$i/vhost/$k/htdocs/usage
				chmod 0755 /home/groups/$i/vhost/$k/htdocs/usage
				chmod g+s /home/groups/$i/vhost/$k/htdocs/usage

				echo "Set permissions of vhost usage directory files for project $i"
				chown $i:$i /home/groups/$i/vhost/$k/htdocs/usage/*
				chmod 0644 /home/groups/$i/vhost/$k/htdocs/usage/*
				chown $i:$i /home/groups/$i/vhost/$k/htdocs/usage/.htaccess
				chmod 0644 /home/groups/$i/vhost/$k/htdocs/usage/.htaccess
			fi
		done
	fi
done

echo "Done"
