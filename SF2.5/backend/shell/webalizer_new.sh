#!/bin/bash

PATH=$PATH:/opt/csw/bin

echo ""
echo "Create project usage directory for Webalizer: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp` ; do
	if [ ! -d /home/groups/$i/htdocs/usage ] ; then
		mkdir /home/groups/$i/htdocs/usage
		chown wwwrun:$i /home/groups/$i/htdocs/usage
		chmod 0775 /home/groups/$i/htdocs/usage
		chmod g+s /home/groups/$i/htdocs/usage
	fi

	for l in `cd /home/groups/$i/log ; ls 2*.log` ; do
	    webalizer -p -n "$i.berlios.de" -o /home/groups/$i/htdocs/usage /home/groups/$i/log/combined.$l
	done

	chown wwwrun:$i /home/groups/$i/htdocs/usage/*
	chmod 0664 /home/groups/$i/htdocs/usage/*

done

echo "Done"
