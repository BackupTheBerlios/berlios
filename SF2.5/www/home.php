<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: home.php,v 1.2 2003/11/13 11:29:20 helix Exp $

$expl_pathinfo = explode('/',$PATH_INFO);

Header ('Location: /projects/'.$expl_pathinfo[1].'/');

?>
