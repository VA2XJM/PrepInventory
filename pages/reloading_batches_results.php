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
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Batch
	#
	print '<h1>Batches - Results</h1> Input grouping stats done with this batch and validate.</b>';

	if (isset($notice)) { print $notice; }

	print '<table border="1" width="80%"><tr><th>Batch ID</th><th>Ammo Lot ID</th><th>Caliber</th><th>Bullet</th><th>Powder</th><th>Primer</th><th>Powder Charge</th></tr>';
	print '<tr><td>'.$id.'</td><td>'.$lot.'</td><td>'.$caliber_name.'</td><td>'.$bullet_name.'</td><td>'.$powder_name.'</td><td>'.$primer_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>';
	print '</table><br>';

	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>

	<div class="col-lg-8"><div class="panel panel-default">
		<div class="panel-heading">
			Grouping
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<form role="form" method="post">
				<div class="form-inline">
					<label>Grouping & Unit</label><br>
					<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="grouping">
					<select class="form-control" name="unit">
					<?php
						$sql = "SELECT * FROM `inv_units` ORDER BY `name` ASC";
						$result = mysqli_query($link, $sql);
						if (mysqli_num_rows($result) < 1) { print ""; }
						else {
							while($row = mysqli_fetch_assoc($result)) {
								print '<option value="'. $row["id"] .'">' . $row["name"] . '</option>';
							}
						}
					?>
					</select>
				</div>
				<div class="checkbox">
					<label>
						<input name="reject" type="checkbox" value="0">Reject this batch.
					</label>
				</div><br>
				<button type="submit" class="btn btn-default" name="go" value="1">Submit</button>
			</form>
		</div>
	</div></div>
<?PHP
	}
	else {
		$grouping = $_POST['grouping'];
		$grouping_unit = $_POST['unit'];
		$grouping_reject = '1';
		$grouping_reject = $_POST['reject'];

		if (is_numeric($_POST['grouping'])) {
			$sql = "UPDATE `reloading_batches` SET `test_result`='$grouping_reject', `test_grouping`='$grouping', `test_grouping_unit`='$grouping_unit' WHERE id = '$id'";
			$result = mysqli_query($link, $sql);

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Batch stats has been added.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while added batch stats.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
		print $notice;
	}
?>