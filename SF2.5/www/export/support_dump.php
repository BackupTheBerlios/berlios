<?php
// ## export support requests for a specific project
include "pre.php";

header("Content-Type: text/plain");
print("<?xml version=\"1.0\"?>
<!DOCTYPE bs_support SYSTEM \"http://$sys_default_host/export/bs_support_0.1.dtd\">
<support>
");

if( !isset($group_id) ) {
	print("    <error>Group ID Not Set</error>\n");
} else {
	$project=group_get_object($group_id);
	if( !$project->userIsAdmin() ) {
		print("    <error>You are not an administrator for this project</error>\n");
		print("</support>\n");
		exit;
	}

	$query = "SELECT 
				s.*
			  FROM 
				support s
			  WHERE 
				s.group_id='$group_id'
			  ORDER BY
				s.support_id";
	$res = db_query($query);

	while( $row = db_fetch_array($res) ) {
		$submitted_by = db_result(db_query("SELECT user_name FROM users WHERE user_id='" . $row["submitted_by"] . "'"),0,"user_name");
		$assigned_to = db_result(db_query("SELECT user_name FROM users WHERE user_id='" . $row["assigned_to"] . "'"),0,"user_name");
		print("    <support id='" . $row["support_id"] . "'>\n");
		print("        <group_id>$group_id</group_id>\n");
		print("        <status_id>" . $row["support_status_id"] . "</status_id>\n");
		print("        <priority>" . $row["priority"] . "</priority>\n");
		print("        <category_id>" . $row["support_category_id"] . "</category_id>\n");
		print("        <submitted_by id='" . $row["submitted_by"] . "' name='$submitted_by'/>\n");
		print("        <assigned_to id='" . $row["assigned_to"] . "' name='$assigned_to'/>\n");
		print("        <date>" . $row["open_date"] . "</date>\n");
		print("        <summary>" . $row["summary"] . "</summary>\n");
		print("        <details>" . $row["details"] . "</details>\n");
		print("        <close_date>" . $row["close_date"] . "</close_date>\n");

		$res_hist = db_query("SELECT * FROM support_history WHERE support_id='" . $row["support_id"] . "'");
		while( $row3=db_fetch_array($res_hist) ) {
				print("        <history id='" . $row3["support_history_id"] . "'>\n");
				print("            <field_name>" . $row3["field_name"] . "</field_name>\n");
				print("            <old_value>" . $row3["old_value"] . "</old_value>\n");
				print("            <mod_by>" . $row3["mod_by"] . "</mod_by>\n");
				print("            <date>" . $row3["date"] . "</date>\n");
				print("        </history>\n");
		}

		print("    </support>\n");
	}
}
?>
</support>
