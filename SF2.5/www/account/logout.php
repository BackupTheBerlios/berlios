<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: logout.php,v 1.2 2003/11/13 11:29:21 helix Exp $

require ('pre.php');    

db_query("DELETE FROM session WHERE session_hash='$session_hash'");

session_cookie('session_hash','');
session_redirect('/');

?>
