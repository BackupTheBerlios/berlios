<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id

/*
	Screenshots System
	By Lutz Henckel, BerliOS, 02/2003
*/

function screenshots_header($params) {
	global $DOCUMENT_ROOT,$HTML,$group_id;

	$params['toptab']='screenshots';
	$params['group']=$group_id;

	/*
		Show horizontal links
	*/
	if ($group_id) {
		site_project_header($params);
	} else {
		$HTML->header($params);
		echo '
			<H2>Screenshots</H2>';
	}
	echo '<P><B>';
	echo '<A HREF="/project/admin/editimages.php?group_id='.$group_id.'">Admin</A></B>';
	echo '<P>';
}

function screenshots_footer($params) {
	GLOBAL $HTML;
	$HTML->footer($params);
}

?>
