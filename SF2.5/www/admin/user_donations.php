<?php
//
// BerliOS: The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://www.berlios.de
//
// $Id: user_donations.php,v 1.1 2004/04/02 10:40:21 helix Exp $

require "pre.php";    
session_require(array('group'=>'1','admin_flags'=>'A'));
$HTML->header(array('title'=>'Alexandria: User Donations'));

echo '<H2>List of User Donations</H2>';
echo "<p><a href=\"/admin/\">Site Admin Home</a>\n";

// Administrative functions for Donations

/*
	Set donation to approved
*/
if ($action=='approve') {
	db_query("UPDATE user_donors SET status='A' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p>Donation from '.$user_id.' at '.date($sys_datefmt,$date).' ('.$date.') is updated to APPROVED status';
}

/*
	Set donation to deleted
*/
if ($action=='delete') {
	db_query("UPDATE user_donors SET status='D' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p>Donation from '.$user_id.' at '.date($sys_datefmt,$date).' ('.$date.') is updated to DELETED status';
}


/*
	Set donation to pending
*/
if ($action=='pending') {
	db_query("UPDATE user_donors SET status='P' WHERE user_id='$user_id' AND add_date='$date'");
	echo '<p>Donation from '.$user_id.' at '.date($sys_datefmt,$date).' ('.$date.') is updated to PENDING status';
}


/*
	Show list of donations
*/
$donorsperpage = 20;

$where_status = "";

if ($status=='P' || $status=='A' || $status=='D') {
  $where_status = "AND donors.status='".$status."'";
}

if ( !$offset || $offset < 0 ) {
  $offset = 0;
}

$query = 'SELECT user_donors.*,users.user_name '
  . 'FROM user_donors,users '
  . 'WHERE user_donors.user_id=users.user_id '
  . $where_status .' '
  . 'ORDER BY user_donors.add_date DESC';

$res = db_query($query,($donorsperpage+1),$offset);

$rows=db_numrows($res);

if (!$res || db_numrows($res) < 1) {
  if (!$res) {
	print "<p><font bgcolor=\"\">Database Access Failed ".db_error()."</font>\n";
  }
  print "<h1>No new donations found.</h1>";
} else {
  if ( db_numrows($res) > $donorsperpage ) {
	$rows =  $donorsperpage;
  } else {
	$rows = db_numrows($res);
  }
  echo "<p>Donations List in Status: <b>$status</b>\n";
?>
<p>
<table border="1">
<tr>
<th><b>Login name</b></th>
<th>User ID</th>
<th>To User ID</th>
<th>Date</th>
<th>Comment</th>
<th>Amount</th>
<th>Status</th>
<th>Actions</th>
</tr>
<?php
  for ($i=0; $i<$rows; $i++) {
	print '<tr>';
	print '<td valign="top"><a href="/users/'. db_result($res,$i,'user_name') .'">'. db_result($res,$i,'user_name') .'</a></td>';
	print '<td valign="top" align="right">'. db_result($res,$i,'user_id') .'</td>';
	print '<td valign="top" align="right">'. db_result($res,$i,'to_user_id') .'</td>';
	print '<td valign="top">'. date($sys_datefmt,db_result($res,$i,'add_date')) .'</td>';
	print '<td valign="top">'. db_result($res,$i,'comment') .'</td>';
	print '<td valign="top" align="right">'. db_result($res,$i,'amount') .'</td>';
	print '<td valign="top" align="middle">'. db_result($res,$i,'status') .'</td>';
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

$HTML->footer(array());

?>
