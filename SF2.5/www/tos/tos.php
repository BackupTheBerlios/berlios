<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: tos.php,v 1.2 2003/11/13 11:29:29 helix Exp $

require("pre.php");  // Initial db and session library, opens session

$HTML->header( array( title=>"Terms of Service Agreement" ) );

include("tos_text.php");

$HTML->footer( array() );

?>

