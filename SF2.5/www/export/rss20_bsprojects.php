<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: rss20_bsprojects.php,v 1.1 2005/02/24 18:10:27 helix Exp $

// ## export BerliOS project list in RSS 2.0

include "pre.php";
include "rss_utils.inc";
header("Content-Type: text/xml");
print '<?xml version="1.0"?>
<rss version="2.0">
';
$res = db_query(
	 'SELECT group_id,group_name,unix_group_name,homepage,short_description,register_time '
	.'FROM groups '
	.'WHERE is_public=1 AND status=\'A\' '
        .'ORDER BY group_id',$limit);

rss20_dump_project_result_set($res,$GLOBALS[sys_default_name].' Full Project Listing');
?>
</rss>
