#!/bin/sh

cd /usr/local/httpd/SF2.5/utils/download

## parse each logfile set 
##./stats_ftp_logparse.pl $*
./stats_http_logparse.pl $*

## and then build the agregates
./stats_agr_filerelease.pl $*

## after which, we update the nightly agregates
./stats_nightly_filerelease.pl $*

