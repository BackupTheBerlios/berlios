#!/bin/bash

echo ""
echo "Set permissions for portals: "

htdir=/usr/local/httpd/htdocs

for i in berlios devel forum news ; do
	echo "Set permissions for usage directory for portal $i"
	find $htdir.$i/usage \( ! -user helix -o ! -group berlios \) -exec chown -h helix:berlios {} +
	find $htdir.$i/usage -prune ! -perm 2755 -exec chmod u=rwx,g=rx,g+s,o=rx {} +
	find $htdir.$i/usage -type f ! -perm 0644 -exec chmod u=rx,g=r,o=r {} +
done

echo "Done"
