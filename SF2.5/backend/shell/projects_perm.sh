#!/bin/bash

echo ""
echo "Set permissions for projects: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp` ; do
	echo "Set permissions of directories for project $i"
	chown $i:$i /home/groups/$i
	chmod 0555 /home/groups/$i
	chown $i:$i /home/groups/$i/cgi-bin
	chmod 2575 /home/groups/$i/cgi-bin
	chown $i:$i /home/groups/$i/htdocs
	chmod 2575 /home/groups/$i/htdocs
        chown $i:$i /home/groups/$i/log
        chmod 2575 /home/groups/$i/log

        echo "Set permissions of log directory for project $i"
        chown $i:$i /home/groups/$i/log
        chmod 0664 /home/groups/$i/log/*

	echo "Set permissions of usage directory for project $i"
	chown $i:$i /home/groups/$i/htdocs/usage
	chmod 2555 /home/groups/$i/htdocs/usage

        echo "Set permissions of usage directory files for project $i"
	chown $i:$i /home/groups/$i/htdocs/usage/*
	chmod 0444 /home/groups/$i/htdocs/usage/*

done

echo "Done"
