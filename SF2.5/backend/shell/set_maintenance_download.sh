#!/bin/bash
#ident @(#)set_maintenance_download.sh	1.0 04/03/23 

echo ""
echo "Restart Download/FTP/Rsync Servers for Maintenance: "

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
#
# Stop proftpd
#
/etc/init.d/proftpd stop
#
# Stop rsyncd
#
/etc/init.d/rsyncd stop
#
# Reload sshd to permit user login
#
if [ ! -f /etc/ssh/sshd_config.save4maintenance ] ; then
        cp /etc/ssh/sshd_config /etc/ssh/sshd_config.save4maintenance
        cp /etc/ssh/sshd_config.maintenance /etc/ssh/sshd_config
        /etc/init.d/sshd force-reload
else
        echo "Sshd already started for Maintenance"
fi

echo "Done"
