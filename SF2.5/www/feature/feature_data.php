<?php

/**
 *	List of possible feature_categories set up for the project
 */
function feature_data_get_categories ($group_id) {
	global $feature_data_categories;
	if (!$feature_data_categories["$group_id"]) {
		$sql="select feature_category_id,category_name from feature_category WHERE group_id='$group_id'";
		$feature_data_categories["$group_id"]=db_query($sql);
	}
	return $feature_data_categories["$group_id"];
}

/**
 *	List of people that can be assigned this feature request
 */
function feature_data_get_technicians ($group_id) {
	global $feature_data_technicians;
	if (!$feature_data_technicians["$group_id"]) {
		$sql="SELECT users.user_id,users.user_name ".
		"FROM users,user_group ".
		"WHERE users.user_id=user_group.user_id ".
		"AND user_group.feature_flags IN (1,2) ".
		"AND user_group.group_id='$group_id' ".
		"ORDER BY users.user_name";
		$feature_data_technicians["$group_id"]=db_query($sql);
	}
	return $feature_data_technicians["$group_id"];
}

/**
 *	return a result set of canned_responses for this group and group_id=0
 */
function feature_data_get_canned_responses ($group_id) {
	/*
		show defined canned responses for this project
		and the site-wide canned responses
	*/
	$sql="SELECT feature_canned_id,title,body ".
		"FROM feature_canned_responses ".
		"WHERE (group_id='$group_id' OR group_id='0')";
	return db_query($sql);
}

/**
 *	returns a result set of statuses
 */
function feature_data_get_statuses() {
	global $feature_data_statuses;
	if (!$feature_data_statuses) {
		$sql="select * from feature_status";
		$feature_data_statuses=db_query($sql);
	}
	return $feature_data_statuses;
}

/**
 *	returns a result set of audit trail for this feature request
 */
function feature_data_get_history ($feature_id) {
	$sql="select feature_history.field_name,feature_history.old_value,feature_history.date,users.user_name ".
		"FROM feature_history,users ".
		"WHERE feature_history.mod_by=users.user_id ".
		"AND feature_id='$feature_id' ORDER BY feature_history.date DESC";
	return db_query($sql);
}

function feature_data_get_status_name($string) {
	/*
		simply return status_name from feature_status
	*/
	$sql="select * from feature_status WHERE feature_status_id='$string'";
	$result=db_query($sql);
	if ($result && db_numrows($result) > 0) {
		return db_result($result,0,'status_name');
	} else {
		return 'Error - Not Found';
	}
}

function feature_data_get_category_name($string) {
	/*
		simply return the category_name from feature_category
	*/
	$sql="select * from feature_category WHERE feature_category_id='$string'";
	$result=db_query($sql);
	if ($result && db_numrows($result) > 0) {
		return db_result($result,0,'category_name');
	} else {
		return 'Error - Not Found';
	}
}

function feature_data_create_message ($body,$feature_id,$by) {
	global $feedback;

	if (!$body || !$feature_id || !$by) {
		$feedback .= 'ERROR - Missing Parameters';
		return false;
	}

	if (user_isloggedin()) {
		$body="Logged In: YES \nuser_id=". user_getid() ."\nBrowser: ". $GLOBALS['HTTP_USER_AGENT'] ."\n\n".$body;
	} else {
		$body="Logged In: NO \nBrowser: ". $GLOBALS['HTTP_USER_AGENT'] ."\n\n".$body;
	}

	$sql="insert into feature_messages(feature_id,body,from_email,date) ".
		"VALUES ('$feature_id','". htmlspecialchars($body). "','$by','".time()."')";
	return db_query($sql);
}

function feature_data_create_history ($field_name,$old_value,$feature_id) {
	/*
		handle the insertion of history for these parameters
	*/
	if (!user_isloggedin()) {
		$user=100;
	} else {
		$user=user_getid();
	}

	$sql="insert into feature_history(feature_id,field_name,old_value,mod_by,date) ".
		"VALUES ('$feature_id','$field_name','$old_value','$user','".time()."')";
	return db_query($sql);
}

function feature_data_get_messages ($feature_id) {
	$sql="select * ".
		"FROM feature_messages ".
		"WHERE feature_id='$feature_id' ORDER BY date DESC";
	return db_query($sql);
}

