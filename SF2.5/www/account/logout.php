<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: logout.php,v 1.1 2003/11/12 16:09:03 helix Exp $

require ('pre.php');    

db_query("DELETE FROM session WHERE session_hash='$session_hash'");

session_cookie('session_hash','');
session_redirect('/');

?>
