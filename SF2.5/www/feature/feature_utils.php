<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: feature_utils.php,v 1.1 2004/03/16 14:51:57 helix Exp $

/*

	Feature Request Manager 
	By Tim Perdue, Sourceforge, January, 2000
	Heavy Rewrite Tim Perdue, April, 2000

*/

function feature_header($params) {
	global $group_id,$DOCUMENT_ROOT;

	//required by new site_project_header
	$params['group']=$group_id;
	$params['toptab']='feature';

	//only projects can use the bug tracker, and only if they have it turned on
	$project=project_get_object($group_id);

	if (!$project->isProject()) {
		exit_error('Error','Only Projects Can Use The Feature Request Manager');
	}
	if (!$project->usesFeature()) {
		exit_error('Error','This Project Has Turned Off The Feature Request Manager');
	}


	site_project_header($params);

	echo '<P><B><A HREF="/feature/?func=addfeature&group_id='.$group_id.'">Submit A Feature Request</A>';
	if (user_isloggedin()) {
		echo ' | <A HREF="/feature/?func=browse&group_id='.$group_id.'&set=my">My Feature Requests</A>';
	}
	echo ' | <A HREF="/feature/?func=browse&group_id='.$group_id.'&set=open">Open Feature Requests</A>';
	if (user_isloggedin()) {
		echo ' | <A HREF="/feature/reporting/?group_id='.$group_id.'">Reporting</A>';
	}
	echo ' | <A HREF="/feature/admin/?group_id='.$group_id.'">Admin</A>';

	echo '</B><P>';
}

function feature_footer($params) {
	site_project_footer($params);
}

function feature_category_box ($group_id,$name='feature_category_id',$checked='xzxz',$text_100='None') {
	if (!$group_id) {
		return 'ERROR - No group_id';
	} else {
		$result= feature_data_get_categories ($group_id);
		return html_build_select_box ($result,$name,$checked,true,$text_100);
	}
}

function feature_technician_box ($group_id,$name='assigned_to',$checked='xzxz') {
	if (!$group_id) {
		return 'ERROR - No group_id';
	} else {
		$result= feature_data_get_technicians ($group_id);
		return html_build_select_box ($result,$name,$checked);
	}
}

function feature_canned_response_box ($group_id,$name='canned_response',$checked='xzxz') {
	if (!$group_id) {
		return 'ERROR - No group_id';
	} else {
		$result= feature_data_get_canned_responses ($group_id);
		return html_build_select_box ($result,$name,$checked);
	}
}

function feature_status_box ($name='status_id',$checked='xzxz',$text_100='None') {
	$result=feature_data_get_statuses();
	return html_build_select_box($result,$name,$checked,true,$text_100);
}

