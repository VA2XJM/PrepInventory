<?PHP
	session_start();
	include('xinc.config.php');

	# Check if session exists.
	#  If Session (UID) is not existing, redirect to login.php
	#  Else show the page.
	if (empty($_SESSION['username'])) {
		header('location:login.php');
		die();
	}
	
	if (!empty($_GET['loc'])) { $loc = $_GET['loc']; }
	else { $loc = 0; }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>PrepInventory</title>
</head>

<body>
	<div style="align: center;">
		<h1>PrepInventory</h1>
		<h3>### Location here ###</h3>
	</div>

	<?php
		$sql = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
			
				#Retrive UNIT:
				$sql2 = "SELECT * FROM inv_units WHERE `id` = ". $row['unit'];
				$result2 = mysqli_query($link, $sql2);
				if (mysqli_num_rows($result2) > 0) {
					while($row2 = mysqli_fetch_assoc($result2)) {
						$unit = $row2['name'];
					}
				}
			
				print '<table border="1" WIDTH="600"><tr><td>'. $row['name'] .'<br>'. $row['description'] .'</td><td rowspan="2" valign="top" width="150"><small>Out ('. $unit .'):</small></td></tr></tr><td>Max: '. $row['qty_max'] .' '. $unit .'</td></tr></table>';
				print '<br>';
			}
		}
	?>

</body>

</html>

<?PHP include('xinc.foot.php'); ?>