<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: get_session_hash.php,v 1.2 2003/11/13 11:29:27 helix Exp $

require ('squal_pre.php');

/*

	MUST USE SSL

	params: $user, $pass

	returns: either valid session_hash or ERROR string

*/

if (!session_issecure()) {
	//force use of SSL for login
	echo 'ERROR - MUST USE SSL';
	exit;
}

$success=session_login_valid($user,$pass);

if ($success) {
	echo $session_hash;
} else {
	echo 'ERROR - '.$feedback;
}

?>
