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
	## Check if batch exists
	#
	if (!empty($_GET['id'])) {
		if (is_numeric($_GET['id'])) {
			$id = $_GET['id'];

			$sql = "SELECT *, t1.id AS `bid` FROM reloading_batches t1 LEFT JOIN reloading_data t2 ON t1.data = t2.id WHERE t1.id = '$id'";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					# Batch infos:
					$caliber = $row['caliber'];
					$lot = $row['lot'];
					$charge = $row['powder_charge'];

					# Shell lot qty
					$sqlx = "SELECT * FROM reloading_shell_lots WHERE id = '$lot'";
					$resultx = mysqli_query($link, $sqlx);
					if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $qty = $rowx['qty']; } }

					# Reloading Data:
					$case_length_max = $row['case_length_max'];
					$case_length_trimto = $row['case_length_trimto'];
					$oal_max = $row['oal_max'];

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
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Reloading Data
	#
	if (isset($notice)) { print $notice; }
?>

	<div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
			Ammo Reloading Data
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			Reloading LOT #<b><?PHP print $id; ?></b> a lot of <b><?PHP print $qty; ?></b>x <b><?PHP print $caliber_name; ?></b>.<br>
			<br>
			Shell Data:<br>
			&nbsp; -> Max. Case Lenght: <?PHP print $case_length_max; ?><br>
			&nbsp; -> Trim Case To : <?PHP print $case_length_trimto; ?><br>
			&nbsp; -> Max. Overall Lenght: <?PHP print $oal_max; ?><br>
			<br>
			Primers: <?PHP print $primer_name; ?><br>
			Powder: <?PHP print "$charge $powder_unit OF $powder_name"; ?><br>
			Bullets: <?PHP print $bullet_name; ?><br>
		</div>
	</div></div>