<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: history.php,v 1.3 2004/01/13 13:15:25 helix Exp $

require "pre.php";    
require ($DOCUMENT_ROOT.'/project/admin/project_admin_utils.php');

session_require(array('group'=>$group_id,'admin_flags'=>'A'));

project_admin_header(array('title'=>'Project History','group'=>$group_id));
?>

<H2>Project History</H2>
<P>
This history will show who made significant changes to your project and when.
<P>
<?php
echo show_grouphistory($group_id);

project_admin_footer(array());
?>
