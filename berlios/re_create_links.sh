#!/bin/sh
#
# Used to re-create the required links in the checkout repository
#

# this should be run in the top level directory, i.e. the one in
# which this file is located.
#
# Author: Gerrit Riessen
#
TOP_LEVEL=`pwd`

ln -s index.php.de index.php
ln -s berlios.php berlios.css
cd $TOP_LEVEL/about
ln -s ../include include
cd $TOP_LEVEL/contact
ln -s ../include include
cd $TOP_LEVEL/partners/include
ln -s ../../include/backend2newslist.php backend2newslist.php

cd $TOP_LEVEL
# the following assume external file structures
ln -s ../phpAdsNew_2beta5 phpAdsNew
ln -s ../phpMyAdmin-2.2.0 phpMyAdmin
ln -s ../SF1.5/www sf
ln -s ../ads ads
ln -s ../htdocs suse
