<?php
//
// BerliOS: The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://www.berlios.de
//
// $Id: my_donors_admin.php,v 1.2 2004/04/02 12:36:17 helix Exp $

require "pre.php";    
require "donate.php";    

global $G_SESSION;

if (user_isloggedin() || $sf_user_hash) {

  /*
   *  If user has valid "remember-me" hash, instantiate not-logged in
   *  session for one.
   */
  if (!user_isloggedin()) {
	list($user_id,$hash)=explode('_',$sf_user_hash);
	$sql="SELECT * 
          FROM users 
		  WHERE user_id='".$user_id."' AND user_pw LIKE '".$hash."%'";

	$result=db_query($sql);
	$rows=db_numrows($result);
	if (!$result || $rows != 1) {
	  exit_not_logged_in();
	}
	$user_id=db_result($result,0,'user_id');
	$G_SESSION=user_get_object($user_id,$result);
  }

  $user_id = $G_SESSION->getUserID();
 
  echo site_user_header(array('title'=>'My Donors Management'));

  echo '<h2>List of my Donors</h2>';

// Administrative functions for Donations

/*
	Set donation to approved
*/
  if ($action=='approve') {
	db_query("UPDATE user_donors SET status='A' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p><font color=red>Update successfull</font>';
  }

/*
	Set donation to deleted
*/
  if ($action=='delete') {
	db_query("UPDATE user_donors SET status='D' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p><font color=red>Update successfull</font>';
  }


/*
	Set donation to pending
*/
  if ($action=='pending') {
	db_query("UPDATE user_donors SET status='P' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p><font color=red>Update successfull</font>';
}


/*
	Show list of donations
*/
$donorsperpage = 20;

$where_status = "";

if ($status=='P' || $status=='A' || $status=='D') {
  $where_status = "AND user_donors.status='".$status."'";
}

if ( !$offset || $offset < 0 ) {
  $offset = 0;
}

$query = "SELECT user_donors.*,users.user_name "
  . "FROM user_donors,users "
  . "WHERE user_donors.user_id=users.user_id "
  . "AND user_donors.to_user_id=$user_id "
  . $where_status ." "
  . "ORDER BY user_donors.add_date DESC";

$res = db_query($query,($donorsperpage+1),$offset);

$rows=db_numrows($res);

if (!$res || db_numrows($res) < 1) {
  if (!$res) {
	print "<p><font bgcolor=\"\">Database Access Failed ".db_error()."</font>\n";
  }
  print "<h1>No donors found.</h1>";
} else {
  if ( db_numrows($res) > $donorsperpage ) {
	$rows =  $donorsperpage;
  } else {
	$rows = db_numrows($res);
  }
  echo "<h3>My Donors";
  if ($status == "") {
	echo "</h3>\n";
  } else {
	echo " in Status: $status</h3>\n";
  }

  $title_arr = array();
  $title_arr[] = 'Login name';
  $title_arr[] = 'Amount';
  $title_arr[] = 'Status';
  $title_arr[] = 'Date';
  $title_arr[] = 'Comment';
  $title_arr[] = 'Actions';
  
  echo html_build_list_table_top($title_arr);
  
  echo "\n";

  for ($i=0; $i<$rows; $i++) {
	print '<tr bgcolor="'. html_get_alt_row_color($i).'">';
	print '<td valign="top"><a href="/users/'. db_result($res,$i,'user_name') .'">'. db_result($res,$i,'user_name') .'</a>'.is_project_donor(db_result($res,$i,'user_id')).is_user_donor(db_result($res,$i,'user_id')).req_user_donate(db_result($res,$i,'user_id')).'</td>';
	print '<td valign="top" align="right">&euro;'. db_result($res,$i,'amount') .',00</td>';
	print '<td valign="top" align="middle">'. db_result($res,$i,'status') .'</td>';
	print '<td valign="top">'. date($sys_datefmt,db_result($res,$i,'add_date')) .'</td>';
	print '<td valign="top">'. db_result($res,$i,'comment') .'</td>';
	print '<td valign="top">';
    print '<a href="?status='.$status.'&action=approve&user_id='.db_result($res,$i,'user_id').'&date='.db_result($res,$i,'add_date').'&offset='.$offset.'">[Approve]</a> ';
    print '<a href="?status='.$status.'&action=delete&user_id='.db_result($res,$i,'user_id').'&date='.db_result($res,$i,'add_date').'&offset='.$offset.'">[Delete]</a> ';
    print '<a href="?status='.$status.'&action=pending&user_id='.db_result($res,$i,'user_id').'&date='.db_result($res,$i,'add_date').'&offset='.$offset.'">[Pending]</a>';
	print "</td></tr>\n";
  }
  print "</table>\n";

  print '<table><tr><td>';
  if ($offset != 0) {
	print '<b><a href="?offset='.($offset-$donorsperpage).'">' .
	  html_image("images/t2.gif","15","15",array("border"=>"0","align"=>"middle")) .
	  ' Newer Donations</a></b>';
  } else {
	print '&nbsp;';
  }

  print '</td><td align="right">';
  if (db_numrows($res)>$rows) {
	print '<b><a href="?offset='.($offset+$donorsperpage).'">Older Donations ' .
	  html_image("images/t.gif","15","15",array("border"=>"0","align"=>"middle")) .
	  '</a></b>';
  } else {
	print '&nbsp;';
  }
  print "</td></tr></table>\n";
}

 echo site_user_footer(array());

} else {

  exit_not_logged_in();

}

?>
