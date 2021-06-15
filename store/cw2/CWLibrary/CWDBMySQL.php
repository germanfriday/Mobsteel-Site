<?php
/*
================================================================
Application Info: 
Cartweaver© 2002 - 2005, All Rights Reserved.
Developer Info: 
	Application Dynamics Inc.
	1560 NE 10th
	East Wenatchee, WA 98802
	Support Info: http://www.cartweaver.com/go/phphelp

Cartweaver Version: 2.4  -  Date: 11/27/2005
================================================================
Class: DB
Developer Info: 
				Tom Muck
				http://www.tom-muck.com

Provides database functionality and connection pooling for database 
operations on a PHP page. This is the MySQL file.

Set it up by creating a new instance on your page:

var $theConnection = new DB($hostname, $database, $databaseUsername, $databasePassword)
 (optional 5th parameter is $databaseType)
 
After setting it up, methods provided are:

Public Methods:
executeQuery($query)
db_query($query)
valueList($rs, $fieldname)
fieldToArray($rs, $fieldname)
db_fetch_assoc($recordset)
db_data_seek($rs) {
db_num_rows($rs) 
queryOfQuery($rs, // The recordset to query
							$fields = "*", // an array of the list of fields to query
							$distinct = false, // true if returning a set of distinct records
							$fieldToMatch = null, // optional database field to match
							$valueToMatch = null) 


Private Methods:
makeConnection()
*/

class DB {
	var $hostname, $database, $databaseUsername, $databasePassword, $databaseType, $recordCount, $debug;
	function DB($hostname, $database, $databaseUsername, $databasePassword, $databaseType = "MySQL"){
		$this->hostname = $hostname;
		$this->database = $database;
		$this->databaseUsername = $databaseUsername;
		$this->databasePassword = $databasePassword;
		$this->databaseType = strtolower($databaseType);
		$this->makeConnection();
		$this->debug = false;
	}
	
	function debug($string) {
		if($this->debug == true) {
			global $cwDebuggerStrings;
			$temp = debug_backtrace();
			array_push($cwDebuggerStrings,"ERROR LINE: " . $temp[0]["line"] . "  " . $temp[0]["file"]);
			array_push($cwDebuggerStrings,"  " . mysql_error() . "\n<br/>" . nl2br($string) . "\n<br/>");
		}
	}
	
	function executeQuery($query) {		
		mysql_select_db($this->database, $this->connection);
		$rs = mysql_query($query, $this->connection) or $this->debug($query);		
		if($rs && strpos(trim(strtolower($query)),'select')===0) {
			$this->recordCount = mysql_num_rows($rs);
		}else{
			$this->recordCount = 0;
		}
		if($this->debug == true) {
			global $queries;
			if(!isset($queries)) $queries = array();
			array_push($queries, array($query,$rs));
		}
		return $rs;
	}
	
	function db_query($query) {
		$rs = mysql_query($query, $this->connection);
		return $rs;
	}

	function makeConnection() {
		$this->connection = mysql_pconnect( $this->hostname, 
									$this->databaseUsername, 
									$this->databasePassword) or trigger_error(mysql_error(),E_USER_ERROR); 
	}
	
	function valueList($rs, $fieldname) {
		$theList = "";
		if($rs) {
			$this->db_data_seek($rs);
			$this->recordCount = mysql_num_rows($rs);
			while ($row_rs = mysql_fetch_assoc($rs)) {
				if($theList != "") $theList .= ",";
				$theList .= $row_rs[$fieldname];
			};
		}

		return $theList;
	}
	
	function fieldToArray($rs, $fieldname) {
		$theArray = array();
		$this->db_data_seek($rs);
		$this->recordCount = mysql_num_rows($rs);
		while ($row_rs = mysql_fetch_assoc($rs)) {
			array_push($theArray,$row_rs[$fieldname]); 
		};
		return $theArray;
	}
	
	function db_fetch_assoc($recordset) {
		if($recordset) {
			return mysql_fetch_assoc($recordset);
		}
	}
	
	function db_data_seek($rs) {
		if($rs) {
			if(mysql_num_rows($rs) > 0){
				mysql_data_seek($rs,0);
			}
		}
	}
	
	function db_num_rows($rs) {
  		return mysql_num_rows($rs);
	}
	
	function queryOfQuery(	$rs, // The recordset to query
							$fields = "*", // an array of the list of fields to query
							$distinct = false, // true if returning a set of distinct records
							$fieldToMatch = null, // optional database field to match
							$valueToMatch = null) { // optional value to match in the field, as a comma-separated list

		$newRs = Array();
		$row = Array();
		$valueToMatch = explode(",",$valueToMatch);
		$matched = true;
		$this->db_data_seek($rs, 0);
		if($rs) {
			while ($row_rs = mysql_fetch_assoc($rs)){
				if($fields == "*") {
					//foreach($row_rs as $field => $value) {
						if($fieldToMatch != null) {
							$matched = false;
							if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))) {
								$matched = true;
							}
						}
					//}
					if($matched) $row = $row_rs;
				}else{
					foreach($fields as $field) {
						if($fieldToMatch != null) {
							$matched = false;
							if(is_integer(array_search($row_rs[$fieldToMatch],$valueToMatch))) {
								$matched = true;
							}
						}
						if($matched) $row[$field] = $row_rs[$field];
					}
				}					
				if($matched)array_push($newRs, $row);
			};
			if($distinct) {
				sort($newRs);
				for($i = count($newRs)-1; $i > 0; $i--)  {
					if($newRs[$i] == $newRs[$i-1]) unset($newRs[$i]);
				}
			}
		}
		$this->db_data_seek($rs, 0);
		return $newRs;
	}
	
	// make variable safe
	function sqlSafe($value){
		if (!is_numeric($value)) {
			if(is_array($value)) {
				foreach($value as $key=>$val) 
					$value[$key] = (!get_magic_quotes_gpc()) ? addslashes($val) : $val;
			}else{
				$value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
			}
		}
		return $value;
	}
}

?>