<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: add_feature.php,v 1.1 2004/03/16 14:51:57 helix Exp $

feature_header(array ('title'=>'Submit a Feature Request'));

	echo '
	<P>
	<H2>Submit A Feature Request</H2>
	<P>
	<B>Fill out the form below.</B> Fill in complete information and make sure 
	you include enough info that someone will be able to help you.
	<P>
	If you are requesting something that could affect security, <B>YOU MUST BE LOGGED IN</B>.
	<P>
	<FORM ACTION="'.$PHP_SELF.'" METHOD="POST" enctype="multipart/form-data">
	<INPUT TYPE="HIDDEN" NAME="func" VALUE="postaddfeature">
	<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="'.$group_id.'">
	<TABLE>
	<TR><TD VALIGN="TOP" COLSPAN="2"><B>For Project:</B><BR>'.group_getname($group_id).'</TD></TR>
	<TR><TD VALIGN="TOP" COLSPAN="2"><B>Category:</B><BR>';

	echo feature_category_box ($group_id,'feature_category_id');

	?>
	</TD></TR>

	<TR><TD COLSPAN="2"><B>Summary:</B><BR>
		<INPUT TYPE="TEXT" NAME="summary" SIZE="35" MAXLENGTH="40">
	</TD></TR>

	<TR><TD COLSPAN="2">
		<B>Detailed Description:</B>
		<P>
		<TEXTAREA NAME="details" ROWS="30" COLS="55" WRAP="HARD"></TEXTAREA>
	</TD></TR>

	<TR><TD COLSPAN="2">
	<?php 
	if (!user_isloggedin()) {
		echo '
		<h3><FONT COLOR="RED">Please <A HREF="/account/login.php">log in!</A></FONT></h3><BR>
		If you <B>cannot</B> login, then enter your email address here:<P>
		<INPUT TYPE="TEXT" NAME="user_email" SIZE="30" MAXLENGTH="35">
		';

	} 
	?>
		<P>
		<H3>DO NOT enter passwords in your message!</H3>
		<P>
		<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="SUBMIT REQUEST">
		</FORM>
		<P>
	</TD></TR>

	</TABLE>

<?php

feature_footer(array());

?>
