<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: squal_pre.php,v 1.2 2003/11/13 11:29:23 helix Exp $


/*


	Drastically simplified version of pre.php

	Sets up database connection and session

	###
	###  You cannot call HTML, user, group or related functions
	###

*/

require ('/etc/sourceforge/local25.inc');
require('database.php');
require('session.php');
require('Error.class');
require('User.class');
require('utils.php');

//plain text version of exit_error();
require('squal_exit.php');

//needed for logging / logo
require('browser.php');

$sys_datefmt = "m/d/y H:i";

// #### Connect to db

db_connect();

if (!$conn) {
	exit_error("Could Not Connect to Database",db_error());
}

//require('logger.php');

// #### set session

session_set();

?>
