<?php

require_once dirname(__FILE__).'/include/user.php';
require_once dirname(__FILE__).'/include/server.php';
require_once dirname(__FILE__).'/include/connectDB.php';
$servers = mysql_query("SELECT day_length,week_length,previous_day,server_id FROM servers") or die(mysql_error());
while($server = mysql_fetch_assoc($servers)) {

	if($server['previous_day'] < time()-$server['day_length']) {
		$serverobj = new server($server['server_id']);
		$users = mysql_query("SELECT user_id FROM users") or die(mysql_error());
		while($user = mysql_fetch_assoc($users)) {
			$user_object = new user($user['user_id'],$serverobj);
			$user_object->dailyGold();
		}//end while user
		mysql_query("UPDATE servers SET previous_day=".time()." WHERE server_id=".$server['server_id']) or die(mysql_error());
	}//end if new day

	if($server['week_length'] && $server['previous_week'] < time()-$server['week_length']) {
		$serverobj = new server($server['server_id']);
		$serverobj->reset();//TODO: highscores, etc	
	}//end if new week

}//end while servers

?>