/*

	Add a comment to a feature request

	Different than creating a new message

	This function is used by non-admins 
		to add a followup to an existing request

*/
function feature_data_add_comment ($project,$feature_id,$details,$user_email) {
	global $feedback;

	if (!user_isloggedin()) {
		if (!$user_email) {
			//force them to fill in user_email if they aren't logged in
			$feedback .= 'Go Back and fill in the user_email address or login';
			return false;
		}
	} else {
		//use their user_name if they are logged in
		$user_email=user_getname().'@'.$GLOBALS['sys_users_host'];
	}

	if ($project && $feature_id && $details) {
		//create the first message for this ticket
		if (!feature_data_create_message($details,$feature_id,$user_email)) {
			return false;
		} else {
			$feedback .= 'Comment added to feature request';
			if ($project->sendAllFeatureUpdates()) {
				$address=$project->getNewFeatureAddress();
			}       
			mail_followup($feature_id,$address);
			return true;
		}
	}
}

/*

	Create a new feature request

	Returns true/false and $feedback

*/

function feature_data_create_feature ($project,$feature_category_id,$user_email,$summary,$details) {
	global $feedback;

	$group_id=$project->getGroupID();

	if (!$group_id) {
		$feedback .= 'ERROR - Missing Group Id';
		return false;
	}

	if (!$feature_category_id) {
		$feature_category_id=100;
	}

	if (!user_isloggedin()) {
		$user=100;
		if (!$user_email) {
			//force them to fill in user_email if they aren't logged in
			$feedback .= 'ERROR - Go Back and fill in the user_email address or login';
			return false;
		}
	} else {
		$user=user_getid();
		//use their user_name if they are logged in
		$user_email=user_getname().'@'.$GLOBALS['sys_users_host'];
	}

	if (!$group_id || !$summary || !$details) {
		$feedback .= 'ERROR - Go Back and fill in all the information requested';
		return false;
	}

	//make sure we aren't double-submitting this code
	$res=db_query("SELECT * FROM feature WHERE submitted_by='$user' AND summary='". htmlspecialchars ($summary) ."'");
	if ($res && db_numrows($res) > 0) {
		$feedback .= 'ERROR - DOUBLE SUBMISSION. You are trying to double-submit this request. Please do not double-submit requests.';
		return false;
	}

	//now insert the request
	$sql="INSERT INTO feature (priority,close_date,group_id,feature_status_id,feature_category_id,submitted_by,assigned_to,open_date,summary) ".
		"VALUES ('5','0','$group_id','1','$feature_category_id','$user','100','". time() ."','". htmlspecialchars($summary) ."')";

	db_begin();
	$result=db_query($sql);
	$feature_id=db_insertid($result,'feature','feature_id');

	if (!$result || !$feature_id) {
		$feedback .= ' ERROR - Data insertion failed '.db_error();
		db_rollback();
		return false;
	} else {

		if ($details != '') {
			//create the first message for this ticket
			if (!feature_data_create_message($details,$feature_id,$user_email)) {
				$feedback .= ' Comment Failed ';
				db_rollback();
				return false;
			} else {
				$feedback .= ' Comment added to feature request ';
			}
		}

		$feedback .= ' Successfully Added Feature Request ';
	}
	mail_followup($feature_id, $project->getNewFeatureAddress());
	db_commit();
	return $feature_id;
}

/*

	Update feature requests

	Return true/false and $feedback

	Handles security

*/

