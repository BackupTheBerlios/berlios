<?php
// ## export project new releases in RSS 2.0
include "pre.php";
include "rss_utils.inc";
header("Content-Type: text/xml");
print '<?xml version="1.0"?>
<rss version="2.0">
';

// ## default limit
if (!$limit) $limit = 10;
if ($limit > 100) $limit = 100;

if ($group_id) {
	$res = db_query("SELECT group_name,unix_group_name,short_description "
	        . "FROM groups WHERE "
       		. "group_id=$group_id");
	if ($row = db_fetch_array($res)) {
        	// ## one time output
        	print " <channel>\n";
        	print "  <title>New Releases: ".htmlspecialchars($row[group_name])."</title>\n";
        	print "  <description>".rss_description($row[short_description])."</description>\n";
        	print "  <link>http://$GLOBALS[sys_default_host]/projects/$row[unix_group_name]</link>\n";
        	print "  <language>en-us</language>\n";
        	print "  <copyright>Copyright 2000-2004 Fraunhofer FOKUS</copyright>\n";
        	print "  <pubDate>".gmdate('D, d M Y g:i:s',time())." GMT</pubDate>\n";
        	print "  <webMaster>admin@$GLOBALS[sys_default_domain]</webMaster>\n";
	}

	$where = "frs_package.group_id=$group_id AND ";
} else {
	// ## one time output
	print " <channel>\n";
	print "  <title>$GLOBALS[sys_default_name] New Releases</title>\n";
	print "  <description>$GLOBALS[sys_default_name] New Releases</description>\n";
	print "  <link>http://$GLOBALS[sys_default_host]</link>\n";
	print "  <language>en-us</language>\n";
	print "  <copyright>Copyright 2000-".date("Y")." Fraunhofer FOKUS</copyright>\n";
	print "  <pubDate>".gmdate('D, d M Y G:i:s',time())." GMT</pubDate>\n";
	print "  <webMaster>admin@$GLOBALS[sys_default_domain]</webMaster>\n";

	$where = "";
}

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
		print "   <link>http://$GLOBALS[sys_default_host]/project/showfiles.php?group_id=$row[group_id]&amp;release_id=$row[filerelease_id]</link>\n";
		print "   <description>".rss_description($row[module_notes])."</description>\n";
		print "   <pubDate>".gmdate('D, d M Y G:i:s',$row[release_time])." GMT</pubDate>\n";
		print "   <guid>http://$GLOBALS[sys_default_host]/project/showfiles.php?group_id=$row[group_id]&amp;release_id=$row[filerelease_id]</guid>\n";
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
