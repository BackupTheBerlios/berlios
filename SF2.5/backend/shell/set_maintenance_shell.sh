#!/bin/bash
#ident @(#)set_maintenance_shell.sh        1.0 04/03/23

echo ""
echo "Restart Shell Servers for Maintenance: "

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

