#!/bin/bash

PATH=$PATH:/opt/csw/bin

echo ""
echo "Create project usage directories for Webalizer: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp` ; do
	if [ ! -d /home/groups/$i/htdocs/usage ] ; then
		mkdir /home/groups/$i/htdocs/usage
		chown $i:$i /home/groups/$i/htdocs/usage
		chmod 0775 /home/groups/$i/htdocs/usage
		chmod g+s /home/groups/$i/htdocs/usage
	fi

	for l in `cd /home/groups/$i/log ; ls 2*.log` ; do
		webalizer -p -n "$i.berlios.de" -o /home/groups/$i/htdocs/usage /home/groups/$i/log/combined.$l
	done

        for k in `cd /home/groups/$i/vhost ; ls` ; do
		if [ ! -d /home/groups/$i/vhost/$k/htdocs/usage ] ; then
                	mkdir /home/groups/$i/vhost/$k/htdocs/usage
                	chown $i:$i /home/groups/$i/vhost/$k/htdocs/usage
                	chmod 0775 /home/groups/$i/vhost/$k/htdocs/usage
                	chmod g+s /home/groups/$i/vhost/$k/htdocs/usage
        	fi

        	for l in `cd /home/groups/$i/vhost/$k/log ; ls 2*.log` ; do
            		webalizer -p -n "$k" -o /home/groups/$i/vhost/$k/htdocs/usage /home/groups/$i/vhost/$k/log/combined.$l
        	done

		chown $i:$i /home/groups/$i/vhost/$k/htdocs/usage/*
		chmod 0664 /home/groups/$i/vhost/$k/htdocs/usage/*

	done

	chown $i:$i /home/groups/$i/htdocs/usage/*
	chmod 0664 /home/groups/$i/htdocs/usage/*

done

echo "Done"
