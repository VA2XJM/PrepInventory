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
	## Adding a new lot
	#
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$notice = '';
		$caliber = $_POST['caliber'];
		$qty = $_POST['qty'];
		$max_trim = $_POST['max-trim'];
		$max_reload = $_POST['max-reload'];
		$details = $_POST['details'];
		$sql = "INSERT INTO `reloading_shell_lots` (`caliber`, `trim_max`, `reload_max`, `qty`, `details`) VALUES ('$caliber', '$max_trim', '$max_reload', '$qty', '$details')";
		$result = mysqli_query($link, $sql);
		if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">New shell lot added.</div></div>'; }
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while adding new shell lot.</div></div>'; }

		if ($_POST['inventory-loc'] > 0) {
			$get_id = $_POST['inventory-loc'];
			$sql = "SELECT * FROM `inventory` WHERE `id` = '$get_id'";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) < 1) { $notice .= '<div class="panel panel-danger"><div class="panel-heading">Cannot get shell(s) out of inventory, do it manually.</div></div>'; }
			else {
				while($row = mysqli_fetch_assoc($result)) {
					$t_qty = $row['qty'];
					$t_item = $row['item'];
					$t_location = $row['location'];
				}

				# Calculate new INV and update
				$t_new = $t_qty - $qty;
				$inv_act = '-'; $inv_qty = $_POST['qty'];
				if ($t_new < '1') { $t_new = '0'; $inv_qty = $t_qty; }

				$sql = "UPDATE `inventory` SET `qty`='$t_new' WHERE `id`='$get_id'";
				$result = mysqli_query($link, $sql);
			
				# Add a line to the inventory log
				$username = $_SESSION['username'];
				$sql = "INSERT INTO `inv_log` (`item`, `action`, `qty`, `user`, `location`) VALUES ('$t_item', '$inv_act', '$inv_qty', '$username', '$t_location')";
				$result = mysqli_query($link, $sql);
			}
		}
		else { $notice .= '<div class="panel panel-info"><div class="panel-heading">No shell has been removed from inventory. Please do it manually.</div></div>'; }
	}

	#
	## Removing lot
	#
	if (!empty($_GET['delete'])) {
		if (is_numeric($_GET['delete'])) {
			$id = $_GET['delete'];
			$sql = "UPDATE `reloading_shell_lots` SET `discarded`='1' WHERE `id`='$id'";
			$result = mysqli_query($link, $sql);

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Shell lot deleted.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while deleting shell lot.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Calibers
	#
	print '<h1>Shell lots</h1> Here is the actual list of shell lots on record. You can add new ones or delete existing ones. <b>Deleting a shell lot will not remove any other data.</b>';

	if (isset($notice)) { print $notice; }

	print '<table border="1" width="80%"><tr><th>Caliber (Qty)</th><th>Lot #</th><th>Reload / Reload Max</th><th>Trim / Trim Max</th><th>Details</th><th width="10px">&nbsp;</th></tr>';
	$sql = "SELECT *, t1.id AS `lotid`, t2.name AS `calibername` FROM reloading_shell_lots t1 LEFT JOIN reloading_calibers t2 ON t1.caliber = t2.id WHERE t1.discarded = '0' ORDER BY t2.name";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$reload_color = "";
			if ($row['reload'] >= $row['reload_max']) { $reload_color = "#FF0000"; }
			$trim_color = "";
			if ($row['trim'] >= $row['trim_max']) { $trim_color = "#FF0000"; }
			print '<tr><td>'.$row['calibername'].' ('.$row['qty'].')</td><td>'.$row['lotid'].'</td><td bgcolor="'.$reload_color.'">'.$row['reload'].' / '.$row['reload_max'].'</td><td bgcolor="'.$trim_color.'">'.$row['trim'].' / '.$row['trim_max'].'</td><td>'.$row['details'].'</td><td><a href="?page=shelllots&delete='. $row['lotid'] .'"><i class="fa fa-minus-square fa-fw"></i></a></td></tr>';
		}
	}
	print '</table>';

	print '<h3>Create a new shell lot</h3>';
