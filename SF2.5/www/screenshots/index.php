<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.1 2003/11/13 11:29:26 helix Exp $

require('pre.php');
require('screenshots_utils.php');

screenshots_header(array('title'=>'Screenshots'));

echo "<h3>Screenshots</h3>\n";

if ($group_id) {
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
} else {
        echo "<h2>No Group Specified</h2>\n";
}

screenshots_footer(array());

?>
