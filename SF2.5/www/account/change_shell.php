<?php
//
// BerliOS: Fostering Open Source Development
// Copyright 2000-2004 (c) The BerliOS Crew
// http://www.berlios.de
//
// $Id: change_shell.php,v 1.1 2004/06/09 12:21:20 helix Exp $

require "pre.php";    
require "account.php";

// ###### function register_valid()
// ###### checks for valid register from form post

function register_valid()	{

	if (!$GLOBALS["Update"]) {
		return 0;
	}
	
	if (!$GLOBALS[form_shell]) {
		$GLOBALS[register_error] = "You must supply a new login shell.";
		return 0;
	}
	
	// if we got this far, it must be good
	db_query("UPDATE users SET shell='$GLOBALS[form_shell]' WHERE user_id=" . user_getid());
	return 1;
}

// ###### first check for valid login, if so, congratulate

if (register_valid()) {
	session_redirect("/account/");
} else { // not valid registration, or first time to page
	site_user_header(array(title=>"Change Login Shell"));

	?>
	<p><b>Login Shell Change</b>
	<?php if ($register_error) print "<p>$register_error"; ?>
	<form action="change_shell.php" method="post">
	<p>New Login Shell:
	<select name="form_shell">
	<option>/bin/bash</option>
	<option>/bin/ash</option>
	<option>/bin/csh</option>
	<option>/bin/ksh</option>
	<option>/bin/sh</option>
	<option>/bin/tcsh</option>
	<option>/bin/zsh</option>
	</select>
	<p><input type="submit" name="Update" value="Update">
	</form>

	<?php
}

site_user_footer(array());

?>
