<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: calendar.php,v 1.2 2003/11/13 11:29:25 helix Exp $

require "pre.php";    // Initial db and session library, opens session

$HTML->header(array(title=>"Project Management: Add Task"));

$timedate = time();

?> 
<p>&nbsp;</p>

<table border="0" width="800">
<tr>  
	<td width="800">
	<pre>
<?php
	$cal = `/usr/bin/cal -y`;
	print ("$cal");
?>
	</pre>
	</td>

</tr>

</table>

<?php
$HTML->footer(array());

?>