//feature_id is an array of features that were checked. The other params are not arrays.
function feature_data_update ($project,$feature_id,$priority,$feature_status_id,
	$feature_category_id,$assigned_to,$summary,$canned_response,$details) {
	global $feedback;

	$group_id=$project->getGroupID();

	if(!is_Array($feature_id)){ 
		$feature_id = array($feature_id); 
	}

	if (!$group_id || !$feature_id || !$assigned_to || !$feature_status_id || !$feature_category_id || !$canned_response) {
		$feedback .= " ERROR: Missing required parameters to feature_data_update ";
		return false;
	}
	$count=count($feature_id);

	if ($count > 0) {
		for ($i=0; $i<$count;$i++) {

			//get this feature from the db
			$sql="SELECT * FROM feature WHERE feature_id='$feature_id[$i]' AND group_id='$group_id'";
			$result=db_query($sql);
			$group_id=db_result($result,0,'group_id');

			if ((db_numrows($result) < 1) || !($project->userIsFeatureAdmin())) {
			//verify permissions
				$feedback .= 'ERROR - permission denied';
				return false;
			}

			// We should assume no update is needed until otherwise
			// verified (imagine all the possible unnecessary queries
			// for a 50 item bug list!) -- G
			$update = false;
			$sql="UPDATE feature SET ";

			db_begin();

			/*
				See which fields changed during the modification
			*/
			$sql .= "feature_status_id='";
			if ( (db_result($result,0,'feature_status_id') != $feature_status_id) && ($feature_status_id != 100) ) {
				feature_data_create_history('feature_status_id',db_result($result,0,'feature_status_id'),$feature_id[$i]);
				$update = true;
				$sql .= $feature_status_id;
			} else {
				$sql .= db_result($result,0,'feature_status_id');
			}

			$sql .= "', feature_category_id='";
			if ( (db_result($result,0,'feature_category_id') != $feature_category_id) && ($feature_category_id != 100) ) { 
				feature_data_create_history('feature_category_id',db_result($result,0,'feature_category_id'),$feature_id[$i]);
				$update = true;
				$sql .= $feature_category_id;
			} else {
				$sql .= db_result($result,0,'feature_category_id');
			}

			$sql .= "', priority='";
			if ( (db_result($result,0,'priority') != $priority) && ($priority != 100)) {
				feature_data_create_history('priority',db_result($result,0,'priority'),$feature_id[$i]);
				$update = true;
				$sql .= $priority;
			} else {
				$sql .= db_result($result,0,'priority');
			}

			$sql .= "', assigned_to='";
			if ( (db_result($result,0,'assigned_to') != $assigned_to) && ($assigned_to != 100)) { 
				feature_data_create_history('assigned_to',db_result($result,0,'assigned_to'),$feature_id[$i]);
				$update = true;
				$sql .= $assigned_to;
			} else {
				$sql .= db_result($result,0,'assigned_to');
			}

			$sql .= "'";
			if ( (db_result($result,0,'summary') != stripslashes(htmlspecialchars($summary))) && ($summary != '') ) {
				feature_data_create_history('summary',htmlspecialchars(addslashes(db_result($result,0,'summary'))),$feature_id[$i]);
				$update = true;
				$sql .= ", summary='".htmlspecialchars($summary)."'";
			}

			/*
				Enter the timestamp if we are changing to closed
			*/
			if ($feature_status_id != "1") {
				$now=time();
				$sql .= ", close_date='$now'";
				feature_data_create_history('close_date',db_result($result,0,'close_date'),$feature_id[$i]);
			}

			/*
				Finally, update the feature itself
			*/
			if ($update){
				$sql .= " WHERE feature_id='$feature_id[$i]'";

				$result=db_query($sql);

				if (!$result) {
					$feedback .= ' Error - update failed! ';
					db_rollback();
					return false;
				} else {
					$feedback .= " Successfully Modified Feature Ticket $feature_id[$i] ";
				}
			}

			/*
				Details field is handled a little differently

				Details are comments attached to bugs
				They are still stored in the bug_history (audit 
				trail) system, but they are not shown in the
				 regular audit trail

				Someday, these should technically be split into
				 their own table.
			*/
			if ($details != '') {
				//create the first message for this ticket
				if (!feature_data_create_message($details,$feature_id[$i],user_getname().'@'.$GLOBALS['sys_users_host'])) {
					db_rollback();
					return false;
				} else {
					$feedback .= ' Comment added to feature request '.$feature_id[$i].' ';
					$send_message=true;
				}
			}

			/*
				handle canned responses
			*/
			if ($canned_response != 100) {
				//don't care if this response is for this group - could be hacked
				$sql="SELECT * FROM feature_canned_responses WHERE feature_canned_id='$canned_response'";
				$result2=db_query($sql);
				if ($result2 && db_numrows($result2) > 0) {
					if (!feature_data_create_message(util_unconvert_htmlspecialchars(db_result($result2,0,'body')),$feature_id[$i],user_getname().'@'.$GLOBALS['sys_users_host'])) {
						db_rollback();
						return false;
					} else {
						$feedback .= ' Canned Response Used For Feature Request ID ' .$feature_id[$i] . '';
						$send_message=true;
					}
				} else {
					$feedback .= ' Unable to Use Canned Response ';
				}
			}

			if ($update || $send_message){
				/*
					see if we're supposed to send all modifications to an address
				*/
				$project=project_get_object($group_id);
				if ($project->sendAllFeatureUpdates()) {
					$address=$project->getNewFeatureAddress();
				}       

				/*
					now send the email
					it's no longer optional due to the group-level notification address
				*/

				mail_followup($feature_id[$i],$address);
				db_commit();
			} else {
				//nothing changed, so cancel the transaction
				db_rollback();
			}
		}
	}

	return true;
}

