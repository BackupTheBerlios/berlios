#!/bin/bash
#ident @(#)set_production_shell.sh        1.0 04/03/23

echo ""
echo "Restart Shell Servers for Production: "

#
# Reload sshd to permit user login
#
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

