#!/bin/bash

echo ""
echo "Set permissions for portals: "

htdir=/usr/local/httpd/htdocs

for i in berlios devel forum news ; do
	echo "Set owner and permissions for usage directory of $i"
	chown helix:berlios $htdir.$i/usage
	chmod 0755 $htdir.$i/usage
	chmod g+s $htdir.$i/usage

	echo "Set owner and permissions for usage directory files of $i"
	chown helix:berlios $htdir.$i/usage/*
	chmod 0644 $htdir.$i/usage/*
	chown helix:berlios $htdir.$i/usage/.htaccess
	chmod 0644 $htdir.$i/usage/.htaccess

done

echo "Done"
