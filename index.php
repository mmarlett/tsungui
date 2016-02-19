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

$testplan_list = $tsungUI->getTestplanList();

if (isset($_POST['testplan']) && ($_POST['testplan']) && isset($_POST['action']) && ($_POST['action']=='run')){
	$tsungUI->addTestplan($_POST['testplan'],$_POST['comment']);
}

if (isset($_GET['action']) && isset($_GET['id']))
{
	switch($_GET['action']) {
		case 'archive':
			$tsungUI->archiveReport($_GET['id']);
		break;
/*		case 're-run':
			if (isset($_GET['startdate']) && ($_GET['startdate'])){
				$plan =  $tsungUI->getTestplanByStartdate($_GET['startdate']);
				if (isset ($plan['template'])){
					$tsungUI->addTestplan($plan['template'],'A re-run of '.$plan['template'].' at '.$_GET['startdate']);
				}
			}elseif(isset($_GET['template']) && (in_array($_GET['template'], $testplan_list))){
				$tsungUI->addTestplan($_GET['template'],'A re-run of '.$_GET['template']);
			}
		break;
		*/
		case 'trash':
			$tsungUI->deleteReport($_GET['id']);
		break;
		
	}
}

if (isset($_GET['action']) && isset($_GET['template']))
{
	switch($_GET['action']) {
		//case 'archive':
		//	$tsungUI->archiveReport($_GET['id']);
		//break;
		case 're_run':
			if (isset($_GET['startdate']) && ($_GET['startdate'])){
				$plan = $tsungUI->getTestplanByStartdate($_GET['startdate']);
				if (isset ($plan['template'])){
					$tsungUI->addTestplan($plan['template'],'A re-run of '.$plan['template'].' at '.$_GET['startdate']);
				}
			}elseif(in_array($_GET['template'], $testplan_list)){
	echo '<p>I here ya.</p>';
				$tsungUI->addTestplan($_GET['template'],'A re-run of '.$_GET['template']);
			}
		break;
		//case 'trash':
		//	$tsungUI->deleteReport($_GET['id']);
		//break;
		
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


?>
<!DOCTYPE html>
<html>
<head>
	<title>TsungUI</title>

<link rel="icon" type="image/png" href="/favico.png" />

<link rel="stylesheet" href="assets/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="assets/tsungui.css">
<script src="assets/jquery-ui/external/jquery/jquery.js"></script>
<script src="assets/jquery-ui/jquery-ui.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>

<script language="javascript" type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>

</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
	<a class="navbar-brand" href="#"><img alt="TsungUI" src="assets/icon.png"></a>
</div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
			<?php
			foreach (sntmedia_tsungUI::$CONFIG['pages'] as $id=>$label){
				$active = ($tsungUI->current_page == $id) ? 'class=\'active\'' : '';
				echo ' <li '.$active.'><a href="?page='.$id.'">'.$label.'</a></li>';
			}
			?>
      </ul>
      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Settings <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Client Machines</a></li>
            <li><a href="#">Monitoring</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">About</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

	<?php
	//print_r($_POST);
	//print_r($_GET);
		include('pages/'.$tsungUI->current_page.'.php');
	?>
</body>
</html>