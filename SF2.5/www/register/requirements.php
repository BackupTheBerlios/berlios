<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: requirements.php,v 1.2 2003/11/13 11:29:26 helix Exp $

require "pre.php";    // Initial db and session library, opens session
session_require(array(isloggedin=>1));
$HTML->header(array(title=>"Project Requirements"));
?>

<H2>Step 1: <?php print $sys_default_name ?>  Services & Requirements (Service Specific Rules)</H2>

<p>
We are now offering a full suite of services for <?php print $sys_default_name ?> projects. If
you haven't already, please be sure to browse the most recent revision of
the <?php print $sys_default_name ?> Services.
</p>

<p>
<b>Use of Project Account</b>
</p>

<p>
The space given to you on this server is given for the expressed purpose of
Open Source development or, in the case of web sites, the advancement of
Open Source. For more information, please read the <?php print $sys_default_name ?> Terms of
Service ("Terms of Service").
</p>

<p>
<b>Creative Freedom</b>
</p>

<p>
It is our intent to allow you creative freedom on your project. This is not
a totally free license, though. For our legal protection and yours there are
limits. Please know, however that we too are Open Source developers that
value our freedom. Details about these restrictions are described in the
Terms of Service.
</p>

<p>
<b>Advertisements</b>
</p>

<p>
You may not place any revenue-generating advertisements on a site hosted at
<?php print $sys_default_name ?>.
</p>

<p>
<b><?php print $sys_default_name ?> Link</b>
</p>

<p>
If you host a web site at <?php print $sys_default_name ?>, you must place one of our approved
graphic images on your site with a link back to <?php print $sys_default_name ?>. The graphic may
either link to the main <?php print $sys_default_name ?> site or to your project page on
<?php print $sys_default_name ?>. We will leave placement up to you.  For information about how
to insert a <?php print $sys_default_name ?> logo which will track your pageviews, please read
the <?php print $sys_default_name ?> documentation.
</p>

<p>
<b>Open Source/Rights to Code</b>
</p>

<p>
You will be presented with a choice of Open Source approved licenses for
your project. You will still own the code, but all of these licenses also
allow us to make your code available to the general public. Although you may
choose to stop hosting your project with us, the nature of these licenses
will allow us to continue to make your code available. Details about content
ownership are described in the Terms of Service.
</p>

<p>
If you wish to use another license that is not currently approved by the
Open Source Initiative, let us know and we will review these requests on a
case-by-case basis.
</p>

<p>
It is our intent to provide a permanent home for all versions of your code.
We do reserve the right, however, to terminate your project if there is due
cause, in accordance with the Terms of Service.
</p>

<BR><H3 align=center><a href="tos.php">Step 2: Terms of Service Agreement</a></H
3>

<?php
$HTML->footer(array());
?>

