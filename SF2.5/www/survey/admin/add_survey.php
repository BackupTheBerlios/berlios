<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: add_survey.php,v 1.3 2003/11/27 15:05:42 helix Exp $

require('pre.php');
require($DOCUMENT_ROOT.'/survey/survey_utils.php');
$is_admin_page='y';

if ($group_id) {

if (!user_isloggedin() || !user_ismember($group_id,'A')) {
        exit_permission_denied();
	exit;
}

survey_header(array('title'=>'Add A Survey'));

if ($post_changes) {
	//$survey_questions=trim(ltrim($survey_questions));
	$sql="insert into surveys (survey_title,group_id,survey_questions) values ('$survey_title','$group_id','$survey_questions')";
	$result=db_query($sql);
	if ($result) {
		$feedback .= " Survey Inserted ";
	} else {
		$feedback .= " Error in Survey Insert ";
	}
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

<H2>Add a Survey</H2><P>

<FORM ACTION="<?php echo $PHP_SELF; ?>" METHOD="POST">

<B>Name of Survey:</B>
<BR>
<INPUT TYPE="TEXT" NAME="survey_title" VALUE="" LENGTH="60" MAXLENGTH="150"><P>
<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="<?php echo $group_id; ?>">
<INPUT TYPE="HIDDEN" NAME="post_changes" VALUE="y">
List question numbers, in desired order, separated by commas. <B>Refer to your list of questions</B> so you can view
the question id's. Do <B>not</B> include spaces or end your list with a comma.
<BR>
Ex: 1,2,3,4,5,6,7
<BR><INPUT TYPE="TEXT" NAME="survey_questions" VALUE="" LENGTH="90" MAXLENGTH="1500"><P>
<B>Is Active</B>
<BR><INPUT TYPE="RADIO" NAME="is_active" VALUE="1" CHECKED> Yes
<BR><INPUT TYPE="RADIO" NAME="is_active" VALUE="0"> No
<P>
<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="Add This Survey">
</FORM>  

<?php

/*
	Select this survey from the database
*/

$sql="SELECT * FROM surveys WHERE group_id='$group_id'";

$result=db_query($sql);

?>
<FORM>
<INPUT TYPE="BUTTON" NAME="none" VALUE="Show Existing Questions" ONCLICK="show_questions()">
</FORM>

<P>
<H2>Existing Surveys</H2>
<P>
<?php
ShowResultsEditSurvey($result);

survey_footer(array());

} else {
	exit_no_group();
}

?>
