<?php

require ('squal_pre.php');

/*if (!strstr($REMOTE_ADDR,$sys_internal_network)) {
        exit_permission_denied();
}*/

db_begin();

#one hour ago for projects
echo "<p>Delete Incomplete Projects\n";
$then=(time()-3600);
db_query("DELETE FROM groups WHERE status='I' and register_time < '$then'");
echo "<br>DELETE FROM groups WHERE status='I' and register_time < '$then'\n";
echo db_error();

#one week ago for users
echo "<p>Delete Pending Users\n";
$then=(time()-604800);
#db_query("DELETE FROM users WHERE status='P' and add_date < '$then'");
#echo "<br>DELETE FROM users WHERE status='P' and add_date < '$then'\n";
db_query("UPDATE users SET status='D' WHERE status='P' AND add_date < '$then'");
echo "<br>UPDATE users SET status='D' WHERE status='P' AND add_date < '$then'\n";
echo db_error();

#one week ago for sessions
echo "<p>Delete old Sesssions\n";
$then=(time()-604800);
db_query("DELETE FROM session WHERE time < '$then'");
echo "<br>DELETE FROM session WHERE time < '$then'\n";
echo db_error();

#one month ago for preferences
echo "<p>Delete User Preferences\n";
$then=(time()-604800*4);
db_query("DELETE FROM user_preferences WHERE set_date < '$then'");
echo "<br>DELETE FROM user_preferences WHERE set_date < '$then'\n";
echo db_error();

#3 weeks ago for jobs
echo "<p>Delete Jobs\n";
$then=(time()-604800*3);
db_query("UPDATE people_job SET status_id = '3' where date < '$then'");
echo "<br>UPDATE people_job SET status_id = '3' where date < '$then'\n";
echo db_error();

db_commit();
echo "<p>Done: ".db_error();

?>
