#!/bin/bash

PATH=$PATH:/opt/csw/bin

echo ""
echo "Create home usage directory for Webalizer: "

logdir=/var/log/httpd
htdir=/usr/local/httpd/htdocs

for i in berlios devel forum news wiki ; do
	if [ ! -d $htdir.$i/usage ] ; then
		echo ""
		echo "Create usage directory of $i"
		mkdir $htdir.$i/usage
		chown wwwrun:root $htdir.$i/usage
		chmod 0770 $htdir.$i/usage
	fi

	for l in `cd $logdir/$i; ls 2*_log` ; do
	    echo "webalizer -p -n "$i.berlios.de" -o $htdir.$i/usage $logdir/$i/access_$l"
	    webalizer -p -n "$i.berlios.de" -o $htdir.$i/usage $logdir/$i/access_$l
	done

	echo "Set owner and permissions for usage directory of $i"
	chown wwwrun:root $htdir.$i/usage/*
	chmod 0660 $htdir.$i/usage/*

done

echo "Done"
