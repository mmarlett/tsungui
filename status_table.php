<?php 
define('sntmedia_TSUNG_UI', true);
require('config.inc.php');
$tsungUI = new sntmedia_tsungUI();                                
if (isset($_POST['testplan']) && ($_POST['testplan']) && isset($_POST['action']) && ($_POST['action']=='run')){
	$tsungUI->addTestplan($_POST['testplan'],$_POST['comment']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>status table</title>
	<meta http-equiv="refresh" content="10">
<link rel="stylesheet" href="/style.css">
<link rel="stylesheet" href="/synapsys.css">
</head>
<body>
<div class='page'>
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
				$sql = "SELECT * FROM `tsung_statusinfo` WHERE `status`='running' OR `status`='waiting' OR `status`='canceled' ORDER BY `id` DESC";
				if ($res = $mysqli->query($sql))
				{
					if ($res->num_rows == 0)
					{
						echo "<tr><td colspan='5'>There are no tests running or waiting to run.</td></tr>";
					} else {
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
</div>
</body>
</html>