function mail_followup($feature_id,$more_addresses=false) {
	global $sys_datefmt,$feedback;
	/*
		Send a message to the person who opened this feature and the person it is assigned to
	*/

	$sql="SELECT feature.priority,feature.group_id,feature.feature_id,feature.summary,".
		"feature_status.status_name,feature_category.category_name,feature.open_date, ".
		"users.email,user2.email AS assigned_to_email ".
		"FROM feature,users,users user2,feature_status,feature_category ".
		"WHERE user2.user_id=feature.assigned_to ".
		"AND feature.feature_status_id=feature_status.feature_status_id ".
		"AND feature.feature_category_id=feature_category.feature_category_id ".
		"AND users.user_id=feature.submitted_by ".
		"AND feature.feature_id='$feature_id'";
		
	$result=db_query($sql);
	
	if ($result && db_numrows($result) > 0) {
		/*
			Set up the body
		*/
		$body = "\n\nFeature Request #".db_result($result,0,'feature_id').", was updated on ".
				date($sys_datefmt,db_result($result,0,'open_date')). 
			"\nYou can respond by visiting: ".
			"\nhttp://".$GLOBALS['sys_default_host']."/feature/?func=detailfeature&feature_id=".
				db_result($result,0,"feature_id")."&group_id=".db_result($result,0,"group_id").
			"\n\nCategory: ".db_result($result,0,'category_name').
			"\nStatus: ".db_result($result,0,'status_name').
			"\nPriority: ".db_result($result,0,'priority').
			"\nSummary: ".util_unconvert_htmlspecialchars(db_result($result,0,'summary'));
			
			
		$subject="[Feature #".db_result($result,0,"feature_id")."] ".
			util_unconvert_htmlspecialchars(db_result($result,0,"summary"));
			
		/*      
			get all the email addresses that have dealt with this request
		*/

		$email_res=db_query("SELECT distinct from_email FROM feature_messages WHERE feature_id='$feature_id'");
		$rows=db_numrows($email_res);
		if ($email_res && $rows > 0) {
			$mail_arr=result_column_to_array($email_res,0);
			$emails=implode($mail_arr,', ');
		}       
		if ($more_addresses) {
			$emails .= ','.$more_addresses;
		}       
		
		/*
			Now include the two most recent emails
		*/
		$sql="select * ".
			"FROM feature_messages ".
			"WHERE feature_id='$feature_id' ORDER BY date DESC";
		$result2=db_query($sql,2);
		$rows=db_numrows($result2);
		if ($result && $rows > 0) {
			for ($i=0; $i<$rows; $i++) {
				//get the first part of the email address
				$email_arr=explode('@',db_result($result2,$i,'from_email'));
				
				$body .= "\n\nBy: ". $email_arr[0] .
				"\nDate: ".date($sys_datefmt,db_result($result2,$i,'date')).
				"\n\nMessage:".
				"\n".util_unconvert_htmlspecialchars(db_result($result2,$i,'body')).
				"\n\n----------------------------------------------------------------------";
			}       
			$body .= "\nYou can respond by visiting: ".
			"\nhttp://$GLOBALS[sys_default_host]/feature/?func=detailfeature&feature_id=".
				db_result($result,0,'feature_id')."&group_id=".db_result($result,0,'group_id');
		}	       
		
		//attach the headers to the body
		
		$body = "To: noreply@$GLOBALS[sys_default_domain]".
			"\nBCC: $emails".
			"\nSubject: $subject".
			$body;
		/*      
			Send the email
		*/
		exec ("/bin/echo \"". util_prep_string_for_sendmail($body)
			."\" | $GLOBALS[sys_sendmail_path] -fnoreply@$GLOBALS[sys_default_domain] -t &");
		$feedback .= " Feature Request Update Emailed ";
		
	} else {
	
		$feedback .= " Could Not Send Feature Request Update ";
		echo db_error();
		
	}       
}       

?>
