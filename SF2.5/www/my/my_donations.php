<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: my_donations.php,v 1.1 2004/04/02 11:20:48 helix Exp $

require ('pre.php');
require ('donate.php');

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

    echo site_user_header(array('title'=>'My Donations'));

?>

<h2>Donations from <?php print $G_SESSION->getUnixName(); ?></h2>

<h3>Donations to projects</h3>

<?php

	$res=db_query("SELECT * FROM group_donors WHERE user_id='".user_getid()."'");
	if (db_numrows($res) < 1) {
	  echo '<p>No project donations found.';
	} else {
	  $rows = db_numrows($res);
	
	  $title_arr = array();
	  $title_arr[] = 'Project';
	  $title_arr[] = 'Amount';
	  $title_arr[] = 'Status';
	  $title_arr[] = 'Date';
	  $title_arr[] = 'Comment';
  
	  echo html_build_list_table_top($title_arr);
  
	  echo "\n";

      for ($i=0; $i<$rows; $i++) {
		print '<tr bgcolor='. html_get_alt_row_color($i) .'>';
		
		$to_group_id = db_result($res,$i,'to_group_id');
		$res_grp=db_query("SELECT * FROM groups WHERE group_id='".$to_group_id."'");
		if (db_numrows($res_grp) < 1) {
		  print '<td valign="top">'. $to_group_id .'</td>';
		} else {
		  print '<td valign="top"><a href="/projects/'. db_result($res_grp,0,'unix_group_name') .'">'. db_result($res_grp,0,'group_name') .'</a>'.req_project_donate(db_result($res_grp,0,'group_id')).'</td>';
		}

		print '<td align="right" valign="top">&euro;'. db_result($res,$i,'amount') .',00</td>';
		print '<td align="center" valign="top">'. db_result($res,$i,'status') .'</td>';
		print '<td align="center" valign="top">'. date($sys_datefmt,db_result($res,$i,'add_date')) .'</td>';
		print '<td valign="top">'. db_result($res,$i,'comment') .'</td>';
		print '</tr>';
	  }
	  print '</td></tr></table>';
	}
?>

<h3>Donations to developers</h3>

<?php
	$res=db_query("SELECT * FROM user_donors WHERE user_id='".user_getid()."'");
	if (db_numrows($res) < 1) {
	  echo '<p>No developer donations found.';
	} else {
	  $rows = db_numrows($res);

	  $title_arr = array();
	  $title_arr[] = 'Developer';
	  $title_arr[] = 'Amount';
	  $title_arr[] = 'Status';
	  $title_arr[] = 'Date';
	  $title_arr[] = 'Comment';
  
	  echo html_build_list_table_top($title_arr);
  
	  echo "\n";

      for ($i=0; $i<$rows; $i++) {
		print '<tr bgcolor='. html_get_alt_row_color($i) .'>';

		$to_user_id = db_result($res,$i,'to_user_id');
		$res_usr=db_query("SELECT * FROM users WHERE user_id='".$to_user_id."'");
		if (db_numrows($res_usr) < 1) {
		  print '<td valign="top">'. $to_user_id .'</td>';
		} else {
		  print '<td valign="top"><a href="/users/'. db_result($res_usr,0,'user_name') .'">'. db_result($res_usr,0,'user_name') .'</a>'.is_project_donor(db_result($res_usr,0,'user_id')).is_user_donor(db_result($res_usr,0,'user_id')).req_user_donate(db_result($res_usr,0,'user_id')).'</td>';
		}

		print '<td align="right" valign="top">&euro;'. db_result($res,$i,'amount') .',00</td>';
		print '<td align="center" valign="top">'. db_result($res,$i,'status') .'</td>';
		print '<td align="center" valign="top">'. date($sys_datefmt,db_result($res,$i,'add_date')) .'</td>';
		print '<td valign="top">'. db_result($res,$i,'comment') .'</td>';
		print '</tr>';
	  }
	  print '</td></tr></table>';
	}

	echo site_user_footer(array());

} else {
	exit_not_logged_in();
}
?>
