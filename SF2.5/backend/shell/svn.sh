#!/bin/bash
#ident @(#)svn.sh	1.0 04/05/25 

PATH=$PATH:/opt/csw/bin
mkdir -p /tmp/.xxzzy

uname=`uname`

echo ""
echo "Checking Subversion Directories: "

for i in `cd /svnroot/repos ; ls` ; do
	echo "Project: $i"

	if [ ."$uname" = .Linux ]; then

	# Linux does not have a POSIX find....

		find /svnroot/repos/$i ! -user wwwrun -print0 | xargs -0 chown -h wwwrun /tmp/.xxzzy

	else

	# For any other OS assume a POSIX find

		find /svnroot/repos/$i ! -user wwwrun -exec chown -h wwwrun {} \;

	fi

	chmod o-w /svnroot/repos/$i/db/*
	
done

echo "Done"
