<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
// $Id: index.php,v 1.1 2004/03/16 14:51:57 helix Exp $

require('pre.php');
require('../feature_utils.php');

if ($group_id && user_ismember($group_id,'S2')) {

	if ($post_changes) {
		/*
			Update the database
		*/

		if ($feature_cat) {

			$sql="INSERT INTO feature_category (group_id,category_name) VALUES ('$group_id','$cat_name')";
			$result=db_query($sql);
			if (!$result) {
				$feedback .= ' Error inserting value ';
				echo db_error();
			} else {
				$feedback .= ' Feature Request Category Inserted ';
			}

		} else if ($create_canned) {

			$sql="INSERT INTO feature_canned_responses (group_id,title,body) VALUES ('$group_id','". addslashes(htmlspecialchars($title)). "','". addslashes(htmlspecialchars($body)). "')";
			$result=db_query($sql);
			if (!$result) {
				$feedback .= ' Error inserting value ';
				echo db_error();
			} else {
				$feedback .= ' Canned Response Inserted ';
			}

		} else if ($update_canned) {

			$sql="UPDATE feature_canned_responses SET title='". addslashes(htmlspecialchars($title)). "', body='". addslashes(htmlspecialchars($body)). "' ".
				"WHERE group_id='$group_id' AND feature_canned_id='$feature_canned_id'";
			$result=db_query($sql);
			if (!$result) {
				$feedback .= ' Error updating value ';
				echo db_error();
			} else {
				$feedback .= ' Canned Response Updated ';
			}

		} else if ($feature_cat_mod) {

			/*
				Update a feature category name
			*/
			$sql="UPDATE feature_category SET category_name = '$feature_cat_name' WHERE feature_category_id='$feature_cat_id' AND group_id='$group_id'";
			$result=db_query($sql);
			if (!$result || db_affected_rows($result) < 1) {
				$feedback .= ' Error modifying feature category name ';
			} else {
				$feedback .= ' Feature Category Name Modified ';
			}

		}

	} 
	/*
		Show UI forms
	*/

	if ($feature_cat) {
		/*
			Show categories and blank row
		*/
		feature_header(array ('title'=>'Add/Change Categories'));

		echo "<H2>Add Feature Request Categories</H2>";

		/*
			List of possible categories for this group
		*/
		$sql="select feature_category_id,category_name from feature_category WHERE group_id='$group_id'";
		$result=db_query($sql);
		echo "<P>";
		if ($result && db_numrows($result) > 0) {
			ShowResultSet($result,"Existing Categories","feature_cat");
		} else {
			echo "\n<H1>No feature categories in this group</H1>";
		}
		?>
		<P>
		Add a new feature category:
		<P>
		<FORM ACTION="<?php echo $PHP_SELF; ?>" METHOD="POST">
		<INPUT TYPE="HIDDEN" NAME="feature_cat" VALUE="y">
		<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="<?php echo $group_id; ?>">
		<INPUT TYPE="HIDDEN" NAME="post_changes" VALUE="y">
		<H3>New Category Name:</H3>
		<P>
		<INPUT TYPE="TEXT" NAME="cat_name" VALUE="" SIZE="15" MAXLENGTH="30"><BR>
		<P>
		<B><FONT COLOR="RED">Once you add a feature category, it cannot be deleted or modified</FONT></B>
		<P>
		<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="SUBMIT">
		</FORM>
		<?php

		feature_footer(array());

	} else if ($create_canned) {
		/*
			Show categories and blank row
		*/
		feature_header(array ('title'=>'Add/Change Canned Responses'));

		echo "<H2>Add Canned Responses</H2>";

		/*
			List of possible categories for this group
		*/
		$sql="SELECT feature_canned_id,title FROM feature_canned_responses WHERE group_id='$group_id'";
		$result=db_query($sql);
		$rows=db_numrows($result);
		echo "<P>";

		if ($result && $rows > 0) {
			//code to show existing responses and link to update page
			echo '
			<H3>Existing Responses:</H3>
			<P>';
			$title_arr=array();
			$title_arr[]='ID';
			$title_arr[]='Title';

			echo html_build_list_table_top ($title_arr);

			for ($i=0; $i < $rows; $i++) {
				echo '<TR BGCOLOR="'. html_get_alt_row_color($i) .'">'.
					'<TD>'.db_result($result, $i, 'feature_canned_id').'</TD>'.
					'<TD><A HREF="'.$PHP_SELF.'?update_canned=1&feature_canned_id='.
						db_result($result, $i, 'feature_canned_id').'&group_id='.$group_id.'">'.
						db_result($result, $i, 'title').'</A></TD></TR>';
			}
			echo '</TABLE>';

		} else {
			echo "\n<H1>No responses set up in this group</H1>";
		}
		?>
		<P>
		Creating useful generic messages can save you a lot of time when 
		handling common feature requests.
		<P>
		<FORM ACTION="<?php echo $PHP_SELF; ?>" METHOD="POST">
		<INPUT TYPE="HIDDEN" NAME="create_canned" VALUE="y">
		<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="<?php echo $group_id; ?>">
		<INPUT TYPE="HIDDEN" NAME="post_changes" VALUE="y">
		<b>Title:</b><BR>
		<INPUT TYPE="TEXT" NAME="title" VALUE="" SIZE="15" MAXLENGTH="30">
		<P>
		<B>Message Body:</B><BR>
		<TEXTAREA NAME="body" ROWS="30" COLS="65" WRAP="HARD"></TEXTAREA>
		<P>
		<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="SUBMIT">
		</FORM>
		<?php

		feature_footer(array());

	} else if ($update_canned) {
		/*
			Show categories and blank row
		*/
		feature_header(array ('title'=>'Update Canned Responses'));

		echo "<H2>Update Canned Responses</H2>";

		/*
			List of possible categories for this group
		*/
		$sql="SELECT feature_canned_id,title,body ".
			"FROM feature_canned_responses ".
			"WHERE group_id='$group_id' ".
			"AND feature_canned_id='$feature_canned_id'";

		$result=db_query($sql);
		echo "<P>";
		if (!$result || db_numrows($result) < 1) {
			echo "\n<H1>No responses set up in this group</H1>";
		} else {
			?>
			<P>
			Creating useful generic messages can save you a lot of time when
			handling common feature requests.
			<P>
			<FORM ACTION="<?php echo $PHP_SELF; ?>" METHOD="POST">
			<INPUT TYPE="HIDDEN" NAME="update_canned" VALUE="y">
			<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="<?php echo $group_id; ?>">
			<INPUT TYPE="HIDDEN" NAME="feature_canned_id" VALUE="<?php echo $feature_canned_id; ?>">
			<INPUT TYPE="HIDDEN" NAME="post_changes" VALUE="y">
			<b>Title:</b><BR>
			<INPUT TYPE="TEXT" NAME="title" VALUE="<?php echo stripslashes(db_result($result,0,'title')); ?>" SIZE="15" MAXLENGTH="30">
			<P>
			<B>Message Body:</B><BR>
			<TEXTAREA NAME="body" ROWS="30" COLS="65" WRAP="HARD"><?php echo stripslashes(db_result($result,0,'body')); ?></TEXTAREA>
			<P>
			<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="SUBMIT">
			</FORM>
			<?php
		}
		feature_footer(array());

	} else if ($feature_cat_mod) {

		/*
			Allow modification of a feature category
		*/
		feature_header(array('title'=>'Change a Feature Manager Category'));

		echo '
			<H2>Modify a Feature Category</H2>';

		$sql="SELECT feature_category_id,category_name FROM feature_category WHERE feature_category_id='$feature_cat_id' AND group_id='$group_id'";
		$result=db_query($sql);
		$rows=db_numrows($result);

		if($result && $rows > 0) {
			?>
			<P>
			<FORM ACTION="<?php echo $PHP_SELF ?>" METHOD="POST">
			<INPUT TYPE="HIDDEN" NAME="post_changes" VALUE="y">
			<INPUT TYPE="HIDDEN" NAME="feature_cat_mod" VALUE="y">
			<INPUT TYPE="HIDDEN" NAME="feature_cat_id" VALUE="<?php echo $feature_cat_id; ?>">
			<INPUT TYPE="HIDDEN" NAME="group_id" VALUE="<?php echo $group_id; ?>">
			<H3>Old Feature Category Name: <?php echo db_result($result, 0, 'category_name'); ?></H3>
			<P>
			<H3>New Feature Category Name:</H3>
			<P>
			<INPUT TYPE="TEXT" NAME="feature_cat_name" VALUE="<?php 
				echo db_result($result, 0, 'category_name'); ?>">
			<P>
			<B><FONT COLOR="RED">It is not recommended that you change the feature category name because other things are dependent upon it.</FONT></B>
			<P>
			<INPUT TYPE="SUBMIT" NAME="SUBMIT" VALUE="SUBMIT">
			</FORM>
			<?php
		} else {
			echo '
			<H1>The feature category that you requested a modification on was not found.</H1>';
		}

		feature_footer(array());

	} else {
		/*
			Show main page
		*/

		feature_header(array ('title'=>'Feature Manager Administration'));

		echo '
			<H2>Feature Manager Administration</H2>';

		echo '<P>
			<A HREF="'.$PHP_SELF.'?group_id='.$group_id.'&feature_cat=1">Add Feature Request Categories</A><BR>';
		echo "\nAdd categories of feature like, 'mail module','gant chart module','cvs', etc<P>";
		echo '<P>
			<A HREF="'.$PHP_SELF.'?group_id='.$group_id.'&create_canned=1">Add Canned Responses</A><BR>';
		echo "\nCreate/Change generic response messages for the feature tool.<P>";

		feature_footer(array());
	}

} else {

	//browse for group first message

	if (!$group_id) {
		exit_no_group();
	} else {
		exit_permission_denied();
	}

}
?>
