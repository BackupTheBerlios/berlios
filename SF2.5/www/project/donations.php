<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: donations.php,v 1.1 2004/04/02 11:31:10 helix Exp $

require "pre.php";       // Initial db and session library, opens session
require "donate.php";

$res_grp=db_query("SELECT * FROM groups WHERE group_id='".$group_id."'");
if (db_numrows($res_grp) < 1) {

  echo db_error();
  exit_error("Invalid Group","That group does not exist.");
}

$group_name=db_result($res_grp,0,'group_name');
$unix_group_name=db_result($res_grp,0,'unix_group_name');

$res_don=db_query("SELECT * FROM group_donate WHERE group_id='".$group_id."'");
if (db_numrows($res_don) < 1) {
  echo db_error();
  exit_error("Invalid Project","That project does not request for donation.");
}

$comment=db_result($res_don,0,'comment');

$donorsperpage = 10;

if ( !$offset || $offset < 0 ) {
        $offset = 0;
}

site_project_header(array('title'=>'Project Supporters','group'=>$group_id,'toptab'=>'donation'));

?>

<h2>Supporters of the Project: <?php echo $group_name; ?> (<?php echo $unix_group_name; ?>)</h2>

<p>Information provided (by this user or project's admin) about donations:</p>

<blockquote>
<p><b><?php echo $comment; ?></b></p>
</blockquote>

<p><a href="/project/make_donation.php?group_id=4">Donate to <?php echo $group_name; ?> (<?php echo $unix_group_name; ?>)</a>.

<h3>The following users have made contributions to <?php echo $group_name; ?> (<?php echo $unix_group_name; ?>):</h3>

<?php
for ($i=0; $i<3; $i++) {
  $from = mktime(0, 0, 0, date("m")-$i, 1, date("Y"));
  $to = mktime(23, 59, 59, date("m")-$i, 31, date("Y"));
  $query = "SELECT group_donors.*,users.user_name "
    . "FROM group_donors,users "
    . "WHERE group_donors.user_id=users.user_id "
    . "AND group_donors.status IN ('A') " 
    . "AND group_donors.add_date >= $from AND group_donors.add_date <= $to "
    . "AND users.donor_display='1' "
    . "ORDER BY group_donors.add_date ASC";

  $res = db_query($query);

  $rows=db_numrows($res);

  if ($rows > 0) {
	print "<table>\n";
	print "<tr><td valign=\"top\"><b>". date("F", $from) ."&nbsp;". date("Y", $from) .":</b></td><td valign=\"top\">";
	for ($k=0; $k<$rows; $k++) {
	  if ( $k > 0 ) print ', ';
	  print "<a href=\"/users/". db_result($res,$k,'user_name') ."\">". db_result($res,$k,'user_name') ."</a>".is_project_donor(db_result($res,$k,'user_id')).is_user_donor(db_result($res,$k,'user_id')).req_user_donate(db_result($res,$k,'user_id'))."";
	}
	print "</td></tr></table>\n";
  }
}
?>

<p>We would also like to thank our numerous anonymous contributers.</p>

<h3>Comments submitted by contributers (1 to <?php echo $donorsperpage; ?>):</h3>

<?php
$query = 'SELECT group_donors.*,users.user_name '
  . 'FROM group_donors,users '
  . 'WHERE group_donors.user_id=users.user_id '
  . "AND group_donors.status IN ('A') " 
  . "AND users.donor_display='1' "
  . 'ORDER BY group_donors.add_date ASC';

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

  $title_arr = array();
  $title_arr[] = 'Login name';
  $title_arr[] = 'Date';
  $title_arr[] = 'Comment';
  
  echo html_build_list_table_top($title_arr);
  
  echo "\n";

  for ($i=0; $i<$rows; $i++) {
	print '<tr bgcolor='. html_get_alt_row_color($i) .'>';
	print '<td valign="top"><a href="/users/'. db_result($res,$i,'user_name') .'">'. db_result($res,$i,'user_name') .'</a>'.is_project_donor(db_result($res,$i,'user_id')).is_user_donor(db_result($res,$i,'user_id')).req_user_donate(db_result($res,$i,'user_id')).'</td>';
	print '<td align="center" valign="top">'. date($sys_datefmt,db_result($res,$i,'add_date')) .'</td>';
	print '<td valign="top">'. db_result($res,$i,'comment') .'</td>';
	print '</tr>';
  }

  print '<tr bgcolor="#D1D5D7" background="/themes/forged/images/steel3.jpg"><td colspan="2">';
  if ($offset != 0) {
	print '<b><a href="?offset='.($offset-$donorsperpage).'">' .
	  html_image("images/t2.gif","15","15",array("border"=>"0","align"=>"middle")) .
	  ' Newer Donations</a></b>';
  }

  print '</td><td align="right" colspan="2">';
  if (db_numrows($res)>$rows) {
	print '<b><a href="?offset='.($offset+$donorsperpage).'">Older Donations ' .
	  html_image("images/t.gif","15","15",array("border"=>"0","align"=>"middle")) .
	  '</a></b>';
  }
  print '</td></tr></table>';
}

$HTML->footer(array());
?>
