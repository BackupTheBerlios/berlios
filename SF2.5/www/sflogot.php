<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: sflogot.php,v 1.1 2003/11/13 11:29:20 helix Exp $

require_once ('squal_pre.php');

/*
	Determine group
*/

if ($group_id) {
	$log_group=$group_id;
} else {
	$log_group=0;
}

$res_logger = db_query ("INSERT INTO activity_log (day,hour,group_id,browser,ver,platform,time,page,type) ".
	"VALUES (".date('Ymd', mktime()).",'".date('H', mktime())."','$log_group','". browser_get_agent() ."','". browser_get_version() ."','". browser_get_platform() ."','". time() ."','$PHP_SELF','1');");
if (!$res_logger) {
	echo "An error occured in the logger.\n";
	echo db_error();
	exit;
}

// output image
header("Content-Type: image/png");
header("Cache-Control: no-cache, must-revalidate");
//header("Pragma: no-cache");

//if (!$group_id) {
//	echo 'xxxxx NO GROUP ID xxxxxxx';
//	exit;
//}

//if ($type == 1) {
	echo readfile ($sys_urlroot.'images/berliOS_small_logo_trans.png');
//}  else { // default
//	echo readfile ($sys_urlroot.'images/berliOS_small_logo_trans.png');
//} 

?>
