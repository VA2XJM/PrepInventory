<?PHP
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	# Check if session exists.
	#  If Session (UID) is not existing, redirect to login.php
	#  Else show the page.
	if (empty($_SESSION['username'])) {
		header('location:login.php');
		die();
	}

	#
	## Switch approval batch
	#
	if (!empty($_GET['switch'])) {
		if (is_numeric($_GET['switch'])) {
			$id = $_GET['switch'];
			$act = $_GET['act'];
			$sql = "UPDATE `reloading_batches` SET `test_result`='$act' WHERE id = '$id'";
			$result = mysqli_query($link, $sql);

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Batch status changed.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while changing batch status.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Batch
	#
	print '<h1>Batches - Results</h1> Here is the actual list of batches that have been shot. You can review grouping results and change acceptance status.';

	if (isset($notice)) { print $notice; }

	print '<br><br><a href="?page=batches_add">Create a new batch</a> - <a href="?page=batches">View open batches</a>';
	print '<table border="1" width="80%"><tr><th>Batch ID</th><th>Ammo Lot ID</th><th>Caliber</th><th>Bullet</th><th>Powder</th><th>Primer</th><th>Powder Charge</th><th>Grouping</th><th width="20px">&nbsp;</th></tr>';
	$sql = "SELECT *, t1.id AS `bid` FROM reloading_batches t1 LEFT JOIN reloading_data t2 ON t1.data = t2.id WHERE test_grouping IS NOT NULL GROUP BY t1.id ORDER BY t1.test_grouping ASC";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			# Batch infos:
			$lot = $row['lot'];
			$caliber = $row['caliber'];
			$charge = $row['powder_charge'];
			$grouping = $row['test_grouping'];
			$grouping_unit = $row['test_grouping_unit'];
			$test_result = $row['test_result'];
			if ($test_result == '0') { $group_bg = "#FF0000"; $icon = "fa-thumbs-up"; $switch_act = "1"; }
			else { $group_bg = "#00FF00"; $icon = "fa-thumbs-down"; $switch_act = "0"; } 

			# Caliber Name
			$sqlx = "SELECT * FROM reloading_calibers WHERE id = '$caliber'";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $caliber_name = $rowx['name']; } }

			# Bullet Name
			$bullet_id = $row['bullet'];
			$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$bullet_id' ORDER BY t2.name";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $bullet_name = $rowx['name']; } }

			# Powder Name & Unit
			$powder_id = $row['powder'];
			$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$powder_id' ORDER BY t2.name";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $powder_name = $rowx['name']; $powder_unit = $rowx['unit']; } }

			$sqlx = "SELECT * FROM inv_units WHERE id = '$powder_unit'";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $powder_unit = $rowx['name']; } }

			# Primer Name
			$primer_id = $row['primer'];
			$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$primer_id' ORDER BY t2.name";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $primer_name = $rowx['name']; } }

			# Grouping unit name
			$sqlx = "SELECT * FROM inv_units WHERE id = '$grouping_unit'";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $grouping_unit = $rowx['name']; } }

			print '<tr><td>'.$row['bid'].'</td><td>'.$lot.'</td><td>'.$caliber_name.'</td><td>'.$bullet_name.'</td><td>'.$powder_name.'</td><td>'.$primer_name.'</td><td>'.$charge.' '.$powder_unit.'</td><td bgcolor="'.$group_bg.'">'.$grouping.' '.$grouping_unit.'</td><td><a href="?page=batches_stats&switch='. $row['bid'] .'&act='.$switch_act.'" title="Change accept/reject status"><i class="fa '.$icon.' fa-fw"></i></a></td></tr>';
		}
	}
	print '</table>';
?>