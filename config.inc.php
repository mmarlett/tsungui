<?php
/**
* TSUNG miniUI
* @license free as in beer
* @version 0.3
* @author Mike Marlett @ sntmedia
* @abstract Contains Configuration Constants; based on v. 0.2 by blueend.com
*/
//Change as needed
define('sntmedia_DB_USER',		'sntmedia');
define('sntmedia_DB_PASS',		'passfail123');
define('sntmedia_DB_NAME',		'tsung');
define('sntmedia_DB_SERVER',	'localhost');

date_default_timezone_set("America/Chicago");
setlocale(LC_MONETARY, 'en_US');


//This is needed for the report generation
define('sntmedia_TSUNG_PERLSTATS','/usr/local/Cellar/tsung/1.6.0/lib/tsung/bin/tsung_stats.pl --dygraph');//path to 'tsung/src/tsung_stats.pl' 
// '--dygraph' is optional setting for producing graphs; other arguements can be passed.

define('sntmedia_TSUNG_MAX_PROCESSES', 100);//How many tests should we spawn simultanously? 

define('sntmedia_PATH_TEMPLATES',dirname(__file__).'/templates/');

//automatically figure out the url for iframes
$path = $_SERVER['PHP_SELF'];
$path = str_replace(dirname(__file__), '', $path);
$path = str_replace('index.php', '', $path);
define('sntmedia_TOP_PATH',$path);
unset($path);

$mysqli = new mysqli(sntmedia_DB_SERVER, sntmedia_DB_USER, sntmedia_DB_PASS, sntmedia_DB_NAME);
if ($mysqli->connect_error) {
	die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

require('tsung_ui_classes.inc.php');

?>