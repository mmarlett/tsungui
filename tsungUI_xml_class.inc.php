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
			$children = $this->getChildren($table_name); //get the tables pointing to this table
			$parent_id = $parent_row['id']; //preserve the id before taking it out of the array
			$parent_row = $this->cleanForXML($parent_row);
			foreach ($children as $child_name)
			{
				$parent_row[$child_name] = $this->getChildRow($child_name, $parent_id); //
				$parent_row[$child_name] = $this->recursiveChildren($child_name, $parent_row[$child_name]);
			}
		}else{ 
			foreach ($parent_row as $key => $this_row)
			{
				$children = $this->getChildren($table_name); //get the tables pointing to this table
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
		$query = "SELECT * FROM `tsung_config_templates` WHERE `id` = '{$id}' LIMIT 1";
		$xml = '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE tsung SYSTEM "'.dirname(__file__).'/tsung-1.0.dtd">';
		if ($res = $this->_link->query($query))
		{
			$row = $res->fetch_array(MYSQLI_ASSOC);
			if ($row)
			{	
				$row = $this->recursiveChildren('tsung_config_templates', $row);
				$xml .= $this->makeThisXML($row, 'tsung');
				return $xml;
			}
		}
	}

}


?>