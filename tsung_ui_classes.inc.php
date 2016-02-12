<?php
/**
* TSUNG miniUI
* @license free as in beer
* @version 0.3
* @author Mike Marlett @ sntmedia
* @abstract Contains Configuration Constants and Utility Class; based on v. 0.2 by blueend.com
*/

/**
* Utility Class - Keeping it simple :)  
*/
class sntmedia_tsungUI {

	/**
	* @var resource mysql connection
	*/
	private $_link;
	
	public static $CONFIG = array();

	public $current_page = 'status';

	public function __construct(){

		$this->_link = new mysqli(sntmedia_DB_SERVER, sntmedia_DB_USER, sntmedia_DB_PASS, sntmedia_DB_NAME);
		if ($this->_link->connect_error) {
			die('Connect Error (' . $this->_link->connect_errno . ') ' . $this->_link->connect_error);
		}
		
		//Pages in Tabs
		self::$CONFIG['pages']['status'] = 'Status';
		self::$CONFIG['pages']['reports'] = 'Reports';
		self::$CONFIG['pages']['tests'] = 'Tests';
		self::$CONFIG['pages']['new_test'] = 'New Test';
	
		if (isset($_GET['page']) && ($_GET['page']) && isset(self::$CONFIG['pages'][$_GET['page']])){
			$this->current_page = $_GET['page'];
		} else {
			$this->current_page = 'status';
		}
	}

	/**
	* Get List of possible Tests (each test is a directory in templates)
	* @return array
	*/
	public function getTestplanList($order = 'ASC'){
		$testplan_list = array();
		if (($order != 'ASC') && ($order != 'DESC')){ //don't let anything else in
			$order = 'ASC';
		}
		//look in the db for the test plans
		$query = "SELECT DISTINCT `_name` FROM `tsung_config_templates` ORDER BY `_name` $order";
		if ($res = $this->_link->query($query))
		{
			while ($row = $res->fetch_array(MYSQLI_ASSOC)){
				$testplan_list[] = $row['_name'];
			}
		}
		//Using db instead of file system 
		/*
		foreach (scandir(sntmedia_PATH_TEMPLATES) as $dir){
			if ($dir<>'.' && $dir<>'..' && $dir<>'.DS_Store' && file_exists(sntmedia_PATH_TEMPLATES.'/'.$dir.'/config.xml')){$testplan_list[] = $dir;};
		}
		*/
		return $testplan_list;
	}

	public function getReportCount($type){
		switch($type) {
			case 'archived':
				$where =  '`status` = \'archived\'';
			break;
			case 'active':
				$where =  '`status` = \'running\'';
			break;
			default;
				$where =  '`status` = \'finished\'';
			break;
		}
	
		$query = "SELECT count(`id`) AS count FROM `tsung_statusinfo` WHERE $where;";
		$res = $this->_link->query($query);
		$row = $res->fetch_array(MYSQLI_ASSOC);
		return ($row['count']);
	
	}

	public function getTestCount($type){
		switch($type) {
			case 'active':
				$where =  '(`_status` = \'active\') || (`_status` = \'completed\') || (`_status` = \'scheduled\') ';
			break;
			case 'completed':
				$where =  '`_status` = \'completed\'';
			break;
			case 'draft':
				$where =  '`_status` = \'draft\'';
			break;
			case 'scheduled':
				$where =  '`_status` = \'scheduled\'';
			break;
			case 'archived':
				$where =  '`_status` = \'archived\'';
			break;
			default;
				$where =  '`_status` is 1';
			break;
		}
	
		$query = "SELECT count(`id`) AS count FROM `tsung_config_templates` WHERE $where;";
		$res = $this->_link->query($query);
		$row = $res->fetch_array(MYSQLI_ASSOC);
		return ($row['count']);
	
	}
	
	public function searchReports($search, $search_filter = '', $order = '', $type = ''){
		$search = $this->_link->real_escape_string(urldecode($search));
		$search_filter = $this->_link->real_escape_string(urldecode($search_filter));
		
		if (($order != 'ASC') && ($order != 'DESC')){ //don't let anything else in
			$order = 'ASC';
		}

		switch($type) {
			case 'archived':
				$where =  '`status` = \'archived\'';
			break;
			case 'active':
				$where =  '`status` = \'running\'';
			break;
			default;
				$where =  '`status` = \'finished\'';
			break;
		}
		
		switch($search_filter) {
			case 'names':
				$where .=  ' AND `tsung_statusinfo`.`template` LIKE \'%'.$search.'%\'';
			break;
			case 'urls':
				$where .=  ' AND `tsung_statusinfo`.`urls` LIKE \'%'.$search.'%\'';
			break;
			case 'profiles':
				$where .= ' AND `tsung_statusinfo`.`profile` LIKE \'%'.$search.'%\'';
			break;
			case 'notes':
				$where .= ' AND `tsung_statusinfo`.`comment` LIKE \'%'.$search.'%\'';
			break;
			default;
				$where .= ' AND (`tsung_statusinfo`.`template` LIKE \'%'.$search.'%\') OR (`tsung_statusinfo`.`urls` LIKE \'%'.$search.'%\') OR (`tsung_statusinfo`.`profile` LIKE \'%'.$search.'%\') OR (`tsung_statusinfo`.`comment` LIKE \'%'.$search.'%\')';
			break;
		}
		$report_list = array();
	
		$query = "SELECT * FROM `tsung_statusinfo` WHERE $where ORDER BY `started_at` $order;";
		if ($res = $this->_link->query($query)){
			while ($row = $res->fetch_array(MYSQLI_ASSOC))
			{
				$report_list[] = $row;
				$path = $row['template'].'/log/'.$row['starttime'];
				if (file_exists(sntmedia_PATH_TEMPLATES.$path.'report.html'))
				{
					$row['path'] = $path;
					$report_list[] = $row;
				}
			}
		}else{
			$report_list['nope'] = $query;
		}
		return $report_list;
	}

