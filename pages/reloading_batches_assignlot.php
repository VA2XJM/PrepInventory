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

					# Caliber Name
					$sqlx = "SELECT * FROM reloading_calibers WHERE id = '$caliber'";
					$resultx = mysqli_query($link, $sqlx);
					if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $caliber_name = $rowx['name']; } }

					# Reloading DATA (For Inventory removal)
					$inv_primer = $row['primer'];
					$inv_bullet = $row['bullet'];
					$inv_powder = $row['powder'];
					$powder_qty = $row['powder_charge'];
				}
			}
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Batch
	#
	if (isset($notice)) { print $notice; }

	if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['lot'])) {
?>

	<div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
			Ammo Lot Assignation
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<form role="form" method="post">
				<div class="form-inline">
					Assign a <b><?PHP print $caliber_name; ?></b> ammo lot to the batch <b><?PHP print $id; ?></b>.<br>
					<select class="form-control" name="lot">
					<option value="0">No lot assigned</option>
					<?php
						$sql = "SELECT * FROM `reloading_shell_lots` WHERE `caliber` = '$caliber' AND `discarded` = '0' AND `reload` < `reload_max` AND `trim` <= `trim_max` ORDER BY `id` ASC";
						$result = mysqli_query($link, $sql);
						if (mysqli_num_rows($result) < 1) { print ""; }
						else {
							while($row = mysqli_fetch_assoc($result)) {
								$lid = $row['id'];
								$tn = '';
								if ($row['trim'] == $row['trim_max']) { $tn = "*"; }
								$sqlx = "SELECT * FROM `reloading_batches` WHERE lot = '$lid' AND test_grouping IS NULL";
								$resultx = mysqli_query($link, $sqlx);
								if (mysqli_num_rows($resultx) < 1) { print '<option value="'. $lid .'">Lot #' . $lid . ' '. $tn .'</option>'; }
							}
						}
					?>
					</select>
				</div>
				<div class="checkbox">
					<label>
						<input name="trim" type="checkbox" value="1">This lot is/will be trimmed.
						<p class="help-block">Above lots displaying an asterisc (*) should be discarded if a new trim is necessary. If you decide to check the box and reload them once again, this shell lot will not be shown next time.</p>
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input name="inv_removal" type="checkbox" value="1">Remove reloading component from inventory
						<p class="help-block">Check this box to remove bullets, powder and primers from inventory right now. If not checked, you will need to manually remove component from inventory.</p>
					</label>
				</div><br>
				<button type="submit" class="btn btn-default" name="go" value="1">Submit</button>
			</form>
		</div>
	</div></div>
<?PHP
	}
	else {
		$lot = $_POST['lot'];
		$notice = "";

		if (is_numeric($_POST['lot'])) {
			$sql = "UPDATE `reloading_batches` SET `lot`='$lot' WHERE id = '$id'"; $result = mysqli_query($link, $sql);
			if ($_POST['lot'] > 0) { $sql = "UPDATE `reloading_shell_lots` SET `reload`=reload+1 WHERE id = '$lot'"; $result = mysqli_query($link, $sql); }

			if (isset($_POST['trim']) && $_POST['lot'] > 0) { 
				$sql = "UPDATE `reloading_batches` SET `trim`='1' WHERE id = '$id'"; $result = mysqli_query($link, $sql); 
				$sql = "UPDATE `reloading_shell_lots` SET `trim`=trim+1 WHERE id = '$lot'"; $result = mysqli_query($link, $sql);
			}

			if (isset($_POST['inv_removal']) && $_POST['lot'] > 0) {
				# Get shells # in lot
				$sqlz = "SELECT * FROM reloading_shell_lots WHERE id = '$lot'";
				$resultz = mysqli_query($link, $sqlz);
				if (mysqli_num_rows($resultz) > 0) {
					while($rowz = mysqli_fetch_assoc($resultz)) {
						$lot_qty = $rowz['qty'];
					}
				}

				# Calculate powder qty to remove from inv.
				# Other components are removed once per shell.
				$rem_powder = $powder_qty * $lot_qty;

				# Update inventory and put logs.
				$sqlz = "UPDATE `inventory` SET `qty`=qty-$rem_powder WHERE id = '$inv_powder'"; $resultz = mysqli_query($link, $sqlz);
				$sqlz = "UPDATE `inventory` SET `qty`=qty-$lot_qty WHERE id = '$inv_bullet'"; $resultz = mysqli_query($link, $sqlz);
				$sqlz = "UPDATE `inventory` SET `qty`=qty-$lot_qty WHERE id = '$inv_primer'"; $resultz = mysqli_query($link, $sqlz);

				# Find location for logs
				$sqlz = "SELECT * FROM `inventory` WHERE `id` = '$inv_powder'";
				$resultz = mysqli_query($link, $sqlz);
				if (mysqli_num_rows($resultz) < 1) { $notice .= '<div class="panel panel-danger"><div class="panel-heading">Can\'t find inventory location for powder.</div></div>'; }
				else { while($row = mysqli_fetch_assoc($resultz)) { $inv_loc_powder = $row['location']; } }

				$sqlz = "SELECT * FROM `inventory` WHERE `id` = '$inv_bullet'";
				$resultz = mysqli_query($link, $sqlz);
				if (mysqli_num_rows($resultz) < 1) { $notice .= '<div class="panel panel-danger"><div class="panel-heading">Can\'t find inventory location for bullets.</div></div>'; }
				else { while($row = mysqli_fetch_assoc($resultz)) { $inv_loc_bullet = $row['location']; } }

				$sqlz = "SELECT * FROM `inventory` WHERE `id` = '$inv_primer'";
				$resultz = mysqli_query($link, $sqlz);
				if (mysqli_num_rows($resultz) < 1) { $notice .= '<div class="panel panel-danger"><div class="panel-heading">Can\'t find inventory location for primers.</div></div>'; }
				else { while($row = mysqli_fetch_assoc($resultz)) { $inv_loc_primer = $row['location']; } }

				# Add a line to the inventory log
				$username = $_SESSION['username'];
				$sqlz = "INSERT INTO `inv_log` (`item`, `action`, `qty`, `user`, `location`) VALUES ('$inv_powder', '-', '$rem_powder', '$username', '$inv_loc_powder')"; $resultz = mysqli_query($link, $sqlz);
				$sqlz = "INSERT INTO `inv_log` (`item`, `action`, `qty`, `user`, `location`) VALUES ('$inv_bullet', '-', '$lot_qty', '$username', '$inv_loc_bullet')"; $resultz = mysqli_query($link, $sqlz);
				$sqlz = "INSERT INTO `inv_log` (`item`, `action`, `qty`, `user`, `location`) VALUES ('$inv_primer', '-', '$lot_qty', '$username', '$inv_loc_primer')"; $resultz = mysqli_query($link, $sqlz);
			}

			if ($result) {	$notice .= '<div class="panel panel-success"><div class="panel-heading">Batch has been assigned.</div></div>'; }
			else { $notice .= '<div class="panel panel-danger"><div class="panel-heading">Error while updating batch.</div></div>'; }

			if ($_POST['lot'] == 0) { $notice .= '<div class="panel panel-danger"><div class="panel-heading">Remove reloading components from inventory manually.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
		print $notice;
	}
?>