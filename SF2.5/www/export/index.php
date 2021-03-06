<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.6 2005/02/10 16:03:41 helix Exp $

require "pre.php";    
$HTML->header(array(title=>"Exports Available"));
?>
<P><?php print $GLOBALS[sys_default_name] ?> data is exported in a variety of standard formats. Many of
the export URLs can also accept form/get data to customize the output. All
data generated by these pages is realtime.

<h3>
XML Exports
</h3>

<h4>Site Information</h4>
<UL>
<LI><A href="rss_bsprojects.php"><?php print $GLOBALS[sys_default_name] ?> Full Project Listing</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
 <A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
<LI><A href="rss20_bsprojects.php"><?php print $GLOBALS[sys_default_name] ?> Full Project Listing</A>
(<A href="http://blogs.law.harvard.edu/tech/rss">RSS 2.0</A>)
<LI><A href="trove_tree.php"><?php print $GLOBALS[sys_default_name] ?> Trove Categories Tree</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="trove_tree_0.1.dtd">&lt;trove_tree_0.1.dtd&gt;</A>)
</UL>

<h4>News Data</h4>
<p>
You will get Project News or New Project Releases of a specific project, if an additional <tt>?group_id=</tt> parameter with id of  project is defined within the Links below.
<UL>
<LI><A href="rss_bsnews.php"><?php print $GLOBALS[sys_default_name] ?> Front Page News/Project News</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
<A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
<LI><A href="rss_bsnewreleases.php"><?php print $GLOBALS[sys_default_name] ?> New Releases/New Project Releases</A>
(<A href="http://my.netscape.com/publish/formats/rss-spec-0.91.html">RSS 0.91</A>,
<A href="http://my.netscape.com/publish/formats/rss-0.91.dtd">&lt;rss-0.91.dtd&gt;</A>)
</UL>

<UL>
<LI><A href="rss20_bsnews.php"><?php print $GLOBALS[sys_default_name] ?> Front Page News/Project News</A>
(<A href="http://blogs.law.harvard.edu/tech/rss">RSS 2.0</A>)
<LI><A href="rss20_bsnewreleases.php"><?php print $GLOBALS[sys_default_name] ?> New Releases/New Project Releases</A>
(<A href="http://blogs.law.harvard.edu/tech/rss">RSS 2.0</A>)
</UL>

<h4>Project Information</h4>
<p>
All links below require <tt>?group_id=</tt> parameter with id of specific
group. Exports which provide en masse access to data which otherwise
project property, require project admin privilege.
</p>

<UL>
<LI><A href="nitf_bsforums.php">Project Forums</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_forum_0.1.dtd.txt">&lt;bs_forum_0.1.dtd&gt;</A>)
<LI><A href="bug_dump.php">Project Bugs</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_bugs_0.1.dtd.txt">&lt;bs_bugs_0.1.dtd&gt;</A>)
<LI><A href="support_dump.php">Project Support</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_support_0.1.dtd.txt">&lt;bs_support_0.1.dtd&gt;</A>)
<LI><A href="patch_dump.php">Project Patches</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_patches_0.1.dtd.txt">&lt;bs_patches_0.1.dtd&gt;</A>)
<LI><A href="task_dump.php">Project Tasks</A>
(<A href="http://www.w3.org/XML">XML</A>, <A href="bs_tasks_0.1.dtd.txt">&lt;bs_tasks_0.1.dtd&gt;</A>)
</UL>

<h3>
HTML Exports
</h3>
<p>
While XML data allows for arbitrary processing and formatting, many
projects will find ready-to-use
<a href="http://developer.berlios.de/docman/display_doc.php?docid=215&group_id=2">
HTML exports</a> suitable for
their needs.
</p>

<?php
$HTML->footer(array());
?>
