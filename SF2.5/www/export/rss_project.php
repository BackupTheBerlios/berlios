<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: rss_project.php,v 1.4 2005/02/24 18:10:27 helix Exp $

require "pre.php";    
$HTML->header(array(title=>"Project Exports"));
?>
<h2>XML Exports</h2>

<P><?php print $GLOBALS[sys_default_name] ?> data is exported in a variety of standard formats. Many of
the export URLs can also accept form/get data to customize the output. All
data generated by these pages is realtime.

<h3>News Data</h3>
<p>
To get Project News or New Project Releases of a specific project use the Links below.
<UL>
<LI><A href="rss_bsnews.php?group_id=<?php echo $group_id; ?>">BerliOS Developer Project News</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
<A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
<LI><A href="rss_bsnewreleases.php?group_id=<?php echo $group_id; ?>">BerliOS Developer New Project Releases</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
<A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
</UL>
<UL>
<LI><A href="rss20_bsnews.php?group_id=<?php echo $group_id; ?>">BerliOS Developer Project News</A>
(<A href="http://blogs.law.harvard.edu/tech/rss">RSS 2.0</A>)
<LI><A href="rss20_bsnewreleases.php?group_id=<?php echo $group_id; ?>">BerliOS Developer New Project Releases</A>
(<A href="http://blogs.law.harvard.edu/tech/rss">RSS 2.0</A>)
</UL>

<h3>Project Information</h3>
<p>
Exports which provide en masse access to data which otherwise
project property, require project admin privilege.
</p>

<UL>
<LI><A href="nitf_bsforums.php?group_id=<?php echo $group_id; ?>">Project Forums</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_forum_0.1.dtd.txt">&lt;bs_forum_0.1.dtd&gt;</A>)
<LI><A href="bug_dump.php?group_id=<?php echo $group_id; ?>">Project Bugs</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_bugs_0.1.dtd.txt">&lt;bs_bugs_0.1.dtd&gt;</A>)
<LI><A href="support_dump.php?group_id=<?php echo $group_id; ?>">Project Support</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_support_0.1.dtd.txt">&lt;bs_support_0.1.dtd&gt;</A>)
<LI><A href="patch_dump.php?group_id=<?php echo $group_id; ?>">Project Patches</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_patches_0.1.dtd.txt">&lt;bs_patches_0.1.dtd&gt;</A>)
<LI><A href="task_dump.php?group_id=<?php echo $group_id; ?>">Project Tasks</A>
(<A href="http://www.w3.org/XML">XML</A>)
</UL>

<?php
$HTML->footer(array());
?>
