<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: suspended.php,v 1.2 2003/11/13 11:29:21 helix Exp $

require "pre.php";    
$HTML->header(array(title=>"Suspended Account"));
?>

<P><B>Suspended Account</B>

<P>Your account has been suspended. If you have questions regarding your suspension,
please email <A href="mailto:staff@<?php echo $GLOBALS['sys_default_domain']; ?>">staff@<?php echo $GLOBALS['sys_default_domain']; ?></A>.
Inquiries through other channels will be directed to this address.

<?php
$HTML->footer(array());

?>
