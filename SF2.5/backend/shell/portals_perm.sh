#!/bin/bash

echo ""
echo "Set permissions for portals: "

logdir=/var/log/httpd
htdir=/usr/local/httpd/htdocs

for i in berlios devel forum news wiki ; do
        echo "Set owner and permissions for usage directory of $i"
	chown berlios:berlios $htdir.$i/usage
	chmod 0775 $htdir.$i/usage

	echo "Set owner and permissions for usage directory files of $i"
	chown berlios:berlios $htdir.$i/usage/*
	chmod 0664 $htdir.$i/usage/*

done

echo "Done"
