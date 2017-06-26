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

	<div class="col-lg-8"><div class="panel panel-default">
		<div class="panel-heading">
			Ammo Lot Assignation
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<form role="form" method="post">
				<div class="form-inline">
					Assign a <b><?PHP print $caliber_name; ?></b> ammo lot to the batch <b><?PHP print $id; ?></b>.<br>
					<select class="form-control" name="lot">
					<?php
						$sql = "SELECT * FROM `reloading_shell_lots` WHERE `caliber` = '$caliber' AND `reload` < `reload_max` AND `trim` <= `trim_max` ORDER BY `id` ASC";
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
						<p class="help-block">Above lots displaying an asterisc should be discarded if a new trim is necessary. If you decide to check the box and reload them once again, this shell lot will not be shown next time.</p>
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

		if (is_numeric($_POST['lot'])) {
			$sql = "UPDATE `reloading_batches` SET `lot`='$lot' WHERE id = '$id'"; $result = mysqli_query($link, $sql);
			$sql = "UPDATE `reloading_shell_lots` SET `reload`=reload+1 WHERE id = '$lot'"; $result = mysqli_query($link, $sql);

			if (isset($_POST['trim'])) { 
				$sql = "UPDATE `reloading_batches` SET `trim`='1' WHERE id = '$id'"; $result = mysqli_query($link, $sql); 
				$sql = "UPDATE `reloading_shell_lots` SET `trim`=trim+1 WHERE id = '$lot'"; $result = mysqli_query($link, $sql);
			}

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Batch has been updated.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while updating batch.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
		print $notice;
	}
?>