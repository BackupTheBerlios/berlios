<?php
//
// BerliOS: The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://www.berlios.de
//
// $Id: project_donors.php,v 1.1 2004/04/02 11:28:01 helix Exp $

require_once('pre.php');
require('donate.php');
require('../../project/admin/project_admin_utils.php');

$is_admin_page='y';

if ($group_id && (user_ismember($group_id, 'F2'))) {

project_admin_header(array('title'=>'Donation Administration'));

echo '<h2>List of Project Donors</h2>';

// Administrative functions for Donations

/*
	Set donation to approved
*/
if ($action=='approve') {
	db_query("UPDATE group_donors SET status='A' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p><font color=red>Update successfull</font>';
}

/*
	Set donation to deleted
*/
if ($action=='delete') {
	db_query("UPDATE group_donors SET status='D' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p><font color=red>Update successfull</font>';
}


/*
	Set donation to pending
*/
if ($action=='pending') {
	db_query("UPDATE group_donors SET status='P' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p><font color=red>Update successfull</font>';
}


/*
	Show list of donations
*/
$donorsperpage = 20;

$where_status = "";

if ($status=='P' || $status=='A' || $status=='D') {
  $where_status = "AND group_donors.status='".$status."'";
}

if ( !$offset || $offset < 0 ) {
  $offset = 0;
}

$query = "SELECT group_donors.*,users.user_name "
  . "FROM group_donors,users "
  . "WHERE group_donors.user_id=users.user_id "
  . "AND group_donors.to_group_id=$group_id "
  . $where_status ." "
  . "ORDER BY group_donors.add_date DESC";

$res = db_query($query,($donorsperpage+1),$offset);

$rows=db_numrows($res);

if (!$res || db_numrows($res) < 1) {
  if (!$res) {
	print "<p><font bgcolor=\"\">Database Access Failed ".db_error()."</font>\n";
  }
  print "<h1>No new donors found.</h1>";
} else {
  if ( db_numrows($res) > $donorsperpage ) {
	$rows =  $donorsperpage;
  } else {
	$rows = db_numrows($res);
  }
  echo "<p>Donors List";
  if ($status == "") {
	echo ":\n";
  } else {
	echo " in Status: <b>$status</b>\n";
  }

  $title_arr = array();
  $title_arr[] = 'Login name';
  $title_arr[] = 'Date';
  $title_arr[] = 'Comment';
  $title_arr[] = 'Amount';
  $title_arr[] = 'Status';
  $title_arr[] = 'Actions';
  
  echo html_build_list_table_top($title_arr);
  
  echo "\n";

  for ($i=0; $i<$rows; $i++) {
	print '<tr bgcolor="'. html_get_alt_row_color($i).'">';
	print '<td valign="top"><a href="/users/'. db_result($res,$i,'user_name') .'">'. db_result($res,$i,'user_name') .'</a>'.is_project_donor(db_result($res,$i,'user_id')).is_user_donor(db_result($res,$i,'user_id')).req_user_donate(db_result($res,$i,'user_id')).'</td>';
	print '<td valign="top">'. date($sys_datefmt,db_result($res,$i,'add_date')) .'</td>';
	print '<td valign="top">'. db_result($res,$i,'comment') .'</td>';
	print '<td valign="top" align="right">&euro;'. db_result($res,$i,'amount') .',00</td>';
	print '<td valign="top" align="middle">'. db_result($res,$i,'status') .'</td>';
	print '<td valign="top">';
    print '<a href="?status='.$status.'&action=approve&group_id='.$group_id.'&user_id='.db_result($res,$i,'user_id').'&date='.db_result($res,$i,'add_date').'&offset='.$offset.'">[Approve]</a> ';
    print '<a href="?status='.$status.'&action=delete&group_id='.$group_id.'&user_id='.db_result($res,$i,'user_id').'&date='.db_result($res,$i,'add_date').'&offset='.$offset.'">[Delete]</a> ';
    print '<a href="?status='.$status.'&action=pending&group_id='.$group_id.'&user_id='.db_result($res,$i,'user_id').'&date='.db_result($res,$i,'add_date').'&offset='.$offset.'">[Pending]</a>';
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

project_admin_footer(array());

} else {
  /*
     Not logged in or insufficient privileges
  */
  if (!$group_id) {
	exit_no_group();
  } else {
	exit_permission_denied();
  }
}

?>
