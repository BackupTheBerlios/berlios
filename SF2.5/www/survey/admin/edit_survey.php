<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: edit_survey.php,v 1.3 2003/11/27 15:05:42 helix Exp $

require('pre.php');
require($DOCUMENT_ROOT.'/survey/survey_utils.php');
$is_admin_page='y';

if ($group_id) {

if (!user_isloggedin() || !user_ismember($group_id,'A')) {
	exit_permission_denied();
	exit;
}

survey_header(array('title'=>'Edit A Survey'));

if ($post_changes) {
	$sql="UPDATE surveys SET survey_title='$survey_title', survey_questions='$survey_questions', is_active='$is_active' ".
		"WHERE survey_id='$survey_id' AND group_id='$group_id'";
	$result=db_query($sql);
	if (db_affected_rows($result) < 1) {
		$feedback .= ' UPDATE FAILED ';
		echo db_error();
	} else {
		$feedback .= ' UPDATE SUCCESSFUL ';
	}
}

/*
	Get this survey out of the DB
*/
if ($survey_id) {
	$sql="SELECT * FROM surveys WHERE survey_id='$survey_id' AND group_id='$group_id'";
	$result=db_query($sql);
	$survey_title=db_result($result, 0, "survey_title");
	$survey_questions=db_result($result, 0, "survey_questions");
	$is_active=db_result($result, 0, "is_active");
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
var timerID2 = null;

function show_questions() {
        newWindow = open("","occursDialog","height=600,width=500,scrollbars=yes,resizable=yes");
        newWindow.location=('show_questions.php?group_id=<?php echo $group_id; ?>');
}

// -->
</script>

<H2>Edit a Survey</H2><P>

<H3><FONT COLOR="RED">WARNING! It is a bad idea to edit a survey after responses have been posted</FONT></H3>
<P>
If you change a survey after you already have responses, your results pages could be misleading or messed up.
<P>
<FORM ACTION="<?php echo $PHP_SELF; ?>" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="<?php echo $group_id; ?>">
<INPUT TYPE="HIDDEN" NAME="post_changes" VALUE="y">
<B>Name of Survey:</B>
<BR>
<INPUT TYPE="HIDDEN" NAME="survey_id" VALUE="<?php echo $survey_id; ?>">
<INPUT TYPE="TEXT" NAME="survey_title" VALUE="<?php echo $survey_title; ?>" LENGTH="60" MAXLENGTH="150">
<P>
<B>Questions:</B>
<BR>
List question numbers, in desired order, separated by commas. <B>Refer to your list of questions</B> so you can view 
the question id's. Do <B>not</B> include spaces or end your list with a comma.
<BR>
Ex: 1,2,3,4,5,6,7
<BR><INPUT TYPE="TEXT" NAME="survey_questions" VALUE="<?php echo $survey_questions; ?>" LENGTH="90" MAXLENGTH="1500"><P>
<B>Is Active</B>
<BR><INPUT TYPE="RADIO" NAME="is_active" VALUE="1"<?php if ($is_active=='1') { echo ' CHECKED'; } ?>> Yes
<BR><INPUT TYPE="RADIO" NAME="is_active" VALUE="0"<?php if ($is_active=='0') { echo ' CHECKED'; } ?>> No
<P>
<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="Submit Changes">
</FORM>  

<?php

/*
	Select all surveys from the database
*/

$sql="SELECT * FROM surveys WHERE group_id='$group_id'";

$result=db_query($sql);

?>
<P>
<FORM>
<INPUT TYPE="BUTTON" NAME="none" VALUE="Show Existing Questions" ONCLICK="show_questions()">
</FORM>
<P>
<H2>Existing Surveys</H2>
<?php

ShowResultsEditSurvey($result);

survey_footer(array());

} else {
	exit_no_group();
}

?>