	public function archiveReport($id){
		if (is_numeric($id)){
			$query = "UPDATE `tsung_statusinfo` SET `status` = 'archived' WHERE `tsung_statusinfo`.`id` = '{$id}';";
			$res = $this->_link->query($query);
		}
	}

	/**
	*Delete log Directory
	*
	* @param string id
	* @return void
	**/
	public function deleteReport($id){
		if (is_numeric($id)){
			$query = "SELECT `template`, `starttime` FROM `tsung_statusinfo` WHERE `id` = '{$id}'";
			$this->_link->query($query);
			$res = $this->_link->query($query);
			$row = $res->fetch_array(MYSQLI_ASSOC);
			$dir = dirname(__FILE__).'/templates/'.$row['template'].'/log/'.$row['starttime'].'/';
			if (is_dir($dir)){
				$this->deletePath($dir);
			}
			$query = "DELETE FROM `tsung_statusinfo` WHERE `tsung_statusinfo`.`id` = '{$id}';";
			$res = $this->_link->query($query);

		}
	}


	/**
	* Get List of possible Reports (each report should be in a directory in templates/testname/log)
	* @return array
	*/
	public function getTestplanReports($order = 'DESC', $type = ''){
		$report_list = array();
		if (($order != 'ASC') && ($order != 'DESC')){ //don't let anything else in
			$order = 'ASC';
		}
		//'waiting','running','canceled','finished','scheduled','archived'
		// waiting, canceled and scheduled will not have reports		
		switch($type) {
			case 'archived':
				$where =  '`status` = \'archived\'';
			break;
			case 'active':
				$where =  '`status` = \'running\'';
			break;
			default;
				$where =  '`status` = \'finished\'';
			break;
		}
		
		//look in the db for the test plans
		
		
		$query = "SELECT * FROM `tsung_statusinfo` WHERE $where ORDER BY `started_at` $order";
		if ($res = $this->_link->query($query))
		{
			while ($row = $res->fetch_array(MYSQLI_ASSOC))
			{
					$report_list[] = $row;
				$path = $row['template'].'/log/'.$row['starttime'];
				if (file_exists(sntmedia_PATH_TEMPLATES.$path.'report.html'))
				{
					$row['path'] = $path;
					$report_list[] = $row;
				}
			}
		}
		return $report_list;
	}


	/**
	* Get url from file
	* @return string
	*/
	public function path2url($file, $Protocol='http://') {
		return $Protocol.$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
	}

	/**
	* Get Status Details for running Test
	* @return string
	*/
	public function getTestplanStatusDetails($id){
		// Get Runtime from DB
		$query = "SELECT * FROM tsung_statusinfo WHERE id = '{$id}' LIMIT 1";

		$r = '';

		if ($res = $this->_link->query($query))
		{
			$row = $res->fetch_array(MYSQLI_ASSOC);
			if ($row['starttime'])
			{
				$dir = dirname(__FILE__).'/templates/'.$row['template'].'/log/'.$row['starttime'].'/';
				if (file_exists($dir)){
					foreach (scandir($dir) as $filename)
					{
						if (fnmatch('tsung_controller*', $filename))
						{
							$cmd = "tail -n5 {$dir}{$filename}";
							$r.= (nl2br(shell_exec($cmd)));
						}
					}
				}
			} else {
				$r.= "Waiting for cron ...";
			}
			$res->free();
		} else {
			$r.= "Waiting...";
		}
		return $r;
	}
	
	/**
	* Get test Plan by Start
	* @param string starttime
	* @return array
	*/
	
	public function getTestplanByStartdate($starttime){
		// Get Runtime from DB
		$starttime = $this->_link->real_escape_string($starttime);
		$query = "SELECT * FROM `tsung_statusinfo` WHERE `starttime` = '{$starttime}' LIMIT 1";
		if ($res = $this->_link->query($query))
		{
			$row = $res->fetch_array(MYSQLI_ASSOC);
			if ($row){
				return $row;
			}
		}
	}


