#!/bin/bash

echo ""
echo "Set permissions for usage subdirs of projects: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp$` ; do
	echo ""

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

		echo "Set permissions of vhost directories for project $i"
		for k in `cd /home/groups/$i/vhost ; ls` ; do
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
