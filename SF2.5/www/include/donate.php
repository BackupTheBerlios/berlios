<?php
//
// BerliOS : The Open Source Mediator
// Copyright 2000-2004 (c) The BerliOS Crew
// http://developer.berlios.de
//
// $Id: donate.php,v 1.1 2004/04/02 10:55:08 helix Exp $

function display_donor($user_id) {
  $display = 0;
  $query = "SELECT display_donor "
	. "FROM users "
	. "WHERE user_id=$user_id "
	. "AND status='A'";
  $res = db_query($query);
  if ($res && db_numrows($res) > 0) {
	$display = 1;
  }
  return $display;
}

function is_project_donor($user_id) {
  $donor = "";
  $query = "SELECT to_group_id "
	. "FROM group_donors "
	. "WHERE user_id=$user_id "
	. "AND status='A'";
  $res = db_query($query);
  if ($res && db_numrows($res) > 0) {
	$donor = '&nbsp;<a href="/help/user_icon_legend.php?user_id='.$user_id.'"><img src="/images/iconRedStar_16x16.png" alt="Project Donor" border="0" width="16" height="16"></a>';
  }
  return $donor;
}

function is_user_donor($user_id) {
  $donor = "";
  $query = "SELECT to_user_id "
	. "FROM user_donors "
	. "WHERE user_id=$user_id";
  $res = db_query($query);
  if ($res && db_numrows($res) > 0) {
	$donor = '&nbsp;<a href="/help/user_icon_legend.php?user_id='.$user_id.'"><img src="/images/iconYellowStar_16x16.png" alt="User Donor" border="0" width="16" height="16"></a>';
  }
  return $donor;
}

function req_project_donate($group_id) {
  $donate = "";
  $query = "SELECT group_id "
	. "FROM group_donate "
	. "WHERE group_id=$group_id";
  $res = db_query($query);
  if ($res && db_numrows($res) > 0) {
	$donate = '&nbsp;<a href="/project/make_donation.php?group_id='.$group_id.'"><img src="/images/iconPurpleStar_16x16.png" alt="Project Donate" border="0" width="16" height="16">&euro;</a>';
  }
  return $donate;
}

function req_user_donate($user_id) {
  $donate = "";
  $query = "SELECT user_id "
	. "FROM user_donate "
	. "WHERE user_id=$user_id";
  $res = db_query($query);
  if ($res && db_numrows($res) > 0) {
	$donate = '&nbsp;<a href="/developer/make_donation.php?user_id='.$user_id.'"><img src="/images/iconTealStar_16x16.png" alt="User Donate" border="0" width="16" height="16">&euro;</a>';
  }
  return $donate;
}

?>