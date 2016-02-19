<?php
class tsungUI_XML {
	/**
	* @var resource mysql connection
	*/
	private $_link;
	public function __construct()
	{
		$this->_link = new mysqli(sntmedia_DB_SERVER, sntmedia_DB_USER, sntmedia_DB_PASS, sntmedia_DB_NAME);
		if ($this->_link->connect_error)
		{
			die('Connect Error (' . $this->_link->connect_errno . ') ' . $this->_link->connect_error);
		}
	}

	/**
	* Get children of parent table 
	* @param string parent_table
	* @return array
	*/
	/*
	public function getChildren($parent_table)
	{
		$parent_table = $this->_link->real_escape_string($parent_table);
		$query = "SELECT `TABLE_NAME` FROM `information_schema`.`KEY_COLUMN_USAGE` WHERE `REFERENCED_TABLE_NAME` = '$parent_table' AND `REFERENCED_COLUMN_NAME` = 'id' AND `TABLE_SCHEMA` = 'tsung';";
		$table_names = array();
		if ($res = $this->_link->query($query))
		{
			while($row = $res->fetch_array(MYSQLI_ASSOC))
			{
				$table_names[] = $row['TABLE_NAME'];
			}
			return $table_names;
		}
	}
	*/

	/**
	* Get table row by parent id 
	* @param string parent_table
	* @return array
	*/
	private function getChildRow($table, $parent_id)
	{
		$table = $this->_link->real_escape_string($table);
		$parent_id = $this->_link->real_escape_string($parent_id);
		$query = "SELECT * FROM `$table` WHERE `parent_id` = '$parent_id';";
		$rows = array();
		$booleans = array('use_controler_vm','random','subst','dumptraffic');
		if ($res = $this->_link->query($query))
		{
			while($row = $res->fetch_array(MYSQLI_ASSOC)){
				foreach ($row as $key => $var) //convert boolean '1'/'0' to 'true'/'false'
				{
					if (in_array($key, $booleans))
					{
						if ($row[$key]==='0'){
							$row[$key]='false';
						}elseif($row[$key]==='1')
						{
							$row[$key]='true';
						}
					}
				}
				$rows[] = $row;
			}
			return $rows;
		}
	}

	/**
	* Take an array and set every key / value as an attribute of name, unless the key is "content" or the val is an array, in which they nest inside the tag
	* @param array 
	* @param string name
	**/
	private function makeThisXML($array, $name){
		$xml = "\n";
		if (is_array($array))
		{
			$array = $this->cleanForXML($array);
			$name = str_ireplace('tsung_', '' , $name);
			$xml .=  "<".$name;
			$content = '';
			$nested = '';
			foreach ($array as $key => $val)
			{
				if ($key == 'content')
				{
					$content .= $val;
				}elseif(is_array($val))
				{
					foreach ($val as $row)
					{
						$nested .= $this->makeThisXML($row, $key); //print_r($val, true).
					}
				}else{
					$xml .= ' '.$key.'="'.$val.'"';
				}
			}
			if (!($content) && !($nested))
			{
				$xml .= "/>";
			}else{
				$xml .= '>'.$content;
				if ($nested){
					$xml .= $nested."\n";
				}
				$xml .= "</".$name.">";
			}
		}
		return $xml;
	}

	/**
	* Convert array to XML config file 
	* @param array
	* @return string of xml
	*/
	private function cleanForXML($array)
	{
		$ignore = array('id', 'created','profile','timestamp','parent_table','parent_id');
		$return = array();
		if (is_array($array)){
			foreach ($array as $key => $val){
				if ((! in_array($key, $ignore)) && ($val))//if it isn't something we want to ignore and it is not null
				{
					$return[$key] = $val;
				}
			}
			return $return;
		}
	}
	
	
	private function recursiveChildren($table_name, $parent_row)
	{
		if (isset ($parent_row['id']))
		{
			$children = $this->getTsungConfigMap($table_name); //get the tables pointing to this table
			$parent_id = $parent_row['id']; //preserve the id before taking it out of the array
			$parent_row = $this->cleanForXML($parent_row);
			foreach ($children as $child_name)
			{
				$parent_row[$child_name] = $this->getChildRow($child_name, $parent_id); //
				$parent_row[$child_name] = $this->recursiveChildren($child_name, $parent_row[$child_name]);
			}
		}elseif(is_array($parent_row)){ 
			foreach ($parent_row as $key => $this_row)
			{
				$children = $this->getTsungConfigMap($table_name); //get the tables pointing to this table
				$parent_id = $this_row['id']; //preserve the id before taking it out of the array
				$parent_row[$key] = $this->cleanForXML($this_row);
				foreach ($children as $child_name)
				{
					$parent_row[$key][$child_name] = $this->getChildRow($child_name, $parent_id); //
					$parent_row[$key][$child_name] = $this->recursiveChildren($child_name, $parent_row[$key][$child_name]);
				}
			}
		}
		return $parent_row;
	}
	
	/**
	* Get test Config from database 
	* @param string id
	* @return array
	*/
	
