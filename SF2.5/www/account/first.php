<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: first.php,v 1.2 2003/11/13 11:29:21 helix Exp $

require "pre.php";    
site_user_header(array(title=>"Welcome to ".$sys_default_name));
?>

<P><B>Welcome to <?php echo $sys_default_name ?>!</B>

<P>You are now a registered user on <?php echo $sys_default_name ?>, the online development
environment for Open Source projects.

<P>As a registered user, you can participate fully in the activities
on the site.
You may now post messages to the project message forums, post bugs
for software in <?php echo $sys_default_name ?>, sign on as a project developer, or even
start your own project.

<P>You should take some time to read through the
<A href="/docs/site/"><b>Site Documentation</b></A> so that you may take
full advantage of <?php echo $sys_default_name ?>.

<P>Enjoy the site, and please provide us with feedback on ways
that we can improve <?php echo $sys_default_name ?>.

<P>--the <?php echo $sys_default_name ?> staff

<?php
site_user_footer(array());

?>
