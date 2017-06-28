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
	## Check and mod data.
	#
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (empty($_GET['id'])) { print '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; die(); }
		if (!is_numeric($_POST['powder_min'])) { $_POST['powder_min'] = '0'; }
		if (!is_numeric($_POST['powder_max'])) { $_POST['powder_max'] = '0'; }
		if (!is_numeric($_POST['maxol'])) { $_POST['maxol'] = '0'; }
		if (!is_numeric($_POST['mcl'])) { $_POST['mcl'] = '0'; }
		if (!is_numeric($_POST['ctl'])) { $_POST['ctl'] = '0'; }

		$id = $_GET['id'];
		$source = $_POST['source'];
		$powder_min = $_POST['powder_min'];
		$powder_max = $_POST['powder_max'];
		$oal_max = $_POST['maxol'];
		$clm = $_POST['mcl'];
		$clt = $_POST['ctl'];
		$len_unit = $_POST['len-unit'];

		$sql = "UPDATE `reloading_data` SET `source`='$source', `powder_min`='$powder_min', `powder_max`='$powder_max', `oal_max`='$oal_max', `case_length_max`='$clm', `case_length_trimto`='$clt', `len_unit`='$len_unit' WHERE `id`='$id'";
		$result = mysqli_query($link, $sql);
		if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Data edited.</div></div>'; }
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while editing data.</div></div>'; }
	}

	#
	## Load and Display Data
	#
	if (!empty($_GET['id'])) {
		if (is_numeric($_GET['id'])) {
			$id = $_GET['id'];

			$sql = "SELECT * FROM reloading_data WHERE id = '$id'";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					# Reloading Data:
					$caliber = $row['caliber'];
					$case_length_max = $row['case_length_max'];
					$case_length_trimto = $row['case_length_trimto'];
					$oal_max = $row['oal_max'];
					$powder_min = $row['powder_min'];
					$powder_max = $row['powder_max'];
					$source = $row['source'];

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

					# Length Unit for shell
					$len_unit = $row['len_unit'];
					$len_unit_id = $len_unit;
					$sqlx = "SELECT * FROM inv_units WHERE id = '$len_unit'";
					$resultx = mysqli_query($link, $sqlx);
					if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $len_unit = $rowx['name']; } }
				}
			}
		}
		else { print '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; die(); }
	}
	else { print '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; die(); }

	print '<h1>Editing Data</h1>';

	if (isset($notice)) { print $notice; }
?>

<div class="panel panel-default">
	<div class="panel-heading">
		Specs
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<b>Caliber</b>: <?PHP print $caliber_name ?><br>
		<b>Powder</b>: <?PHP print $powder_name ?><br>
		<b>Bullet</b>: <?PHP print $bullet_name ?><br>
		<b>Primer</b>: <?PHP print $primer_name ?><br><br>

		<form role="form" method="post">
			<div class="form-inline">
				<label>Powder minimum load &emsp;&nbsp;&nbsp; Powder maximum load (<?PHP print $powder_unit; ?>)</label><br>
				<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="powder_min" value="<?PHP print $powder_min; ?>"> &nbsp; <input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="powder_max" value="<?PHP print $powder_max; ?>">
			</div>
			<div class="form-inline">
				<label>Max overall length &emsp;&emsp;&emsp; Max case length &emsp;&emsp;&emsp;&emsp;&nbsp; Case trim length(<?PHP print $len_unit; ?>)</label><br>
				<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="maxol" value="<?PHP print $oal_max; ?>"> &nbsp; <input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="mcl" value="<?PHP print $case_length_max; ?>"> &nbsp; <input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="ctl" value="<?PHP print $case_length_trimto; ?>">
			</div>
			<div class="form-inline">
				<label>Source</label><br>
				<input class="form-control" type="text" autocomplete="off" placeholder="Source" name="source"  value="<?PHP print $source; ?>">
			</div>
			<div class="form-inline">
				<label>Measurement Unit</label><br>
				<select class="form-control" name="len-unit">
				<?php
					$sql = "SELECT * FROM `inv_units` ORDER BY `name` ASC";
					$result = mysqli_query($link, $sql);
					if (mysqli_num_rows($result) < 1) { print ""; }
					else { print $len_unit_id;
						while($row = mysqli_fetch_assoc($result)) {
							$unit_id = $row['id'];
							$selected = "";
							if ($unit_id == $len_unit_id) { $selected = ' selected="selected"'; }
							print '<option value="'. $row["id"] .'"'. $selected .'>' . $row["name"] . '</option>';
						}
					}
				?>
				</select>
			</div><br>
			<button type="submit" class="btn btn-default" name="go" value="1">Submit</button>
		</form>
	</div>
</div>