<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.2 2004/01/13 13:15:25 helix Exp $

require('pre.php');
require('screenshots_utils.php');

if ($group_id) {
	screenshots_header(array('title'=>'Screenshots'));

	echo "<h2>Screenshots</h2>\n";

	$sql="SELECT id,description FROM db_images WHERE group_id='$group_id'";
	$result=db_query($sql);
	$rows=db_numrows($result);
	if ($rows < 1) {
		echo "<h2>No Screenshots Found";
		echo " For ".group_getname($group_id)."</h2>\n";
	} else {
		for ($j = 0; $j < $rows; $j++) { 
			echo '
			<a href="/dbimage.php?id='.db_result($result, $j, 'id').'">'.
			'<img src="/images/ic/frame_image.png" border="0"> '.
				db_result($result, $j, 'description').'</a>';
			echo '<br>
			';
		}
	}

	screenshots_footer(array());
} else {
	exit_no_group();
}

?>
