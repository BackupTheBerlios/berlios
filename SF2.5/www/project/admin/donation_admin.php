<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: donation_admin.php,v 1.1 2004/04/02 11:28:01 helix Exp $

require "pre.php";    // Initial db and session library, opens session
require('../../project/admin/project_admin_utils.php');

$is_admin_page='y';

if ($group_id && (user_ismember($group_id, 'F2'))) {
  /*
			Show main page for choosing 
			either moderotor or delete
  */
  project_admin_header(array('title'=>'Project Donation Management'));
	
  echo '
	  <H2>Project Donation Management</H2>
	  <ul>
	  <li><A HREF="project_donation_admin.php?group_id='.$group_id.'">Create/Update/Delete Project Donation Parameters for PayPal</A><BR>
      <li><A HREF="project_donors.php?group_id='.$group_id.'">Manage Project Donors</A>
	  <ul>
      <li><A HREF="project_donors.php?group_id='.$group_id.'&status=P">Donors in <b>P</b> (Pending) Status</A>
      <li><A HREF="project_donors.php?group_id='.$group_id.'&status=A">Donors in <b>A</b> (Approved) Status</A>
      <li><A HREF="project_donors.php?group_id='.$group_id.'&status=D">Donors in <b>D</b> (Deleted) Status</A>
      </ul></ul>';

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