	/**
	* Add new Test to Queue
	* @param string template name
	* @return void
	*/
	public function addTestplan($templateName, $comment, $status = 'waiting', $templateID = ''){
		$templateName = $this->_link->real_escape_string($templateName);
		if ($templateID){
			$templateID = $this->_link->real_escape_string($templateID);
		}else{
			$query = "SELECT `id`, `timestamp` FROM `tsung_config_templates` WHERE `name` = '$templateName' ORDER BY `timestamp` LIMIT 1";
			if ($res = $this->_link->query($query))
			{
				$row = $res->fetch_array(MYSQLI_ASSOC);
				if ($row){
					$templateID = $row['id'];
				}
			}
		}
		$comment = $this->_link->real_escape_string($comment);
		$query = "INSERT INTO `tsung_statusinfo`
			SET 
			`status`='$status',
			`template`='$templateName',
			`parent_id`='$templateID',
			`comment`='$comment';";
		$this->_link->query($query);
	}
	
	/**
	* Add new Test to Queue
	* @param int TEST id
	* @return void
	*/
	public function cancelTestplan($id){
		$id = (int) $id;
		$query = "UPDATE tsung_statusinfo SET status='canceled' WHERE id = '{$id}'";
		$this->_link->query($query);
	}

	/**
	* Get Test Info by Template Name
	* 
	* @param string template
	* @param string $starttime
	* @return array
	*/
	public function getTestInfoByPath($templateName, $starttime){
		$templateName = $this->_link->real_escape_string($templateName);
		$starttime = $this->_link->real_escape_string($starttime);
		$query = "SELECT * FROM `tsung_statusinfo` 
					WHERE `template`='$templateName'
					AND `starttime`='$starttime';";
		$res = $this->_link->query($query);
		$r = $res->fetch_array(MYSQLI_ASSOC);
		$r['page_throughput'] = '';
		$r['page_mean'] = '';
		$r['request_mean'] = '';
		if (! isset($r['comment'])){
			$r['comment'] = '';
		}
		if (! isset($r['template'])){
			$r['template'] = '';
		}

		// Grep some infos from reports
		$path = sntmedia_PATH_TEMPLATES.$templateName.'/log/'.$starttime.'/report.html';
		//set these so they won't throw errors later, even if there isn't a report
		if (file_exists($path))
		{
			$content = file_get_contents($path);
			$found = preg_match_all('%<td class="stats">([^<]*)</td>%m', $content, $result);
			if ($found > 0)
			{
				$r['page_throughput'] = $result[0][9];
				$r['page_mean'] = $result[0][10];
				$r['request_mean'] = $result[0][16];
			}
		}
		return $r;
	}
	
	/** 
	* Recursively delete a directory 
	* 
	* @param string $dir Directory name 
	* @param boolean $deleteRootToo Delete specified top-level directory as well 
	*/ 
	private function deletePath($path)
	{
		if (is_dir($path) === true)
		{
			$files = array_diff(scandir($path), array('.', '..'));
			foreach ($files as $file)
			{
				$this->deletePath(realpath($path) . '/' . $file);
			}
			return rmdir($path);
		}
		else if (is_file($path) === true)
		{
			return unlink($path);
		}
		return false;
	}

	

	/**************
	*
	*returns a list of the tables as an array
	*
	**************/
	public function getTableNames(){
		$query = "SHOW TABLES IN `tsung`";// like 'tsung_%' ";
		$this->_link->query($query);
		$res = $this->_link->query($query);
		$tables = array();
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$tables[] = $row['Tables_in_tsung'];
		}
		return $tables;
	}


	/**************
	*
	*@param string $tbl_name a table in the database
	*returns a list of the columns as an array
	*
	**************/
	public function getColumnNames($tbl_name){
		$tbl_name = $this->_link->real_escape_string($tbl_name);
		$query = 'SHOW COLUMNS IN `'.$tbl_name.'` FROM `tsung`';
		$this->_link->query($query);
		$res = $this->_link->query($query);
		$columns = array();
		while ($row = $res->fetch_array(MYSQLI_ASSOC)){
			$columns[] = $row['Field'];
		}
		return $columns;
	}

	// Used by the cron --------------------
	
	public function is_process_running($PID){
		exec("ps $PID", $ProcessState);
		return(count($ProcessState) >= 2);
	}

	public function updateStatus($id, $status, $starttime=false, $pgid=False){
		$id = (int) $id;
		$data = array();
		$data[] = "status = '$status'";
		if ($starttime) $data[] = "starttime = '$starttime'";
		if ($pgid) $data[] = "pgid = '$pgid'";
		if ($status=='running') $data[] = "started_at = NOW()";
		if ($status=='finished') $data[] = "finished_at = NOW()";

		$query = "UPDATE tsung_statusinfo   
		SET ";
		$query .= implode(', ', $data);
		$query .= " WHERE id = $id;";

		$this->_link->query($query);
	}

	// not implemented yet
	public function recorderIsRunning(){
	}

	public function startRecorder(){
	}

	public function stopRecorder(){
	}

}


