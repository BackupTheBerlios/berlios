#!/bin/sh
#
# Used to re-create the required links in the checkout repository
#

# this should be run in the top level directory, i.e. the one in
# which this file is located.
#
# Author: Gerrit Riessen
#
ln -s index.php.de index.php
ln -s berlios.php berlios.css
cd about
ln -s ../include include
cd ../contact
ln -s ../include include
cd partners/include
ln -s ../../include/backend2newslist.php backend2newslist.php

# the following assume external file structures
ln -s ../phpAdsNew_2beta5 phpAdsNew
ln -s ../phpMyAdmin-2.2.0 phpMyAdmin
ln -s ../SF1.5/www sf
ln -s ../ads ads
ln -s ../htdocs suse
