<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: newprojectmail.php,v 1.1 2003/11/12 16:09:03 helix Exp $

require ('pre.php');
require('proj_email.php');

session_require(array('group'=>'1','admin_flags'=>'A'));

$HTML->header(array('title'=>"Project Intro email"));

send_new_project_email($group_id);

print "<P>Mail successfully sent.";

$HTML->footer(array());
?>