<?php
//
// BerliOS: Fostering Open Source Development
// Copyright 2000-2004 (c) The BerliOS Crew
// http://www.berlios.de
//
// $Id $

function wiki_header($params) {
	global $group_id;

	//required for site_project_header
	$params['group']=$group_id;
	$params['toptab']='wiki';

	$project=project_get_object($group_id);

	if (!$project->usesWiki()) {
		exit_error('Error','This Project Has Turned Off Wikis');
	}

	site_project_header($params);
}

function wiki_footer($params) {
	site_project_footer($params);
}

?>
