<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.2 2003/11/13 11:29:24 helix Exp $

require "pre.php";    
$HTML->header(array(title=>"Mirrors of Other Sites"));
?>
<H2>Site Mirrors</H2>
<HR NoShade>

<P><?php print $GLOBALS['sys_default_name'] ?> provides high-bandwidth mirrors for several other projects. Our mirror server is a HP9000 L-Class System with 2 PA-RISC 8500/360 MHz CPUs, with 1 GB RAM and 250 GB of formatted storage on a FC 60 Raid controller. Its switched 100Mbit connection feeds directly to FOKUS routers and a 155Mbit line.

<P>Following is a partial mirror list. All mirrors can be found at:
<UL><LI><B><A href="http://<?php print $GLOBALS['sys_download_host'] ?>/pub/mirrors/">http://<?php print $GLOBALS['sys_download_host'] ?>/pub/mirrors/</A></B>
(preferred)
<LI><B><A href="ftp://<?php print $GLOBALS['sys_ftp_host'] ?>/pub/mirrors/">ftp://<?php print $GLOBALS['sys_ftp_host'] ?>/pub/mirrors/</A></B>
</UL>

<P>To report problems with these mirrors, or to suggest another mirror,
send email to <A href="mailto:berlios-admin@berlios.de">berlios-admin@berlios.de</A>.

<HR>

<P><B><A href="http://<?php print $GLOBALS['sys_download_host'] ?>/pub/mirrors/debian/">Debian GNU/Linux</A></B> -
Debian is the largest GNU/Linux distribution completely maintained by a base of volunteers. More than 500 developers currently work to make Debian the most complete distribution available today.

<P><?php html_image('/images/others/suse_g_sm.png','77','55',array(align=>'right')); ?>
<B><A href="http://<?php print $GLOBALS['sys_download_host'] ?>/pub/mirrors/suse/">SuSE Linux</A></B> -
SuSE Linux provides a comprehensive set of software, and features the YaST2 setup and configuration tool with automatic hardware detection for PCI components and a menu-driven graphical interface. For the experienced SuSE Linux user, the familiar YaST1, with all its features, continues to be available for updates and system administration.

<P><B><A href="http://xfree86.berlios.de">XFree86</A></B> -
XFree86 (<a href="ftp://ftp.berlios.de/pub/xfree86">ftp://ftp.berlios.de/pub/xfree86</a>)
is a freely redistributable implementation of the X Window
System that runs on UNIX(R) and UNIX-like operating systems (and OS/2). 
The XFree86 Project has traditionally focused on Intel x86-based
platforms (which is where the `86' in our name comes from), 
but our current release also supports other platforms.

<P><B><A href="http://linuxgazette.berlios.de">Linux Gazette</A></B> -
Linux Gazette (<a href="ftp://ftp.berlios.de/pub/linuxgazette">ftp://ftp.berlios.de/pub/linuxgazette</a>)
a member of the Linux Documentation Project, is an on-line WWW publication dedicated to two simple ideas:
making Linux just a little more fun and sharing ideas and discoveries.

<P><B><A href="http://linuxfocus.berlios.de">LinuxFocus</A></B> -
LinuxFocus is the first really international Linux Magazine.
It is managed and produced by Linux volunteers, fans and developers.
LF is free and available on the web all over the world.

<P><B><A href="http://apache.berlios.de">Apache</A></B> -
The Apache Software Foundation (<a href="ftp://ftp.berlios.de/pub/apache">ftp://ftp.berlios.de/pub/apache</a>)
provides support for the Apache community of open-source software projects.

<P><B><A href="http://opensource.berlios.de">Open Source Initiative (OSI)</A></B> -
The Open Source Initiative (OSI) is a non-profit corporation dedicated to managing and promoting
the Open Source Definition for the good of the community.

<P><B><A href="http://tldp.berlios.de">The Linux Documentation Project (TLDP)</A></B> -
The Linux Documentation Project is a loosely knit team of volunteers who provide documenation for many aspects of Linux. There are several forms of documentation: Guides, HOWTOs, man pages and FAQs.

<?php
$HTML->footer(array());
?>