	public function getTsungConfig($id){
		$id = $this->_link->real_escape_string($id);
		$query = "SELECT * FROM `tsung_tsung` WHERE `id` = '{$id}' LIMIT 1";
		$xml = '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE tsung SYSTEM "'.dirname(__file__).'/tsung-1.0.dtd">';
		if ($res = $this->_link->query($query))
		{
			$row = $res->fetch_array(MYSQLI_ASSOC);
			if ($row)
			{
				$row = $this->recursiveChildren('tsung_tsung', $row);
				$xml .= $this->makeThisXML($row, 'tsung');
				$pattern = '/ _([a-z_]+)="(.*?)"/i';
				$xml = preg_replace($pattern, '', $xml);
				return $xml;
			}
		}
	}


	/**
	* A set of static arrays that will return an ordered array to tell you which 
	* children are acceptible for a given parent table, and in what order
	* @param string table
	* @return array tables
	*/

	/*
	In an ideal world, this would be dynamic, but tsung's XML requirements are too ridged
	*/

	public function getTsungConfigMap($parent_table='tsung_tsung'){
		switch($parent_table) {
			case 'tsung_tsung':
				$children = array('tsung_information','tsung_clients','tsung_servers','tsung_monitoring','tsung_load','tsung_options','tsung_sessions');
			break;
			//first level children
			case 'tsung_clients':
				$children = array('tsung_client');
			break;
			case 'tsung_servers':
				$children = array('tsung_server');
			break;
			case 'tsung_monitors':
				$children = array('tsung_snmp','tsung_mysqladmin');
			break;
			case 'tsung_load':
				$children = array('tsung_arrivalphase','tsung_user');
			break;
			case 'tsung_sessions':
				$children = array('tsung_session');
			break;
			case 'tsung_options':
				$children = array('tsung_option');
			break;


			//second level children
			case 'tsung_client':
				$children = array('tsung_client_ip');
			break;
			case 'tsung_snmp':
				$children = array('tsung_oid');
			break;
			case 'tsung_arrivalphase':
				$children = array('tsung_users', 'tsung_session_setup');
			break;
			case 'tsung_session':
				$children = array('tsung_request','tsung_thinktime','tsung_transaction','tsung_setdynvars','tsung_for','tsung_repeat','tsung_if','tsung_change_type','tsung_foreach','tsung_set_option','tsung_interaction'); //tsung_change_type , tsung_for
			break;
			case 'tsung_option':
				$children = array('tsung_user_agent');
			break;


			
			//third level children
			case 'tsung_request':
				$children = array('tsung_match','tsung_dyn_variable','tsung_http','tsung_jabber','tsung_raw','tsung_pgsql','tsung_ldap','tsung_mysql','tsung_fs','tsung_shell','tsung_job','tsung_websocket','tsung_amqp','tsung_mqtt'); //tsung_jabber, tsung_raw, tsung_ldap, tsung_mqtt
			break;

			case 'tsung_transaction':
				$children = array('tsung_request','tsung_setdynvars','tsung_thinktime','tsung_for','tsung_repeat','tsung_if','tsung_foreach','tsung_interaction');
			break;
			
			
			//fourth level children
			case 'tsung_http':
				$children = array('tsung_oauth', 'tsung_www_authenticate', 'tsung_soap', 'tsung_http_header', 'tsung_add_cookie');
			break;
			
			
			default;
				$children = array();
			break;
		}
		return $children;
	}

	/*Unemplimented ConfigMap parts
	tsung_for {can nest another for} (from="1" to="10" incr="1" var="loops")
	tsung_change_type (new_type="ts_http" host="foo.bar" port="80" server_type="tcp" store="true" bidi="true")
	tsung_jabber (type="chat" ack="no_ack" size="16" destination="previous")
	tsung_xmpp_authenticate (username="%%_username%%" passwd="%%_password%%")
	tsung_raw (data="BYEBYE" datasize="2048" ack="local")
	tsung_ldap
	tsung_setdynvars (sourcetype="file"  fileid="users" delimiter=";" order="iter" (type:random_string) length="10" (type:random_number) start="1" end="100" (type='muc:join') ack="local" room="room%%_room%%" nick="%%_nick1%%" size="16" )
	tsung_var (name="username")
	tsung_mqtt (type="connect" clean_start="true" keepalive="10" will_topic="will_topic" will_qos="0" will_msg="will_msg" will_retain="false" topic="test_topic" qos="1" retained="true"  timeout="60")
	tsung_amqp (type="basic.consume" vhost="/" channel="%%_loops%%" queue="test_queue" ack="true"  timeout="60")
	*/

	/**
	* Get test Config from database 
	* @param string id
	* @return array
	*/
	
	public function buildTsungConfig($id){
		$id = $this->_link->real_escape_string($id);
		$query = "SELECT * FROM `tsung_tsung` WHERE `id` = '{$id}' LIMIT 1";
		$xml = '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE tsung SYSTEM "'.dirname(__file__).'/tsung-1.0.dtd">';
		if ($res = $this->_link->query($query))
		{
			$row = $res->fetch_array(MYSQLI_ASSOC);
			if ($row)
			{
				$row = $this->recursiveChildren('tsung_tsung', $row);
				$xml .= $this->makeThisXML($row, 'tsung');
				$pattern = '/ _([a-z_]+)="(.*?)"/i';
				$xml = preg_replace($pattern, '', $xml);
				return $xml;
			}
		}
	}




}

?>