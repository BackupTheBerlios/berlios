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

cd $TOP_LEVEL
# the following assume external file structures
ln -s ../phpAdsNew_2beta5 phpAdsNew
ln -s ../SF1.5/www sf
ln -s ../ads ads
