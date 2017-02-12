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
	## Removing batch
	#
	if (!empty($_GET['delete'])) {
		if (is_numeric($_GET['delete'])) {
			$id = $_GET['delete'];
			$sql = "DELETE FROM `reloading_batches` WHERE `id`='$id'";
			$result = mysqli_query($link, $sql);

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Batch deleted.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while deleting batch.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Batch
	#

	if (isset($notice)) { print $notice; }

	$sql = "SELECT *, t1.id AS `bid` FROM reloading_batches t1 LEFT JOIN reloading_data t2 ON t1.data = t2.id WHERE test_grouping IS NULL ORDER BY t1.id ASC";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			# Batch infos:
			$lot = $row['lot'];
			$caliber = $row['caliber'];
			$charge = $row['powder_charge'];

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

		}
	}
	print '</table>';
?>