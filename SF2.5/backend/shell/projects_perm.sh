#!/bin/bash

echo ""
echo "Set permissions for projects: "

for i in `cd /home/groups ; ls | grep -v lost+found | grep -v quota.group | grep -v ^ftp$` ; do
	echo ""
	echo "Set permissions of directories for project $i"
	find /home/groups/$i -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
	find /home/groups/$i -prune ! -perm 2555 -exec chmod u=rx,g=rx,g+s,o=rx {} +

	echo "Set permissions of cgi directory for project $i"
	find /home/groups/$i/cgi-bin -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
	find /home/groups/$i/cgi-bin -prune ! -perm 2575 -exec chmod u=rx,g=rwx,g+s,o=wx {} +
	
	echo "Set permissions of htdocs directory for project $i"
	find /home/groups/$i/htdocs -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
	find /home/groups/$i/htdocs -prune ! -perm 2575 -exec chmod u=rx,g=rwx,g+s,o=rx {} +

	echo "Set permissions of log directory for project $i"
	find /home/groups/$i/log -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
        find /home/groups/$i/log -prune ! -perm 2755 -exec chmod u=rwx,g=rx,g+s,o=rx {} +
	find /home/groups/$i/log -type f ! -perm 0644 -exec chmod u=rw,g=r,o=r

	if [ -d /home/groups/$i/htdocs/usage ] ; then
		echo "Set permissions of usage directory for project $i"
		find /home/groups/$i/htdocs/usage \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
        	find /home/groups/$i/htdocs/usage -prune ! -perm 2755 -exec chmod u=rwx,g=rx,g+s,o=rx {} +
		find /home/groups/$i/htdocs/usage -type f ! -perm 0644 -exec chmod u=rw,g=r,o=r {} +
	fi

	if [ -d /home/groups/$i/vhost ] ; then
		find /home/groups/$i/vhost -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
		find /home/groups/$i/vhost -prune ! -perm 2555 -exec chmod u=rx,g=rx,g+s,o=rx {} +

		for k in `cd /home/groups/$i/vhost ; ls` ; do
			echo "Set permissions of vhost $k directory for project $i"
			find /home/groups/$i/vhost/$k -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
                	find /home/groups/$i/vhost/$k -prune ! -perm 2555 -exec chmod u=rx,g=rx,g+s,o=rx {} +

			echo "Set permissions of cgi-bin directory of vhost $k for project $i"
			find /home/groups/$i/vhost/$k/cgi-bin -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
                        find /home/groups/$i/vhost/$k/cgi-bin -prune ! -perm 2575 -exec chmod u=rx,g=rwx,g+s,o=rx {} +
			
			echo "Set permissions of htdocs directory of vhost $k for project $i"
                        find /home/groups/$i/vhost/$k/htdocs -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
                        find /home/groups/$i/vhost/$k/htdocs -prune ! -perm 2575 -exec chmod u=rx,g=rwx,g+s,o=rx {} +

			echo "Set permissions of log directory of vhost $k for project $i"
                        find /home/groups/$i/vhost/$k/log -prune \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
                        find /home/groups/$i/vhost/$k/htdocs -prune ! -perm 2755 -exec chmod u=rwx,u=rx,g+s,o=rx {} +
			find /home/groups/$i/vhost/$k/htdocs -type f ! -perm 0644 -exec chmod u=rw,g=r,o=r {} +

			if [ -d /home/groups/$i/vhost/$k/htdocs/usage ] ; then
				echo "Set permissions of usage directory of vhost $k for project $i"
				find /home/groups/$i/vhost/$k/htdocs/usage \( ! -user $i -o ! -group $i \) -exec chown -h $i:$i {} +
                		find /home/groups/$i/vhost/$k/htdocs/usage -prune ! -perm 2755 -exec chmod u=rwx,g=rx,g+s,o=rx {} +
                		find /home/groups/$i/vhost/$k/htdocs/usage -type f ! -perm 0644 -exec chmod u=rw,g=r,o=r {} +
			fi
		done
	fi
done

echo "Done"
