<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: about.php,v 1.2 2003/11/13 11:29:20 helix Exp $

require "pre.php";    
$HTML->header(array(title=>"About $sys_default_name Site"));
?>

<P>
<h2>About <?php echo $sys_default_name ?> Site</h2>

<B>Mission Statement</b><br>
BerliOS Developers Site's  mission is to enrich the Open Source community by providing 
a centralized place for Open Source Developers to control and
manage Open Source Software Development.
<br><br>

<B>People</b><br>
The BerliOS <a href="staff.php">staff</a> has worked hard to make 
BerliOS a reality, here's your chance to meet the people behind the site.
<br><br>

<b>Thanks</b><br>
We owe a lot of thanks to the people that wrote 
the <a href="/docman/display_doc.php?docid=28&group_id=2">Software</a> that runs <?php echo $sys_default_name ?>.
We'd also like to give <a href="thanks.php">Kudos</a> to the following people and organizations for helping make BerliOS happen.
<br><br>

<b>More Information</b><br>
If you have further questions, they might be answered in our
<a href="/docs/site/">site documentation</A> or
<A href="/docman/display_doc.php?docid=27&group_id=2">hardware list</A>.
<!---
<A href="/docs/site/hardware.php">hardware list</A>.
or <A href="/docs/site/faq.php">frequently asked questions</A>.
You might also look at <B><A href="/docs/site/news.php"><?php echo $sys_default_name ?> in the News</A></B>.
--> 
<?php
$HTML->footer(array());

?>
