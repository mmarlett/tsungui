<?php
if(!defined('sntmedia_TSUNG_UI')){die();}
if (isset($_GET['action']) && ($_GET['action']==='cancel')){
	$tsungUI->cancelTestplan($_GET['id']);
}
if (isset($_GET['action']) && ($_GET['action']==='delete')){
	$tsungUI->deleteReport($_GET['id']);
}
//print_r($_POST);
?>
<main onload(statusupdate);>
<div class="page">
<div class="container">
<div class="clearfix">
<h2 class="pull-left">Run test</h2>
<a href="?page=tests&amp;action=new" class="pull-right btn-main btn-big"><span class="glyphicon glyphicon-plus"></span> New Test</a>
</div>

<div class="form-panel">
<div class="section">
<form method='POST'>
<div class="form-group ">
<div class="input-wrapper">
<div class="input-group">
<input type='hidden' name='action' value='run'>
<select class="select optional form-control" id="testplan" name="testplan">
<option value=''>[Select a test plan]</option>
<?php
foreach ($testplan_list as $plan){
echo "<option value='$plan'>$plan</option>";
}
?>
</select>
<span class="input-group-btn"><button class="btn btn-default" type="submit">Run!</button></span>
</div>
<textarea class="text" id="comment" name="comment" style="width:100%; margin-top:10px;" rows="1" placeholder="Enter notes for this test"></textarea>
</div>
</div>
</div>
</form>
</div>
<script >
var statusupdate = function(){
	$('#status-table').load('status_table.php');
}
setInterval(statusupdate, 2000); // run it every 2 seconds
</script>

<div class="form-panel" id="status-table" name="status-table" >
</div>
</div>
</div>
</div>
</main>
