#!/bin/bash
#ident @(#)apache_production.sh	1.0 04/03/23 

echo ""
echo "Restart Web Server for Production: "

if [ -f /etc/apache/httpd.conf.save4maintenance ] ; then
	#
	# Activate apache production config files
	#
	cp /etc/apache/httpd.conf.save4maintenance /etc/apache/httpd.conf
	cp /etc/apache/access.conf.save4maintenance /etc/apache/access.conf
	#
	# Remove saved apache production config files
	#
	rm /etc/apache/httpd.conf.save4maintenance
	rm /etc/apache/access.conf.save4maintenance 
	#
	# Apache is broken and won't restart
	#
	/etc/init.d/apache stop
	sleep 5
	/etc/init.d/apache stop
	sleep 20
	/etc/init.d/apache start
else
	echo "Web Server aready started for Production"
fi

echo "Done"
