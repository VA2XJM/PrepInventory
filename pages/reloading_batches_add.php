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
	## Load and Display Batch
	#
	print '<h1>Batches</h1> Create a new batch.<br>';

	if (isset($notice)) { print $notice; }

	if (empty($_GET['caliber'])) {
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Select a caliber
			</div>
			<div class="panel-body">
				<?php
					$sql = "SELECT * FROM `reloading_calibers` ORDER BY `name` ASC";
					$result = mysqli_query($link, $sql);
					if (mysqli_num_rows($result) < 1) { print ""; }
					else {
						while($row = mysqli_fetch_assoc($result)) {
							print '<a href="?page=batches_add&caliber='.$row['id'].'">' . $row['name'] . '</a><br>';
						}
					}
				?>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && empty($_GET['data'])) {
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Select a reloading data
			</div>
			<div class="panel-body">
				<?php
					$caliber = $_GET['caliber'];
					$sql = "SELECT * FROM `reloading_data` WHERE caliber = '$caliber' ORDER BY `id` ASC";
					$result = mysqli_query($link, $sql);
					if (mysqli_num_rows($result) < 1) { print ""; }
					else {
						while($row = mysqli_fetch_assoc($result)) {
							# Charge min-max
							$charge_min = $row['powder_min'];
							$charge_max = $row['powder_max'];

							# Bullet Name
							$bullet_id = $row['bullet'];
							$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$bullet_id' ORDER BY t2.name";
							$resultx = mysqli_query($link, $sqlx);
							if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $bullet_name = $rowx['name']; } }

							# Powder Name
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

							print '<a href="?page=batches_add&caliber='.$caliber.'&data='.$row['id'].'">' . $bullet_name . ' - ' . $primer_name . ' - ' . $powder_name . ' ('.$charge_min.'-'.$charge_max.' '.$powder_unit.')</a><br>';
						}
					}
				?>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && isset($_GET['data']) && is_numeric($_GET['data']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Powder charges
			</div>
			<div class="panel-body">
				<label>Select powder charges for each ammo lot you wish to reload. Indicate up to 10 charges, empty charges will not be generated.<br><b>At least one charge is required.</b></label><br><br>
				<?php
					$data = $_GET['data'];
					$sql = "SELECT * FROM `reloading_data` WHERE id = '$data' ORDER BY `id` ASC";
					$result = mysqli_query($link, $sql);
					if (mysqli_num_rows($result) < 1) { print ""; }
					else {
						while($row = mysqli_fetch_assoc($result)) {
							# Charge min-max
							$charge_min = $row['powder_min'];
							$charge_max = $row['powder_max'];

							# Bullet Name
							$bullet_id = $row['bullet'];
							$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$bullet_id' ORDER BY t2.name";
							$resultx = mysqli_query($link, $sqlx);
							if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $bullet_name = $rowx['name']; } }

							# Powder Name
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

							print '<b>Selected reloading data:</b> ' . $bullet_name . ' - ' . $primer_name . ' - ' . $powder_name . ' (Min/Max: '.$charge_min.' - '.$charge_max.' '.$powder_unit.')<br>';
						}
					}
				?>
				<br><form role="form" method="post">
					<div class="form-inline">
						<label>Charges</label><br>
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge1">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge2">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge3">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge4">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge5">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge6">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge7">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge8">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge9">
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="charge10">
					</div>
					<br>
					<button type="submit" class="btn btn-default" name="go" value="1">Submit</button>
				</form>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && isset($_GET['data']) && is_numeric($_GET['data']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		$caliber = $_GET['caliber'];
		$data = $_GET['data'];
		$sql = "SELECT * FROM `reloading_data` WHERE id = '$data' ORDER BY `id` ASC";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) < 1) { print ""; }
		else {
			while($row = mysqli_fetch_assoc($result)) {
				# Charge min-max
				$charge_min = $row['powder_min'];
				$charge_max = $row['powder_max'];

				# Bullet Name
				$bullet_id = $row['bullet'];
				$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$bullet_id' ORDER BY t2.name";
				$resultx = mysqli_query($link, $sqlx);
				if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $bullet_name = $rowx['name']; } }

				# Powder Name
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

		$table = '<table border="1" width="100%"><tr><th>Batch ID</th><th>Bullet</th><th>Primer</th><th>Powder</th><th>Powder Charge</th></tr>';
		if (isset($_POST['charge1']) && is_numeric($_POST['charge1'])) {
			$charge = $_POST['charge1'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge2']) && is_numeric($_POST['charge2'])) {
			$charge = $_POST['charge2'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge3']) && is_numeric($_POST['charge3'])) {
			$charge = $_POST['charge3'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge4']) && is_numeric($_POST['charge4'])) {
			$charge = $_POST['charge4'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge5']) && is_numeric($_POST['charge5'])) {
			$charge = $_POST['charge5'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge6']) && is_numeric($_POST['charge6'])) {
			$charge = $_POST['charge6'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge7']) && is_numeric($_POST['charge7'])) {
			$charge = $_POST['charge7'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge8']) && is_numeric($_POST['charge8'])) {
			$charge = $_POST['charge8'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge9']) && is_numeric($_POST['charge9'])) {
			$charge = $_POST['charge9'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		if (isset($_POST['charge10']) && is_numeric($_POST['charge10'])) {
			$charge = $_POST['charge10'];
			$sql = "INSERT INTO `reloading_batches` (`caliber`, `data`, `powder_charge`) VALUES ('$caliber', '$data', '$charge')";
			$result = mysqli_query($link, $sql);
			if ($result) { $last_id = mysqli_insert_id($link); $table .= '<tr><td>'.$last_id.'</td><td>'.$bullet_name.'</td><td>'.$primer_name.'</td><td>'.$powder_name.'</td><td>'.$charge.' '.$powder_unit.'</td></tr>'; }
		}

		$table .= '</table>';

		print '<div class="panel panel-default">
			<div class="panel-heading">
				Success
			</div>
			<div class="panel-body">';
		print $table;
		print '</div></div>';
	}
?>