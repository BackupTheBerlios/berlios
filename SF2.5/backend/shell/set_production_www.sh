#!/bin/bash
#ident @(#)set_production_www.sh	1.0 04/03/23 

echo ""
echo "Restart Web Servers for Production: "

if [ -f /etc/httpd/httpd.conf.save4maintenance ] ; then
	#
	# Activate apache production config files
	#
	cp /etc/httpd/httpd.conf.save4maintenance /etc/httpd/httpd.conf
	cp /etc/httpd/access.conf.save4maintenance /etc/httpd/access.conf
	#
	# Remove saved apache production config files
	#
	rm /etc/httpd/httpd.conf.save4maintenance
	rm /etc/httpd/access.conf.save4maintenance 
	#
	# Apache is broken and won't restart
	#
	/etc/init.d/apache stop
	sleep 5
	/etc/init.d/apache stop
	sleep 20
	/etc/init.d/apache start
else
	echo "Web Server already started for Production"
fi

echo "Done"
