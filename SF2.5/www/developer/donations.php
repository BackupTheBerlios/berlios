<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: donations.php,v 1.1 2004/04/02 10:42:24 helix Exp $

require('pre.php');
require('donate.php');

if (isset($user_id)) {
  $res=db_query("SELECT * FROM users WHERE user_id='".$user_id."'");
  if (db_numrows($res) < 1) {
	echo db_error();
	exit_error("Invalid User","That user does not exist.");
  }

  $res=db_query("SELECT * FROM user_donate WHERE user_id='".$user_id."'");
  if (db_numrows($res) < 1) {
	echo db_error();
	exit_error("Invalid User","That user does not request for donation.");
  }

  $user=&user_get_object($user_id);

  $donorsperpage = 10;

  if ( !$offset || $offset < 0 ) {
	$offset = 0;
  }

  $HTML->header (array('title'=>'View User Donations'));
?>

<h2><a href="/users/<?php echo $user->getUnixName(); ?>"><?php echo $user->getUnixName(); ?></a> Supporters</h2>

<p>Information provided (by this user or project's admin) about donations:</p>

<blockquote>
<p><b><?php echo db_result($res,0,'comment'); ?></b></p>
</blockquote>

<p><a href="make_donation.php?user_id=<?php echo $user_id; ?>">Donate to <?php echo $user->getUnixName(); ?></a>.

<hr>
<h3>Donations</h3>

<?php
  $query = 'SELECT user_donors.*,users.user_name '
    . 'FROM user_donors,users '
    . 'WHERE user_donors.user_id=users.user_id '
    . "AND user_donors.status IN ('A') " 
    . "AND user_donors.to_user_id ='".$user_id."' "
    . "AND users.donor_display='1' "
    . 'ORDER BY user_donors.add_date ASC';

  $res = db_query($query,($donorsperpage+1),$offset);

  $rows=db_numrows($res);

  if (!$res || db_numrows($res) < 1) {
    if (!$res) {
	  print "<p><font bgcolor=\"\">Database Access Failed ".db_error()."</font>\n";
    }
    print "<p>No one has donated to ".$user->getUnixName().". You could be the first donor!</p>";
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

  $HTML->footer (array());

} else {
  exit_error("Invalid User","No user specified.");
}
?>
