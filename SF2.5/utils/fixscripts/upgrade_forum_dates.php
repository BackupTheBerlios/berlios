<?php
//
// SourceForge: Breaking Down the Barriers to Open Source Development
// Copyright 2003 (c) The BerliOS crew
// http://www.berlios.de
//
// $Id

exit;

/*

	One time use script

	calculates and updates the most recent date in the forums

*/

require ('pre.php');    

session_require(array('group'=>'1','admin_flags'=>'A'));

//get all the tasks
$result=db_query("SELECT msg_id FROM forum ORDER BY msg_id ASC",10000,$z);
$rows=db_numrows($result);
echo db_error();
echo "\nRows: $rows\n";
flush();

for ($i=0; $i<$rows; $i++) {

	// echo "\n".db_result($result,$i,'msg_id')."\n";

	/*
		//update most recent date
	*/

	// echo "\nUPDATE forum SET most_recent_date=date WHERE msg_id='". db_result($result,$i,'msg_id') ."'\n";
	db_query ("UPDATE forum SET most_recent_date=date WHERE msg_id='". db_result($result,$i,'msg_id') ."'");
}

flush();

?>
