#!/bin/bash
#ident @(#)set_maintenance_www.sh	1.0 04/03/23 

echo ""
echo "Restart Web Servers for Maintenance: "

if [ ! -f /etc/httpd/httpd.conf.save4maintenance ] ; then
	#
	# Save apache production config files
	#
	cp /etc/httpd/httpd.conf /etc/httpd/httpd.conf.save4maintenance
	cp /etc/httpd/access.conf /etc/httpd/access.conf.save4maintenance
	#
	# Activate apache maintenance config files
	#
	cp /etc/httpd/httpd.conf.maintenance /etc/httpd/httpd.conf
	cp /etc/httpd/access.conf.maintenance /etc/httpd/access.conf
	#
	# Apache is broken and won't restart
	#
	/etc/init.d/apache stop
	sleep 5
	/etc/init.d/apache stop
	sleep 20
	/etc/init.d/apache start
else
	echo "Web Server already started for Maintenance"
fi

echo "Done"