?>
	<div class="panel panel-default">
		<div class="panel-heading">
			Create a new shell lot
		</div>
		<div class="panel-body">
			<form role="form" method="post">
				<div class="form-group">
					<label>Caliber</label>
					<select class="form-control" name="caliber">
					<?php
						$sql = "SELECT * FROM `reloading_calibers` ORDER BY `name` ASC";
						$result = mysqli_query($link, $sql);
						if (mysqli_num_rows($result) < 1) { print ""; }
						else {
							while($row = mysqli_fetch_assoc($result)) {
								print '<option value="'. $row['id'] .'">' . $row['name'] . '</option>';
							}
						}
					?>
					</select>
				</div>
				<div class="form-inline">
					<label>Quantity &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp; Max trim allowed &emsp;&emsp;&emsp;&emsp;&emsp;&nbsp; Max reload allowed &emsp;&emsp;&emsp;&emsp; Details/Notes</label><br>
					<input class="form-control" type="number" autocomplete="off" placeholder="0" name="qty" value="0"> &nbsp; <input class="form-control" type="number" autocomplete="off" placeholder="1" value="1" name="max-trim"> &nbsp; <input class="form-control" type="number" autocomplete="off" placeholder="1" value="1" name="max-reload"> &nbsp; <input class="form-control" type="text" autocomplete="off" placeholder="" value="" name="details">
				</div>
				<div class="form-group">
					<label>Shell inventory location</label>
					<select class="form-control" name="inventory-loc">
					<option value="0">Ignore / Not in inventory</option>
					<?php
						$sql = "SELECT * FROM `inv_locations_1` ORDER BY `name` ASC";
						$result = mysqli_query($link, $sql);
						if (mysqli_num_rows($result) < 1) { print "No locations has been configured yet."; }
						else {
							while($row = mysqli_fetch_assoc($result)) {
								# Level 1 locations
								print '<option value="0">'. $row["name"] . '</option>';
								$sql2 = "SELECT * FROM `inv_locations_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
								$result2 = mysqli_query($link, $sql2);
								if (mysqli_num_rows($result2) > 0) {
									while($row2 = mysqli_fetch_assoc($result2)) {
										# Level 2 locations
										print '<option value="0"> - '. $row2["name"] . '</option>';
										$sql3 = "SELECT * FROM `inv_locations_3` WHERE parent = '". $row2['id'] ."' ORDER BY `name` ASC";
										$result3 = mysqli_query($link, $sql3);
										if (mysqli_num_rows($result3) > 0) {
											while($row3 = mysqli_fetch_assoc($result3)) {
												# Level 3 locations
												print '<option value="0"> -- '. $row3["name"] . '</option>';
												$sql4 = "SELECT * FROM `inv_locations_4` WHERE parent = '". $row3['id'] ."' ORDER BY `name` ASC";
												$result4 = mysqli_query($link, $sql4);
												if (mysqli_num_rows($result4) > 0) {
													while($row4 = mysqli_fetch_assoc($result4)) {
														# Level 4 locations
														print '<option value="0"> --- '. $row4["name"] . '</option>';
														# Items for fourth level
														$loc = '4-'. $row4['id'];
														$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
														$resultx = mysqli_query($link, $sqlx);
														if (mysqli_num_rows($resultx) > 0) {
															while($rowx = mysqli_fetch_assoc($resultx)) {
																print '<option value="'. $rowx["invid"] .'"> ----> '. $rowx["name"] . '</option>';
															}
														}
														# /Items			
													}
												}
												# Items for third level
												$loc = '3-'. $row3['id'];
												$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
												$resultx = mysqli_query($link, $sqlx);
												if (mysqli_num_rows($resultx) > 0) {
													while($rowx = mysqli_fetch_assoc($resultx)) {
														print '<option value="'. $rowx["invid"] .'"> ---> '. $rowx["name"] . '</option>';									}
												}
												# /Items
											}
										}
										# Items for second level
										$loc = '2-'. $row2['id'];
										$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
										$resultx = mysqli_query($link, $sqlx);
										if (mysqli_num_rows($resultx) > 0) {
											while($rowx = mysqli_fetch_assoc($resultx)) {
												print '<option value="'. $rowx["invid"] .'"> --> '. $rowx["name"] . '</option>';
											}
										}
										# /Items
									}
								}
								# Items for first level
								$loc = '1-'. $row['id'];
								$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
								$resultx = mysqli_query($link, $sqlx);
								if (mysqli_num_rows($resultx) > 0) {
									while($rowx = mysqli_fetch_assoc($resultx)) {
										print '<option value="'. $rowx["invid"] .'"> -> '. $rowx["name"] . '</option>';
									}
								}
								# /Items
							}
						}
					?>
					</select>
				</div>				
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>

