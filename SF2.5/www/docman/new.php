<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 1999-2000 (c) The SourceForge Crew
// http://sourceforge.net
//
//

/*
	Docmentation Manager
	by Quentin Cregan, SourceForge 06/2000
*/


require('doc_utils.php');
require('pre.php');

if($group_id) {

  if ($mode == "add"){
	
	if (!$doc_group || $doc_group == 100) {
	  //cannot add a doc unless an appropriate group is provided
	  exit_error('Error','No Valid Document Group Was Selected');
	}
	
	if (!$title || !$description) { 
	  exit_missing_param();
	}
	
	if (!$upload_instead && !$data) {
	  exit_missing_param();
	}
	
	if (!user_isloggedin()) {
	  $user_id=100;
	} else {
	  $user_id=user_getid();
	}
	
	if ($upload_instead) {
	  
	  $filename = $_FILES['uploaded_data']['name'];
	  $type = $_FILES['uploaded_data']['type'];
	  $size = $_FILES['uploaded_data']['size'];
	  $tmp_name = $_FILES['uploaded_data']['tmp_name'];
	  $error = $_FILES['uploaded_data']['error'];
	  print "<p>$filename<br>$type<br>$size<br>$tmp_name<br>$error\n";

	  if ($error == UPLOAD_ERR_INI_SIZE) {
		$feedback .= ' ERROR - The uploaded file exceeds the maximal allowed size';
		exit_error('Missing Info',$feedback.' - Please click back and fix the error.');
	  } elseif ($error == UPLOAD_ERR_FORM_SIZE) {
		$feedback .= ' ERROR - The uploaded file exceeds the maximal allowed size';
		exit_error('Missing Info',$feedback.' - Please click back and fix the error.');
	  } elseif ($error == UPLOAD_ERR_PARTIAL) {
		$feedback .= ' ERROR - The uploaded file was only partially uploaded';
		exit_error('Missing Info',$feedback.' - Please click back and try again.');
	  }
	  
	  $datab = pg_escape_bytea(fread(fopen($tmp_name, 'r'),$size));
	  $data = "";

	  $feedback .= ' Document Uploaded ';
	} else {
	  $data = htmlspecialchars($data);
	  $datab = "";
	  $type = "text/html";
	}
	
	docman_header('Documentation - Add Information - Processing','Documentation - New submission');
	
	$query = "insert into doc_data(stateid,title,data,datab,createdate,updatedate,created_by,doc_group,description,language_id,type,filename) "
	  ."values('3',"
	  // state = 3 == pending
	  ."'".htmlspecialchars($title)."',"
	  ."'".$data."',"
	  ."'".$datab."',"
	  ."'".time()."',"
	  ."'".time()."',"
	  ."'".$user_id."',"
	  ."'".$doc_group."',"			
	  ."'".htmlspecialchars($description)."',"
	  ."'".$language_id."',"
	  ."'".$type."',"
	  ."'".$filename."')";
	
	db_query($query);
	// print "<p>$query\n";
	//PROBLEM check the query

	print "<p><b>Thank You!  Your submission has been placed in the database for review before posting.</b> \n\n<p>\n <a href=\"/docman/index.php?group_id=".$group_id."\">Back</a>"; 
	
	docman_footer($params);
  } else {
	docman_header('Add documentation','Add documentation');
	if ($user == 100) {
	  print "<p>You are not logged in, and will not be given credit for this.<p>";
	}
	
	echo '
			<p>

			<b> Document Title: </b> Refers to the relatively brief title of the document (e.g. How to use the download server)
			<br>
			<b> Description: </b> A brief description to be placed just under the title.<br>

			<form name="adddata" action="new.php?mode=add&group_id='.$group_id.'" method="POST" enctype="multipart/form-data">

			<table border="0" width="75%">

			<tr>
			<th>Document Title:</th>
			<td><input type="text" name="title" size="40" maxlength="255"></td>

			</tr>
			<tr>
			<th>Description:</th> 
			<td><input type="text" name="description" size="50" maxlength="255"></td>
			</tr>

			<tr>
			<th> Language:</th>
			<td>';
	
	echo html_get_language_popup($Language,'language_id',1);
	
	echo	'</td>
			</tr>

			<tr>
			<th> <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                 <input type="checkbox" name="upload_instead" value="1"> <B>Upload File:</B></th>
			<td> <input type="file" name="uploaded_data" size="30"></td>
			</tr>

			<tr>
			<th>OR Paste Document (in html format):</th>
			<td><textarea cols="60" rows="10" name="data"></textarea></td>
			</tr>

			<tr>
			<th>Group that document belongs in:</th>
			<td>';
	
	display_groups_option($group_id);
	
	echo '	</td> </tr> </table>

			<input type="submit" value="Submit Information">

			</form> '; 
	
	docman_footer($params);
  } // end else.
  
} else {
  exit_no_group();
}

?>
