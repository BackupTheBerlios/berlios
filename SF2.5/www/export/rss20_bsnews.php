<?php
// ## export BerliOS project front page news in RSS 2.0
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
	$query = "SELECT group_name FROM groups WHERE $where";
	$res = db_query($query,1);
	$row = db_fetch_array($res);
	$title = ": ".$row[group_name]." - ";
	$link = "?group_id=$group_id";
	$description = " of ".$row[group_name];
	$querywm =  "SELECT users.user_name,users.realname FROM user_group,users WHERE group_id=$group_id AND admin_flags='A' AND users.user_id=user_group.user_id ORDER BY users.add_date";
	$reswm = db_query($querywm,1);
	if ($rowwm = db_fetch_array($reswm)) {
	  $webmaster = $rowwm[user_name]."@".$GLOBALS[sys_users_host]." (".$rowwm[realname].")";
	} else {
	  $webmaster = "admin@".$GLOBALS[sys_default_domain];
	}
} else {
	$where = "is_approved=1";
	$title = "";
	$link = "";
	$description = "";
	$webmaster = "admin@".$GLOBALS[sys_default_domain];
}

// ## one time output
print " <channel>\n";
print "  <title>".$GLOBALS[sys_default_name]." Project$title News</title>\n";
print "  <link>http://".$GLOBALS[sys_default_host]."/news/$link</link>\n";
print "  <description>".$GLOBALS[sys_default_name]." Project News$description</description>\n";
print "  <language>en-us</language>\n";
print "  <copyright>Copyright 2000-".date("Y")." Fraunhofer FOKUS</copyright>\n";
print "  <webMaster>$webmaster</webMaster>\n";
print "  <lastBuildDate>".gmdate('D, d M Y G:i:s',time())." GMT</lastBuildDate>\n";
print "  <docs>http://blogs.law.harvard.edu/tech/rss</docs>\n";
print "  <generator>BerliOS RSS generator</generator>\n";
print "  <image>\n";
print "    <url>http://".$GLOBALS[sys_default_host]."/images/berliOS_small_logo.png</url>\n";
print "    <title>BerliOS Developer</title>\n";
print "    <link>http://".$GLOBALS[sys_default_host]."/</link>\n";
print "    <width>124</width>\n";
print "    <heigth>32</heigth>\n";
print "  </image>\n";

$query = "SELECT user_name,realname,forum_id,summary,date,details,group_id FROM users,news_bytes "
	."WHERE $where AND news_bytes.submitted_by=users.user_id ORDER BY date DESC";

$res = db_query($query,$limit);

// ## item outputs
while ($row = db_fetch_array($res)) {
	print "  <item>\n";
	print "   <title>".htmlspecialchars($row[summary])."</title>\n";
	// if news group, link is main page
	if ($row[group_id] != $sys_news_group) {
		print "   <link>http://".$GLOBALS[sys_default_host]."/forum/forum.php?forum_id=".$row[forum_id]."</link>\n";
	} else {
		print "   <link>http://".$GLOBALS[sys_default_host]."/</link>\n";
	}
	print "   <description>".rss_description($row[details])."</description>\n";
	print "   <author>".$row[user_name]."@".$GLOBALS[sys_mail_host]." (".$row[realname].")</author>\n";
	print "   <pubDate>".gmdate('D, d M Y G:i:s',$row[date])." GMT</pubDate>\n";
	if ($row[group_id] != $sys_news_group) {
		print "   <guid>http://".$GLOBALS[sys_default_host]."/forum/forum.php?forum_id=".$row[forum_id]."</guid>\n";
	} else {
		print "   <guid>http://".$GLOBALS[sys_default_host]."/</guid>\n";
	}
	// if news group, comment is main page
	if ($row[group_id] != $sys_news_group) {
		print "   <comment>http://".$GLOBALS[sys_default_host]."/forum/forum.php?forum_id=".$row[forum_id]."</comment>\n";
	} else {
		print "   <comment>http://".$GLOBALS[sys_default_host]."/</comment>\n";
	}
	print "  </item>\n";
}
// ## end output
print " </channel>\n";
?>
</rss>
