<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: about_foundries.php,v 1.2 2003/11/13 11:29:20 helix Exp $

require "pre.php";    
$HTML->header(array(title=>"About Foundries"));
?>

<P>
<h2>About <?php echo $sys_default_name ?> Foundries</h2>

<?php echo $sys_default_name ?> Foundries serve as places for developers to congregate, share
expertise and news, get and give advice, and generally help each other
develop  better software faster (this is Open Source, after all).<br>

&nbsp;<br>

If you're interested in volunteering to help support or start a foundry, or
have suggestions, ideas, or gripes please
<a href="/contact.php" >contact <?php echo $sys_default_name ?></a>.

<h2>Foundries</h2>

<p>Here is a list of all foundries on the system.  Some are more
complete than others.  If you see a foundry you may want to contribute
to, contact the existing foundry admins for that foundry.

<?php
	$query = "SELECT group_name,unix_group_name ".
		 "FROM groups WHERE status='A' AND is_public='1' ".
		 " AND type='2' ORDER BY group_name ";
	$result = db_query($query);
	$rows = db_numrows($result);
	if (!$result || $rows < 1) {
		echo "<H2>No matches found</H2>";
		echo db_error();
	} else {
		echo "<UL>";
		for ($i=0; $i<$rows; $i++) {
			echo "\n<li><A HREF=\"/foundry/".db_result($result, $i, 'unix_group_name')."/\">".
				db_result($result, $i, 'group_name')."</A></li>";
		}
		echo "\n</UL>";
	}
?>

<?php
$HTML->footer(array());

?>
