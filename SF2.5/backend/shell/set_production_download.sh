#!/bin/bash
#ident @(#)set_production_download.sh	1.0 04/03/23 

echo ""
echo "Restart Download/FTP/Rsync Servers for Production: "

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
#
# Start proftpd
#
/etc/init.d/proftpd start
#
# Start rsyncd
#
/etc/init.d/rsyncd start
#
# Reload sshd to allow user login
#
if [ -f /etc/ssh/sshd_config.save4maintenance ] ; then
        cp /etc/ssh/sshd_config.save4maintenance /etc/ssh/sshd_config
        /etc/init.d/sshd force-reload
else
        echo "Sshd already started for Production"
fi

echo "Done"
