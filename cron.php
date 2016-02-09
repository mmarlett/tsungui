<?php
/**
* blueend.com - TSUNG GUI
* @version 0.2
* @author blueend.com
* @license free as in beer
* 
* @abstract Contains Cron Functions to manage tsung via shell commands
* Please make sure you run this script with enough privileges
*/
require('config.inc.php');
$tsungUI = new sntmedia_tsungUI();

// Tsung Controller CRON
echo "Checking for running TSUNG processes ...\n";
$cmd = "ps ax | grep -e 'tsung ' | cut -c1-5";
$r = shell_exec($cmd);
$pids = explode('\n', $r);

if (count($pids)>sntmedia_TSUNG_MAX_PROCESSES){
	echo "Tsung still running... waiting\n";
	die();//Exit script immediately
}

echo "Checking for jobs...\n";
$sql = "SELECT * FROM `tsung_statusinfo` WHERE `status`='waiting' ORDER BY `id` ASC LIMIT 1";
if ($res = $mysqli->query($sql)){
	if (mysqli_num_rows($res)==0) echo "No Jobs found...\n";
	while ($row = $res->fetch_array(MYSQLI_ASSOC)){

		$pid = posix_getppid();
		$gid = posix_getpgid($pid);

		$starttime = date('Ymd-Hi');
		$tsungUI->updateStatus($row['id'], 'running', $starttime, $gid);
		set_time_limit(99999);         
		$dir = sntmedia_PATH_TEMPLATES.$row['template'].'/';
		
		// 1. Run TSUNG
		echo "Running TSUNG for $starttime\n";
		$cmd = 'cd "'.$dir.'"; nohup tsung -f config.xml -l log -i '.$row['id'].' start > /dev/null & echo $!';
		$pid = shell_exec($cmd);
		echo "\n$cmd";
		echo "\nPID: $pid";

		// Handle Process abort
		$abort = false;
		while ((!$abort) && ($tsungUI->is_process_running($pid))){
			$sql = "SELECT * FROM tsung_statusinfo WHERE id = {$row['id']}";
			if ($res = $mysqli->query($sql)){
				$info = $res->fetch_array(MYSQLI_ASSOC);
				if ($info['status']=='running'){ 
					sleep(2);
				} else {
					echo "Stopping $starttime (by user)...\n";
					shell_exec("tsung -i {$row['id']} stop");
				}
			}
		}
		// 2. Create GRAPHS
		echo "2. Creating REPORT for $starttime: \n";
		$dir .= 'log/'.$starttime.'/';
		$cmd = "cd $dir; ".sntmedia_TSUNG_PERLSTATS;
		shell_exec($cmd);
		$tsungUI->updateStatus($row['id'], 'finished');
	}
}

?>