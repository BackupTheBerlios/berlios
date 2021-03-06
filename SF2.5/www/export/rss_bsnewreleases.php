<?php
// ## export sf front page news in RSS
include "pre.php";
include "rss_utils.inc";
header("Content-Type: text/xml");
print '<?xml version="1.0"?>
<!DOCTYPE rss SYSTEM "http://my.netscape.com/publish/formats/rss-0.91.dtd">
<rss version="0.91">
';

// ## default limit
if (!$limit) $limit = 10;
if ($limit > 100) $limit = 100;

if ($group_id) {
	$where = "frs_package.group_id=$group_id AND ";
	$res = db_query("SELECT group_name FROM groups WHERE group_id=$group_id");
	$row = db_fetch_array($res);
	$title = ": ".$row[group_name]." - ";
	$link = "/project/showfiles.php?group_id=$group_id";
	$description = " of ".$row[group_name];
	$querywm =  "SELECT users.user_name,users.realname FROM user_group,users WHERE group_id=$group_id AND admin_flags='A' AND users.user_id=user_group.user_id ORDER BY users.add_date";
	$reswm = db_query($querywm,1);
	if ($rowwm = db_fetch_array($reswm)) {
	  $webmaster = $rowwm[user_name]."@".$GLOBALS[sys_users_host]." (".$rowwm[realname].")";
	} else {
	  $webmaster = "admin@".$GLOBALS[sys_default_domain];
	}
} else {
	$where = "";
    $title = "";
	$link = "/new/";
	$description = "";
	$webmaster = "admin@".$GLOBALS[sys_default_domain];
}

// ## one time output
print " <channel>\n";
print "  <title>".$GLOBALS[sys_default_name]." Project$title Releases</title>\n";
print "  <link>http://".$GLOBALS[sys_default_host]."$link</link>\n";
print "  <description>".$GLOBALS[sys_default_name]." Project Releases$description</description>\n";
print "  <language>en-us</language>\n";
print "  <copyright>Copyright 2000-".date("Y")." Fraunhofer FOKUS</copyright>\n";
print "  <webMaster>$webmaster</webMaster>\n";
print "  <pubDate>".gmdate('D, d M Y G:i:s',time())." GMT</pubDate>\n";
print "  <image>\n";
print "    <url>http://".$GLOBALS[sys_default_host]."/images/berliOS_small_logo.png</url>\n";
print "    <title>BerliOS Developer</title>\n";
print "    <link>http://".$GLOBALS[sys_default_host]."$link</link>\n";
print "    <width>124</width>\n";
print "    <heigth>32</heigth>\n";
print "  </image>\n";

$res = db_query("SELECT groups.group_name AS group_name,"
	. "frs_package.group_id AS group_id,"
	. "groups.unix_group_name AS unix_group_name,"
	. "groups.short_description AS short_description,"
	. "groups.license AS license,"
	. "users.user_name AS user_name,"
	. "users.user_id AS user_id,"
    . "frs_package.name AS package_name,"
	. "frs_release.package_id AS filemodule_id,"
	. "frs_release.name AS module_name,"
	. "frs_release.notes AS module_notes,"
	. "frs_release.status_id AS release_status,"
	. "frs_release.release_date AS release_date,"
	. "frs_file.release_time AS release_time,"
	. "frs_file.filename AS filename,"
	. "frs_file.release_id AS filerelease_id "
	. "FROM users,frs_file,frs_release,frs_package,groups WHERE "
	. "frs_release.released_by=users.user_id AND "
	. "frs_release.package_id=frs_package.package_id AND "
	. "frs_package.group_id=groups.group_id AND "
	. "frs_release.status_id=1 AND "
	. $where
	. "frs_file.release_id=frs_release.release_id "
	. "ORDER BY frs_file.release_time DESC",($limit * 3));


// ## item outputs
$outputtotal = 0;
while ($row = db_fetch_array($res)) {
	if (!$G_RELEASE["$row[filerelease_id]"]) {
		print "  <item>\n";
		print "   <title>".htmlspecialchars($row[package_name])." ".htmlspecialchars($row[module_name])."</title>\n";
		print "   <link>http://".$GLOBALS[sys_default_host]."/project/showfiles.php?group_id=".$row[group_id]."&amp;release_id=".$row[filerelease_id]."</link>\n";
		print "   <description>";
		print rss_description($row[module_notes]);
		print "</description>\n";
		print "  </item>\n";
		$outputtotal++;
	}
	// ## eliminate dupes, only do $limit of these
	$G_RELEASE["$row[filerelease_id]"] = 1;
	if ($outputtotal >= $limit) break;
}
// ## end output
print " </channel>\n";
?>
</rss>
