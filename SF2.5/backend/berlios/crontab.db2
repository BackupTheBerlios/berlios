#!/bin/bash
#
# crontab entries for BerliOS Postgres Database Server:
# db2.berlios.de
#
# four times a day every 6 hours
#
55 23,5,11,17 * * * /usr/local/httpd/SF2.5/backend/berlios/cron.6hour.db2
# 
# daily at 01:10
# after backend scripts has finished!!!
#
10 03  *  *  * /usr/local/httpd/SF2.5/backend/berlios/cron.daily.db2
