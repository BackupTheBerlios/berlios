<?php

require($DOCUMENT_ROOT.'/news/news_utils.php');
require('features_boxes.php');
require('cache.php');

//we already know $foundry is set up from the master page

$HTML->header(array('title'=>group_getname($group_id).' - Foundry','group'=>$group_id));

echo '<h2>Foundry: '.group_getname($group_id).'<h2>
';

echo'	<table cellspacing="0" cellpadding="5" border="0" width="100%">
	      <tr>
		<td align="left" valign="top">
';

echo html_dbimage($foundry->getLogoImageID());

echo '<p>
';

echo $foundry->getFreeformHTML1();

echo '<p>
';

/*

	News that was selected for display by the portal
	News items are chosen froma list of news in subprojects

*/

$HTML->box1_top('Foundry News', '#f4f5f7');
echo news_foundry_latest($group_id);
$HTML->box1_bottom();

/*

	Message Forums

*/

echo '<P>
';

$HTML->box1_top('Discussion Forums');

//$sql="SELECT * FROM forum_group_list WHERE group_id='$group_id' AND is_public='1';";

$sql="SELECT g.group_forum_id,g.forum_name, g.description, count(*) as total " //, max(date) as latest"		 
	." FROM forum_group_list g, forum f"
	." WHERE g.group_id='$group_id' AND g.is_public=1"
	." AND g.group_forum_id = f.group_forum_id"
	." group by g.group_forum_id, g.forum_name, g.description";


$result = db_query ($sql);

$rows = db_numrows($result);

if (!$result || $rows < 1) {

	echo '<h1>No forums found for '. $foundry->getUnixName() .'</h1>';

} else {

	/*
		Put the result set (list of forums for this group) into a column with folders
	*/

	for ($j = 0; $j < $rows; $j++) {
		echo '
			<a href="/forum/forum.php?forum_id='. db_result($result, $j, 'group_forum_id') .'">'.
			html_image("images/ic/cfolder15.png","15","13",array("BORDER"=>"0")) . '&nbsp;'.
			db_result($result, $j, 'forum_name').'</a> ';
		//message count
		echo '('. db_result($result,$j,'total') .' msgs)';
		echo "<br>\n";
		echo db_result($result,$j,'description').'<p>';
	}

}

$HTML->box1_bottom();

echo '<p>
';

echo $foundry->getFreeformHTML2();

echo '</td><td valign="top" width="30%">';

echo $foundry->getSponsorHTML1().'<p>';

echo cache_display('foundry'.$group_id.'_features_boxes','foundry_features_boxes()',3600);

echo '</td></tr></table>';

$HTML->footer(array());

?>
