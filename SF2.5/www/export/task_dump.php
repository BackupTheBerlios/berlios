<?php
// ## export tasks for a specific project
include "pre.php";

header("Content-Type: text/plain");
print("<?xml version=\"1.0\"?>
<!DOCTYPE bs_tasks SYSTEM \"http://$sys_default_host/export/bs_tasks_0.1.dtd\">
<tasks>
");

if( !isset($group_id) ) {
	print("    <error>Group ID Not Set</error>\n");
} else {
	$project=group_get_object($group_id);

	if (!user_isloggedin()) {
	  if (isset($login) && isset($passwd)) {
		$success=session_login_valid(strtolower($login),$passwd);
        if (!$success) {
		  print("    <error>Invalid Login and/or Password</error>\n");
		  print("</tasks>\n");
		  exit;
		}
	  } else {
		print("    <error>Login and/or Password missing</error>\n");
		print("</tasks>\n");
		exit;
	  }
	}

	if( !$project->userIsAdmin() ) {
		print("    <error>You are not an administrator for this project</error>\n");
		print("</tasks>\n");
		exit;
	}

	if (user_isloggedin() && user_ismember($group_id)) {
		$public_flag='0,1';
	} else {
		$public_flag='1';
	}

	$query="SELECT * FROM project_group_list WHERE group_id='$group_id' AND is_public IN ($public_flag)";

	$ressub = db_query ($query);
	$rows = db_numrows($ressub);
	if (!$ressub || $rows < 1) {
		echo "<error>No subprojects found</error>\n";
		print("</tasks>\n");
		exit;
	}

	while( $rowsub = db_fetch_array($ressub) ) {
		print("    <subproject id='" . $rowsub["group_project_id"] . "'>\n");
		print("        <group_id>" . $rowsub["group_id"] . "</group_id>\n");
		print("        <subproject_name>" . $rowsub["project_name"] . "</subproject_name>\n");	  	  
		print("        <is_public>" . $rowsub["is_public"] . "</is_public>\n");	  	  
		print("        <description>" . $rowsub["description"] . "</description>\n");	  	  

		$query = "SELECT * FROM project_task WHERE group_project_id='".$rowsub["group_project_id"]."'";
		$restas = db_query($query);

		while( $rowtas = db_fetch_array($restas) ) {
		  $created_by = db_result(db_query("SELECT user_name FROM users WHERE user_id='" . $rowtas["created_by"] . "'"),0,"user_name");
		  print("        <task id='" . $rowtas["project_task_id"] . "'>\n");
		  print("            <group_id>" . $rowtas["group_project_id"] . "</group_id>\n");
		  print("            <status_id>" . $rowtas["status_id"] . "</status_id>\n");
		  print("            <priority>" . $rowtas["priority"] . "</priority>\n");
		  print("            <created_by id='" . $rowtas["created_by"] . "' name='$created_by'/>\n");

		  $query = "SELECT assigned_to_id FROM project_assigned_to WHERE project_task_id='" . $rowtas["project_task_id"] . "'";
		  $resass = db_query($query);
		  while( $rowass = db_fetch_array($resass) ) {
			$assigned_to_id = $rowass["assigned_to_id"];
			$assigned_to = db_result(db_query("SELECT user_name FROM users WHERE user_id='" . $assigned_to_id . "'"),0,"user_name");
			print("            <assigned_to id='" . $assigned_to_id . "' name='$assigned_to'/>\n");
		  }
		  print("            <start_date>" . $rowtas["start_date"] . "</start_date>\n");
		  print("            <summary>" . $rowtas["summary"] . "</summary>\n");
		  print("            <details>" . $rowtas["details"] . "</details>\n");
		  print("            <end_date>" . $rowtas["end_date"] . "</end_date>\n");
		  print("            <percent_complete>" . $rowtas["percent_complete"] . "</percent_complete>\n");
		  print("            <hours>" . $rowtas["hours"] . "</hours>\n");

		  $resdep = db_query("SELECT * FROM project_dependencies WHERE project_task_id='" . $rowtas["project_task_id"] . "'");
		  while( $rowdep=db_fetch_array($resdep) ) {
				print("            <dependency id='" . $rowdep["project_depend_id"] . "'>\n");
				print("                <dep_id>" . $rowdep["is_dependent_on_task_id"] . "</dep_id>\n");
				print("            </dependency>\n");
		  }
		  print("        </task>\n");
	    }
		print("    </subproject>\n");
	}
}
?>
</tasks>