function show_featurelist ($result,$offset,$set='open') {
	global $sys_datefmt,$group_id;
	/*
		Accepts a result set from the feature table. Should include all columns from
		the table, and it should be joined to USER to get the user_name.
	*/

	$url = "/feature/?group_id=$group_id&set=$set&order=";
	$title_arr=array();
	$title_arr[]='Request ID';
	$title_arr[]='Summary';
	$title_arr[]='Category';
	$title_arr[]='Status';
	$title_arr[]='Date';
	$title_arr[]='Assigned To';
	$title_arr[]='Submitted By';

	$links_arr=array();
	$links_arr[]=$url.'feature_id';
	$links_arr[]=$url.'summary';
	$links_arr[]=$url.'feature_category_id';
	$links_arr[]=$url.'feature_status_id';
	$links_arr[]=$url.'date';
	$links_arr[]=$url.'assigned_to_user';
	$links_arr[]=$url.'submitted_by';

	$IS_FEATURE_ADMIN=user_ismember($group_id,'S2');

	echo '
		<FORM ACTION="'. $PHP_SELF .'" METHOD="POST">
		<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="'.$group_id.'">
		<INPUT TYPE="HIDDEN" NAME="func" VALUE="postmodfeature">';

	echo html_build_list_table_top ($title_arr,$links_arr);

	$then=(time()-1296000);
	$rows=db_numrows($result);
	for ($i=0; $i < $rows; $i++) {
		echo '
			<TR BGCOLOR="'. get_priority_color(db_result($result, $i, 'priority')) .'">'.
			'<TD NOWRAP>'.
			($IS_FEATURE_ADMIN?'<INPUT TYPE="CHECKBOX" NAME="feature_id[]" VALUE="'.
			db_result($result, $i, 'feature_id') .'"> ':'').
	                sprintf("%06d",db_result($result, $i, 'feature_id')) .
        	        '</TD>'.
			'<TD><A HREF="'.$PHP_SELF.'?func=detailfeature&feature_id='. 
			db_result($result, $i, 'feature_id').
			'&group_id='. db_result($result, $i, 'group_id').'">'. 
			db_result($result, $i, 'summary').
			'</A></TD>'.
			'<TD>'. db_result($result, $i, 'category_name') .'</TD>'.
			'<TD>'. db_result($result, $i, 'status_name') .'</TD>'.
			'<TD>'. (($set != 'closed' && db_result($result, $i, 'date') < $then)?'<B>* ':'&nbsp; ') . date($sys_datefmt,db_result($result, $i, 'date')) .'</TD>'.
			'<TD>'. db_result($result, $i, 'assigned_to_user') .'</TD>'.
			'<TD>'. db_result($result, $i, 'submitted_by') .'</TD></TR>';

	}

	/*
		Show extra rows for <-- Prev / Next -->
	*/
	echo '
		<TR><TD COLSPAN="2">';
	if ($offset > 0) {
		echo '<A HREF="'.$PHP_SELF.'?func=browse&group_id='.$group_id.'&set='.$set.'&offset='.($offset-50).'"><B><-- Previous 50</B></A>';
	} else {
		echo '&nbsp;';
	}
	echo '</TD><TD>&nbsp;</TD><TD COLSPAN="2">';
	
	if ($rows>=50) {
		echo '<A HREF="'.$PHP_SELF.'?func=browse&group_id='.$group_id.'&set='.$set.'&offset='.($offset+50).'"><B>Next 50 --></B></A>';
	} else {
		echo '&nbsp;';
	}
	echo '</TD></TR>';

       /*
                Mass Update Code
        */     
        if ($IS_FEATURE_ADMIN) {
                echo '<TR><TD COLSPAN="5">
                <FONT COLOR="#FF0000"><B>Feature Admin:</B></FONT>  If you wish to apply changes to all feature tickets selected above, use these controls to change their properties and click once on "Mass Update".
                <TABLE WIDTH="100%" BORDER="0">

                <TR><TD><B>Category:</B><BR>'. feature_category_box ($group_id,'feature_category_id','xyz','No Change') .'</TD>
                <TD><B>Priority:</B><BR>';
                echo build_priority_select_box ('priority', '5', true);
                echo '</TD></TR>


                <TR><TD><B>Assigned To:</B><BR>'. feature_technician_box ($group_id,'assigned_to','xyz','No Change') .'</TD>
                <TD><B>Status:</B><BR>'. feature_status_box ('feature_status_id','xyz','No Change') .'</TD></TR>

                <TR><TD COLSPAN="2"><B>Canned Response:</B><BR>'. feature_canned_response_box ($group_id,'canned_response') .'</TD></TR>

                <TR><TD COLSPAN="3" ALIGN="MIDDLE"><INPUT TYPE="SUBMIT" name="submit" VALUE="Mass Update"></TD></TR>

                </TABLE>        
		</FORM>
                </TD></TR>';
        }

	echo '</TABLE>';
}

function show_feature_details ($feature_id) {
	/*
		Show the details rows from feature_history
	*/
	global $sys_datefmt;
	$result= feature_data_get_messages ($feature_id);
	$rows=db_numrows($result);

	if ($rows > 0) {
		echo '
		<H3>Followups</H3>
		<P>';
		$title_arr=array();
		$title_arr[]='Message';

		echo html_build_list_table_top ($title_arr);

		for ($i=0; $i < $rows; $i++) {
			$email_arr=explode('@',db_result($result,$i,'from_email'));
			echo '<TR BGCOLOR="'. html_get_alt_row_color($i) .'"><TD><PRE>
Date: '. date($sys_datefmt,db_result($result, $i, 'date')) .'
Sender: '. $email_arr[0] . '
'. util_line_wrap ( db_result($result, $i, 'body'),85,"\n"). '</PRE></TD></TR>';
		}
		echo '</TABLE>';
	} else {
		echo '
			<H3>No Followups Have Been Posted</H3>';
	}
}

function show_featurehistory ($feature_id) {
	/*
		show the feature_history rows that are relevant to this feature_id, excluding details
	*/
	global $sys_datefmt;
	$result= feature_data_get_history ($feature_id);
	$rows= db_numrows($result);

	if ($rows > 0) {

		$title_arr=array();
		$title_arr[]='Field';
		$title_arr[]='Old Value';
		$title_arr[]='Date';
		$title_arr[]='By';

		echo html_build_list_table_top ($title_arr);

		for ($i=0; $i < $rows; $i++) {
			$field=db_result($result, $i, 'field_name');
			echo '
			<TR BGCOLOR="'. html_get_alt_row_color($i) .'"><TD>'.$field.'</TD><TD>';

			if ($field == 'feature_status_id') {

				echo feature_data_get_status_name(db_result($result, $i, 'old_value'));

			} else if ($field == 'feature_category_id') {

				echo feature_data_get_category_name(db_result($result, $i, 'old_value'));

			} else if ($field == 'assigned_to') {

				echo user_getname(db_result($result, $i, 'old_value'));

			} else if ($field == 'close_date') {

				echo date($sys_datefmt,db_result($result, $i, 'old_value'));

			} else {

				echo db_result($result, $i, 'old_value');

		}
		echo '</TD>'.
			'<TD>'. date($sys_datefmt,db_result($result, $i, 'date')) .'</TD>'.
			'<TD>'. db_result($result, $i, 'user_name'). '</TD></TR>';
	}

	echo '
		</TABLE>';
	
	} else {
		echo '
			<H3>No Changes Have Been Made to This Feature Request</H3>';
	}
}

?>
