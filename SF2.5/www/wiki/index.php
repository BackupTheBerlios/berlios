<?php
//
// BerliOS: Fostering Open Source Development
// Copyright 2000-20040 (c) The BerliOS Crew
// http://www.berlios.de
//
// $Id $

require('pre.php');
require('../wiki/wiki_utils.php');

if ($group_id) {
	wiki_header(array('title'=>'Wikis for '.group_getname($group_id)));

	echo '
		<H2>Wikis for '.group_getname($group_id).'</H2>';

	echo '<p>'.html_image("images/ic/cfolder15.png","15","13",array("BORDER"=>"0")).'&nbsp;'
      .'<a href="http://openfacts.berlios.de/index-en.phtml?title='.group_getname($group_id).'">English</a>';
    echo '<p>'.html_image("images/ic/cfolder15.png","15","13",array("BORDER"=>"0")).'&nbsp;'
	  .'<a href="http://openfacts.berlios.de/index.phtml?title='.group_getname($group_id).'">Deutsch</a>';
    echo '<p>'. html_image("images/ic/cfolder15.png","15","13",array("BORDER"=>"0")).'&nbsp;'
	  .'<a href="http://openfacts.berlios.de/index-es.phtml?title='.group_getname($group_id).'">Español</a>';

	wiki_footer(array());

} else {
	exit_no_group();
}

?>
