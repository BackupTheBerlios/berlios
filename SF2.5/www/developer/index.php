<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.2 2003/11/13 11:29:22 helix Exp $

/*
	Developer Info Page
	Written by dtype Oct 1999
*/

if (!$user_id) {
	$user_id=$form_dev;
}

require ('pre.php');

header("Location: /users/". user_getname($user_id) ."/");

?>
