<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: detail_feature.php,v 1.1 2004/03/16 14:51:57 helix Exp $

feature_header(array ('title'=>'Feature Request Detail: '.$feature_id));

$sql="SELECT feature.summary,users.user_name AS submitted_by,feature.priority,".
	"user2.user_name AS assigned_to,feature_status.status_name,feature.open_date,feature_category.category_name ".
	"FROM feature,users,users user2,feature_category,feature_status ".
	"WHERE feature.submitted_by=users.user_id ".
	"AND feature.assigned_to=user2.user_id ".
	"AND feature.feature_status_id=feature_status.feature_status_id ".
	"AND feature.feature_category_id=feature_category.feature_category_id ".
	"AND feature.feature_id='$feature_id'";

$result=db_query($sql);

if (db_numrows($result) > 0) {

	echo '
		<H2>[ Feature Request #'.$feature_id.' ] '.db_result($result,0,'summary').'</H2>

	<TABLE CELLPADDING="0" WIDTH="100%">
		<TR>
			<TD><B>Date:</B><BR>'.date($sys_datefmt,db_result($result,0,'open_date')).'</TD>
			<TD><B>Priority:</B><BR>'.db_result($result,0,'priority').'</TD>
		</TR>

		<TR>
			<TD><B>Submitted By:</B><BR>'.db_result($result,0,'submitted_by').'</TD>
			<TD><B>Assigned To:</B><BR>'.db_result($result,0,'assigned_to').'</TD>
		</TR>

		<TR>
			<TD><B>Category:</B><BR>'.db_result($result,0,'category_name').'</TD>
			<TD><B>Status:</B><BR>'.db_result($result,0,'status_name').'</TD>
		</TR>

		<TR><TD COLSPAN="2"><B>Summary:</B><BR>'.db_result($result,0,'summary').'</TD></TR>';

	echo '
		<FORM ACTION="'.$PHP_SELF.'" METHOD="POST">

		<TR><TD COLSPAN="2">
			<FORM ACTION="'.$PHP_SELF.'" METHOD="POST">
			<INPUT TYPE="HIDDEN" NAME="func" VALUE="postaddcomment">
			<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="'.$group_id.'">
			<INPUT TYPE="HIDDEN" NAME="feature_id" VALUE="'.$feature_id.'">
			<P>
			<B>Add A Comment:</B><BR>
			<TEXTAREA NAME="details" ROWS="10" COLS="60" WRAP="HARD"></TEXTAREA>
		</TD></TR>

		<TR><TD COLSPAN="2">';

	if (!user_isloggedin()) {
		echo '
			<h3><FONT COLOR="RED">Please <A HREF="/account/login.php">log in!</A></FONT></h3><BR>
			If you <B>cannot</B> login, then enter your email address here:<P>
			<INPUT TYPE="TEXT" NAME="user_email" SIZE="30" MAXLENGTH="35">';

	}

	echo '
			<P>
			<H3>DO NOT enter passwords in your message!</H3>
			<P>
			<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="SUBMIT">
			</FORM>
		</TD></TR>
		<P>

		<TR><TD COLSPAN="2">';

	echo show_feature_details($feature_id);

	?>

		<TR><TD COLSPAN="2">
	<?php

	show_featurehistory($feature_id);

	?>
		</TD></TR>
	</TABLE>
	<?php

} else {

	echo '
		<H1>Feature Request not found</H1>
	<P>
	<B>You can get this message</B> if this Project did not create feature groups/categories. 
	An admin for this project must create feature groups/categories and then modify this feature.';
	echo db_error();

}

feature_footer(array());

?>
