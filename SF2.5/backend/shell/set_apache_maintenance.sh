#!/bin/bash
#ident @(#)apache_maintenance.sh	1.0 04/03/23 

echo ""
echo "Restart Web Server for Maintenance: "

if [ ! -f /etc/apache/httpd.conf.save4maintenance ] ; then
	#
	# Save apache production config files
	#
	cp /etc/apache/httpd.conf /etc/apache/httpd.conf.save4maintenance
	cp /etc/apache/access.conf /etc/apache/access.conf.save4maintenance
	#
	# Activate apache maintenance config files
	#
	cp /etc/apache/httpd.conf.maintenance /etc/apache/httpd.conf
	cp /etc/apache/access.conf.maintenance /etc/apache/access.conf
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
