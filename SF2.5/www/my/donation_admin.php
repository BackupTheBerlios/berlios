<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: donation_admin.php,v 1.1 2004/04/02 11:20:48 helix Exp $

require "pre.php";    // Initial db and session library, opens session

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

site_user_header(array('title'=>"My Donation Management"));


	echo '
	  <H2>My Donation Management</H2>
	  <ul>
	  <li><A HREF="my_donation_admin.php">Create/Update/Delete my Donation Parameters for PayPal</A><BR>
      <li><A HREF="my_donors_admin.php">Manage my Donors</A>
	  <ul>
      <li><A HREF="my_donors_admin.php?status=P">Donors in <b>P</b> (Pending) Status</A>
      <li><A HREF="my_donors_admin.php?status=A">Donors in <b>A</b> (Approved) Status</A>
      <li><A HREF="my_donors_admin.php?status=D">Donors in <b>D</b> (Deleted) Status</A>
      </ul>
      <li><A HREF="my_donations.php">My Donations</A>
      </ul>';

site_user_footer(array());

} else {

  exit_not_logged_in();

}
?>
