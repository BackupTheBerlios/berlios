<?php
// ## export project front page news in RSS 2.0
include "pre.php";
include "rss_utils.inc";
header("Content-Type: text/xml");
print '<?xml version="1.0" encoding="ISO-8859-1"?>
<rss version="2.0">
';
// ## default limit
if (!$limit) $limit = 10;
if ($limit > 100) $limit = 100;

if ($group_id) {
	$where = "group_id=$group_id";
} else {
	$where = "is_approved=1";
}

$query = "SELECT forum_id,summary,date,details,group_id FROM news_bytes "
	."WHERE $where ORDER BY date DESC";

$res = db_query($query,$limit);

// ## one time output
print " <channel>\n";
print "  <title>$GLOBALS[sys_default_name] Project News</title>\n";
print "  <description>$GLOBALS[sys_default_name] Project News Highlights</description>\n";
print "  <link>http://$GLOBALS[sys_default_host]</link>\n";
print "  <language>en-us</language>\n";
print "  <copyright>Copyright 2000-".date("Y")." Fraunhofer FOKUS</copyright>\n";
print "  <pubDate>".gmdate('D, d M Y G:i:s',time())." GMT</pubDate>\n";
print "  <webMaster>admin@$GLOBALS[sys_default_domain]</webMaster>\n";
// ## item outputs
while ($row = db_fetch_array($res)) {
	print "  <item>\n";
	print "   <title>".htmlspecialchars($row[summary])."</title>\n";
	// if news group, link is main page
	if ($row[group_id] != $sys_news_group) {
		print "   <link>http://$GLOBALS[sys_default_host]/forum/forum.php?forum_id=$row[forum_id]</link>\n";
	} else {
		print "   <link>http://$GLOBALS[sys_default_host]/</link>\n";
	}
	print "   <description>".rss_description($row[details])."</description>\n";
	print "   <pubDate>".gmdate('D, d M Y G:i:s',$row[date])." GMT</pubDate>\n";
	if ($row[group_id] != $sys_news_group) {
		print "   <guid>http://$GLOBALS[sys_default_host]/forum/forum.php?forum_id=$row[forum_id]</guid>\n";
	} else {
		print "   <guid>http://$GLOBALS[sys_default_host]/</guid>\n";
	}
	print "  </item>\n";
}
// ## end output
print " </channel>\n";
?>
</rss>
