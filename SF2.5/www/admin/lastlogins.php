<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: lastlogins.php,v 1.3 2004/02/08 15:53:41 helix Exp $

require "pre.php";    
session_require(array('group'=>'1','admin_flags'=>'A'));

$res_logins = db_query("SELECT session.user_id AS user_id,"
	. "session.ip_addr AS ip_addr,"
	. "session.time AS time,"
	. "users.user_name AS user_name FROM session,users "
	. "WHERE session.user_id=users.user_id AND "
	. "session.user_id>0 AND session.time>0 ORDER BY session.time DESC",50);
if (db_numrows($res_logins) < 1) exit_error("No records found","There must be an error somewhere.");

$HTML->header(array('title'=>"Last Logins"));

print '<P><B>Most Recent Sessions with Logins</B>';
print "\n<P>";
print '<TABLE width=100% cellpadding=0 cellspacing=0 border=0>';

while ($row_logins = db_fetch_array($res_logins)) {
	print '<TR>';
	print "<TD><a href=\"http://".$GLOBALS['sys_default_host']."/users/$row_logins[user_name]\">$row_logins[user_name]</a></TD>";
	print "<TD>$row_logins[ip_addr]</TD>";
	print "<TD>" . date("Y/m/d G:i",$row_logins['time']) . "</TD>";
	print '</TR>';
}

print '</TABLE>';

$HTML->footer(array());

?>
