<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: rss_utils.inc,v 1.3 2004/06/09 10:58:11 helix Exp $

function rss_setscheme() {
	if ($GLOBALS['scheme'] == "netscape") {
		$GLOBALS['description_maxlen'] = 500;
		$GLOBALS['description_notnull'] = 1;
	}
}

function rss_description ($in) {
	// if description_maxlen is defined, then truncate appropriately
	// added for netscape rss schema compat
	if ($GLOBALS['description_maxlen'] &&
		(strlen($in) > $GLOBALS['description_maxlen'])) {
		$in = substr($in,0,$GLOBALS['description_maxlen']);
	}
	// according to netscape, this cannot be blank
	if ((strlen($in)==0) && $GLOBALS['description_notnull']) {
		$in = "No description available.";
	}
	return htmlspecialchars($in);
}

// callback is optional function name which should be called for each project
// row and which result will be appended to description element
function rss_dump_project_result_set ($res,$title,$desription='',$callback=0) {
        if (!$desription) $desription=$title;
	// ## one time output
	print " <channel>\n";
	print "  <copyright>Copyright 2000-2004 Fraunhofer FOKUS</copyright>\n";
	print "  <pubDate>".gmdate('D, d M Y g:i:s',time())." GMT</pubDate>\n";
	print "  <description>$desription</description>\n";
	print "  <link>http://$GLOBALS[sys_default_host]</link>\n";
	print "  <title>$title</title>\n";
	print "  <webMaster>admin@$GLOBALS[sys_default_domain]</webMaster>\n";
	print "  <language>en-us</language>\n";

	// ## item outputs
	while ($row = db_fetch_array($res)) {
                if ($callback) {
                	$addendum=$callback($row);
                }
		print "  <item>\n";
		print "   <title>".htmlspecialchars($row[group_name])."</title>\n";
		print "   <link>http://$GLOBALS[sys_default_host]/projects/$row[unix_group_name]/</link>\n";
		print "   <description>";
		print ereg_replace(" *\r*\n *"," ",rss_description($row[short_description]));
                print $addendum;
		print "</description>\n";
		print "  </item>\n";
	}
	// ## end output
	print " </channel>\n";
}

?>
