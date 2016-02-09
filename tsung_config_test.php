<?php
/**
* blueend.com - TSUNG GUI
* @version 0.2
* @author blueend.com
* @license free as in beer
* 
* @abstract Contains Frontend
*/
define('sntmedia_TSUNG_UI', true);
require('config.inc.php');
require('tsungUI_xml_class.inc.php');
$tsungUI = new tsungUI_XML();                                

echo '<html>
<head>
</head>
<body>
<pre>';
/*
tsung_client
tsung_load
tsung_match
tsung_monitors
tsung_options
tsung_server
tsung_session
tsung_statusinfo

*/
echo( $tsungUI->getTsungConfig('1'));

echo '
</pre>
</body>
';

?>