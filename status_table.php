<?php 
define('sntmedia_TSUNG_UI', true);
require('config.inc.php');
$tsungUI = new sntmedia_tsungUI();                                
$sql = "SELECT * FROM `tsung_statusinfo` WHERE `status`='running' OR `status`='waiting' OR `status`='canceled' ORDER BY `id` DESC";
$is_running = false;
if ($res = $mysqli->query($sql))
{
if ($res->num_rows == 0)
{
	echo "<h4>There are no tests running or waiting to run.</h4>";
} else {
?>
<div class="form-panel" id="status-graph" name="status-graph" ></div>
<h3>Status</h3>
<table class="list-table">
	<thead>
		<tr>
			<th>Test plan</th>
			<th>JobID</th>
			<th>Status</th>
			<th>Comment</th>
			<th>Action</th>
		</tr>
	</thead>
<?php
		while ($row = $res->fetch_array(MYSQLI_ASSOC))
		{
			echo '<tr class="ro">';
			echo '<td><b class="title">';
			echo $row['template'];
			echo '</b></td>';
			echo '<td>';
			echo $row['id'];
			echo '</td>';
			echo '<td><b>';
			if ($row['status'] == 'running'){
				$is_running = true;
			}
			if ($row['status'] == 'waiting')
			{
				echo '<div class="spinner-bar" > </div>';
			}else{
				echo strtoupper($row['status']);
			}
			echo '</b></td>';
			echo "<td>".$row['comment']."</td>";
			if ($row['status'] !== 'canceled'){
				echo "<td><a href='?action=cancel&id=".$row['id']."'>Cancel</a></td>";
			}elseif ($row['status'] == 'canceled'){
				echo "<td><a href='?action=delete&id=".$row['id']."'>Delete</a></td>";
			}else{
				echo "<td></td>";
			}
			echo "</tr>";
			echo "<tr><td>&nbsp;</td>";
			echo "<td colspan='5'>".$tsungUI->getTestplanStatusDetails($row['id'])."</td>";
			echo "</tr>";
		}
	}
}

?>
</table>
<?php
if ($is_running == true){
	echo "<script>
var graphupdate = function(){
	$('#status-graph').load(':8091/es/ts_web:graph');
}
onLoad(graphupdate); // run it every 10 seconds
setInterval(graphupdate, 10000); // run it every 10 seconds
</script>
";

}

?>