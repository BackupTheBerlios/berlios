<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: rss_project.php,v 1.1 2003/11/13 11:29:22 helix Exp $

require "pre.php";    
$HTML->header(array(title=>"Project Exports Available"));
?>
<P><?php print $GLOBALS[sys_default_name] ?> data is exported in a variety of standard formats. Many of
the export URLs can also accept form/get data to customize the output. All
data generated by these pages is realtime.

<h3>
XML Exports
</h3>

<h4>Project Information</h4>
<p>
Exports which provide en masse access to data which otherwise
project property, require project admin privilege.
</p>

<UL>
<LI><A href="nitf_bsforums.php?group_id=<?php echo $group_id; ?>">Project Forums</A>
(<A href="bs_forum_0.1.dtd.txt">&lt;bs_forum_0.1.dtd&gt;</A>)
<LI><A href="bug_dump.php?group_id=<?php echo $group_id; ?>">Project Bugs</A>
(<A href="bs_bugs_0.1.dtd.txt">&lt;bs_bugs_0.1.dtd&gt;</A>)
<LI><A href="support_dump.php?group_id=<?php echo $group_id; ?>">Project Support</A>
(<A href="bs_support_0.1.dtd.txt">&lt;bs_support_0.1.dtd&gt;</A>)
<LI><A href="patch_dump.php?group_id=<?php echo $group_id; ?>">Project Patches</A>
(<A href="bs_patches_0.1.dtd.txt">&lt;bs_patches_0.1.dtd&gt;</A>)
</UL>

<?php
$HTML->footer(array());
?>
