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
$tsungUI = new sntmedia_tsungUI();                                

if (isset($_POST['testplan']) && ($_POST['testplan']) && isset($_POST['action']) && ($_POST['action']=='run')){
	$tsungUI->addTestplan($_POST['testplan'],$_POST['comment']);
}

if (isset($_GET['action']) && isset($_GET['id']))
{
	switch($_GET['action']) {
		case 'archive':
			$tsungUI->archiveReport($_GET['id']);
		break;
		case 're-run':
			if (isset($_GET['startdate']) && ($_GET['startdate'])){
				$plan =  $tsungUI->getTestplanByStartdate($_GET['startdate']);
				if (isset ($plan['template'])){
					$tsungUI->addTestplan($plan['template'],'A re-run of '.$plan['template'].' at '.$_GET['startdate']);
				}
			}elseif(isset($_GET['template']) && (in_array($_GET['template'], $testplan_list))){
				$tsungUI->addTestplan($_GET['template'],'A re-run of '.$_GET['template']);
			}
		break;
		case 'trash':
			$tsungUI->deleteReport($_GET['id']);
		break;
		
	}
}

$order = 'DESC';// ASC or DESC
if (isset ($_GET['order']))
{
	if (($_GET['order']=='ASC') || ($_GET['order']=='DESC'))
	{
		$order = $_GET['order'];
	}
}

$type = '';// completed, active, archived
if (isset ($_GET['type']))
{
	$type = urldecode($_GET['type']);
}

$search = '';
if (isset($_GET['search']))
{
	$search = urldecode($_GET['search']);
}

$search_filter = '';
if (isset($_GET['search_filter']))
{
	$search_filter = urldecode($_GET['search_filter']);
}

$action = '';
if (isset($_GET['action'])){
	$action = urldecode($_GET['action']);
}


$testplan_list = $tsungUI->getTestplanList();
?>
<!DOCTYPE html>
<html>
<head>
	<title>TsungUI</title>

<link rel="icon" type="image/png" href="/favico.png" />
<link rel="stylesheet" href="/jquery-ui-1.11.4.custom/jquery-ui.css">
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="/style.css">
<link rel="stylesheet" href="/synapsys.css">
<script src="/jquery-ui-1.11.4.custom/external/jquery/jquery.js"></script>
<script src="/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script src="/assets/bootstrap/js/bootstrap.js"></script>
<script language="javascript" type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>

	<style>

		body{
			background: #fff;
			padding: 0px;
			margin: 0px;
		}

		body, td{
			font-size: 13px;
		}

		.page{
			background: white;
			padding: 10px;  
		}

		table thead td{
			border-bottom: 1px dotted #555;
		}

		h1{
			display: Block;
			background: #3294ff;
			background-image: url(icon.png);
			background-position: left top;
			background-repeat: no-repeat;
			padding: 20px 0px 40px 150px;
			margin: 0;
			color: white;
			border-bottom: 1px solid #000;
		}

		ul{
			list-style: none;
			margin: 0;
			padding: 0;
		}

		ul .title{
			background: #2466ae;
			color: white;
			margin-bottom: 10px;
			display: block;
			padding: 3px;
		}

		ul li{
			padding-bottom: 8px;
		}

		ul li span{
			color: #666;
			display: block;
			font-size: 11px;
		}

		ul li a{
			text-decoration: none;
		}

		#navigation{
			height: 28px;
			margin-top: -28px;
			padding-left: 150px;
			height: 28px;
			overflow: hidden;
		}

		#navigation a{
			float: left;
			margin-right: 10px;
			background: #2466ae;
			padding: 6px 6px;
			border: 1px solid #000;
			border-bottom: 0px;
			text-decoration: none;
			color: #CCC;
			font-size: 14px;
		}

		#navigation a.selected{
			background: white;
			color: #555;
			font-weight: bold;
		}
		
		.new_test {
			background: #eee;
			padding: 10px;
		}

		tr.ro td{
			background: #ccc;
		}
	</style>
</head>
<body>
	<div id="head">
		<h1>TsungUI</h1>
		<div id="navigation">
			<?php
			foreach (sntmedia_tsungUI::$CONFIG['pages'] as $id=>$label){
				$sel = ($tsungUI->current_page == $id) ? 'selected' : '';
				echo "<a class='$sel' href='?page=$id'>$label</a>";
			}
			?>
		</div>
	</div>
	<?php
		include('pages/'.$tsungUI->current_page.'.php');
	?>
</body>
</html>