<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: filenotes.php,v 1.1 2003/11/12 16:09:03 helix Exp $

if ((!$group_id) && $form_grp) $group_id=$form_grp;

header ("Location: showfiles.php?group_id=$group_id");

?>
