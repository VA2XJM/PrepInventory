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
	## Checking values and correcting
	#
	if (isset($_POST['go'])) {
		if (!is_numeric($_POST['powder_min'])) { $_POST['powder_min'] = '0'; }
		if (!is_numeric($_POST['powder_max'])) { $_POST['powder_max'] = '0'; }
		if (!is_numeric($_POST['maxol'])) { $_POST['maxol'] = '0'; }
		if (!is_numeric($_POST['mcl'])) { $_POST['mcl'] = '0'; }
		if (!is_numeric($_POST['ctl'])) { $_POST['ctl'] = '0'; }
	}

	#
	## Load and Display Data
	#
	print '<h1>Adding Data</h1>';

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
							print '<a href="?page=data_add&caliber='.$row['id'].'">' . $row['name'] . '</a><br>';
						}
					}
				?>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && empty($_GET['bullet'])) {
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Select a bullet
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<tbody>
							<?php 
								$sql = "SELECT * FROM `inv_locations_1` ORDER BY `name` ASC";
								$result = mysqli_query($link, $sql);
								if (mysqli_num_rows($result) < 1) { print "No locations has been configured yet."; }
								else {
									while($row = mysqli_fetch_assoc($result)) {
										# Level 1 locations
										print '<tr><td><img src="'. $row['icon'] .'" class="icon"> ' . $row["name"] . ' </td></tr>';
										$sql2 = "SELECT * FROM `inv_locations_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
										$result2 = mysqli_query($link, $sql2);
										if (mysqli_num_rows($result2) > 0) {
											while($row2 = mysqli_fetch_assoc($result2)) {
												# Level 2 locations
												print '<tr><td>&emsp; <img src="'. $row2['icon'] .'" class="icon"> ' . $row2["name"] . ' </td></tr>';
												$sql3 = "SELECT * FROM `inv_locations_3` WHERE parent = '". $row2['id'] ."' ORDER BY `name` ASC";
												$result3 = mysqli_query($link, $sql3);
												if (mysqli_num_rows($result3) > 0) {
													while($row3 = mysqli_fetch_assoc($result3)) {
														# Level 3 locations
														print '<tr><td>&emsp;&emsp; <img src="'. $row3['icon'] .'" class="icon"> ' . $row3["name"] . ' </td></tr>';
														$sql4 = "SELECT * FROM `inv_locations_4` WHERE parent = '". $row3['id'] ."' ORDER BY `name` ASC";
														$result4 = mysqli_query($link, $sql4);
														if (mysqli_num_rows($result4) > 0) {
															while($row4 = mysqli_fetch_assoc($result4)) {
																# Level 4 locations
																print '<tr><td>&emsp;&emsp;&emsp; <img src="'. $row4['icon'] .'" class="icon"> ' . $row4["name"] . ' </td></tr>';
																# Items for fourth level
																$loc = '4-'. $row4['id'];
																$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
																$resultx = mysqli_query($link, $sqlx);
																if (mysqli_num_rows($resultx) > 0) {
																	while($rowx = mysqli_fetch_assoc($resultx)) {
																		print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td></tr>';
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
																print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td></tr>';
															}
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
														print '<tr><td>&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td></tr>';
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
												print '<tr><td>&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td></tr>';
											}
										}
										# /Items
										print '<tr><td></td><td></td></tr>';
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && isset($_GET['bullet']) && is_numeric($_GET['bullet']) && empty($_GET['powder'])) {
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Select powder
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<tbody>
							<?php 
								$sql = "SELECT * FROM `inv_locations_1` ORDER BY `name` ASC";
								$result = mysqli_query($link, $sql);
								if (mysqli_num_rows($result) < 1) { print "No locations has been configured yet."; }
								else {
									while($row = mysqli_fetch_assoc($result)) {
										# Level 1 locations
										print '<tr><td><img src="'. $row['icon'] .'" class="icon"> ' . $row["name"] . ' </td></tr>';
										$sql2 = "SELECT * FROM `inv_locations_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
										$result2 = mysqli_query($link, $sql2);
										if (mysqli_num_rows($result2) > 0) {
											while($row2 = mysqli_fetch_assoc($result2)) {
												# Level 2 locations
												print '<tr><td>&emsp; <img src="'. $row2['icon'] .'" class="icon"> ' . $row2["name"] . ' </td></tr>';
												$sql3 = "SELECT * FROM `inv_locations_3` WHERE parent = '". $row2['id'] ."' ORDER BY `name` ASC";
												$result3 = mysqli_query($link, $sql3);
												if (mysqli_num_rows($result3) > 0) {
													while($row3 = mysqli_fetch_assoc($result3)) {
														# Level 3 locations
														print '<tr><td>&emsp;&emsp; <img src="'. $row3['icon'] .'" class="icon"> ' . $row3["name"] . ' </td></tr>';
														$sql4 = "SELECT * FROM `inv_locations_4` WHERE parent = '". $row3['id'] ."' ORDER BY `name` ASC";
														$result4 = mysqli_query($link, $sql4);
														if (mysqli_num_rows($result4) > 0) {
															while($row4 = mysqli_fetch_assoc($result4)) {
																# Level 4 locations
																print '<tr><td>&emsp;&emsp;&emsp; <img src="'. $row4['icon'] .'" class="icon"> ' . $row4["name"] . ' </td></tr>';
																# Items for fourth level
																$loc = '4-'. $row4['id'];
																$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
																$resultx = mysqli_query($link, $sqlx);
																if (mysqli_num_rows($resultx) > 0) {
																	while($rowx = mysqli_fetch_assoc($resultx)) {
																		print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
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
																print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
															}
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
														print '<tr><td>&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
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
												print '<tr><td>&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
											}
										}
										# /Items
										print '<tr><td></td><td></td></tr>';
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && isset($_GET['bullet']) && is_numeric($_GET['bullet']) && isset($_GET['powder']) && is_numeric($_GET['powder']) && empty($_GET['primer'])) {
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Select primer
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<tbody>
							<?php 
								$sql = "SELECT * FROM `inv_locations_1` ORDER BY `name` ASC";
								$result = mysqli_query($link, $sql);
								if (mysqli_num_rows($result) < 1) { print "No locations has been configured yet."; }
								else {
									while($row = mysqli_fetch_assoc($result)) {
										# Level 1 locations
										print '<tr><td><img src="'. $row['icon'] .'" class="icon"> ' . $row["name"] . ' </td></tr>';
										$sql2 = "SELECT * FROM `inv_locations_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
										$result2 = mysqli_query($link, $sql2);
										if (mysqli_num_rows($result2) > 0) {
											while($row2 = mysqli_fetch_assoc($result2)) {
												# Level 2 locations
												print '<tr><td>&emsp; <img src="'. $row2['icon'] .'" class="icon"> ' . $row2["name"] . ' </td></tr>';
												$sql3 = "SELECT * FROM `inv_locations_3` WHERE parent = '". $row2['id'] ."' ORDER BY `name` ASC";
												$result3 = mysqli_query($link, $sql3);
												if (mysqli_num_rows($result3) > 0) {
													while($row3 = mysqli_fetch_assoc($result3)) {
														# Level 3 locations
														print '<tr><td>&emsp;&emsp; <img src="'. $row3['icon'] .'" class="icon"> ' . $row3["name"] . ' </td></tr>';
														$sql4 = "SELECT * FROM `inv_locations_4` WHERE parent = '". $row3['id'] ."' ORDER BY `name` ASC";
														$result4 = mysqli_query($link, $sql4);
														if (mysqli_num_rows($result4) > 0) {
															while($row4 = mysqli_fetch_assoc($result4)) {
																# Level 4 locations
																print '<tr><td>&emsp;&emsp;&emsp; <img src="'. $row4['icon'] .'" class="icon"> ' . $row4["name"] . ' </td></tr>';
																# Items for fourth level
																$loc = '4-'. $row4['id'];
																$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
																$resultx = mysqli_query($link, $sqlx);
																if (mysqli_num_rows($resultx) > 0) {
																	while($rowx = mysqli_fetch_assoc($resultx)) {
																		print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$_GET['powder'].'&primer='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
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
																print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$_GET['powder'].'&primer='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
															}
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
														print '<tr><td>&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$_GET['powder'].'&primer='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
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
												print '<tr><td>&emsp; <img src="'. $rowx['icon'] .'" class="icon"> <a href="?page=data_add&caliber='.$_GET['caliber'].'&bullet='. $_GET['bullet'] .'&powder='.$_GET['powder'].'&primer='.$rowx['invid'].'">' . $rowx["name"] . '</a></td></tr>';
											}
										}
										# /Items
										print '<tr><td></td><td></td></tr>';
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && isset($_GET['bullet']) && is_numeric($_GET['bullet']) && isset($_GET['powder']) && is_numeric($_GET['powder']) && isset($_GET['primer']) && is_numeric($_GET['primer']) && !isset($_POST['go'])) {
?>
		<div class="panel panel-default">
			<div class="panel-heading">
				Specs
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<form role="form" method="post">
					<div class="form-inline">
						<label>Powder minimum load &emsp;&nbsp;&nbsp; Powder maximum load</label><br>
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="powder_min"> &nbsp; <input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="powder_max">
					</div>
					<div class="form-inline">
						<label>Max overall length &emsp;&emsp;&emsp; Max case length &emsp;&emsp;&emsp;&emsp;&nbsp; Case trim length</label><br>
						<input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="maxol"> &nbsp; <input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="mcl"> &nbsp; <input class="form-control" type="text" autocomplete="off" placeholder="0.000" name="ctl">
					</div>
					<div class="form-inline">
						<label>Source</label><br>
						<input class="form-control" type="text" autocomplete="off" placeholder="Source" name="source">
					</div><br>
					<button type="submit" class="btn btn-default" name="go" value="1">Submit</button>
				</form>
			</div>
		</div>
<?PHP
	}
	if (isset($_GET['caliber']) && is_numeric($_GET['caliber']) && isset($_GET['bullet']) && is_numeric($_GET['bullet']) && isset($_GET['powder']) && is_numeric($_GET['powder']) && isset($_GET['primer']) && is_numeric($_GET['primer']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		$caliber = $_GET['caliber'];
		$source = $_POST['source'];
		$bullet = $_GET['bullet'];
		$primer = $_GET['primer'];
		$powder = $_GET['powder'];
		$powder_min = $_POST['powder_min'];
		$powder_max = $_POST['powder_max'];
		$oal_max = $_POST['maxol'];
		$clm = $_POST['mcl'];
		$clt = $_POST['ctl'];

		$sql = "INSERT INTO `reloading_data` (`caliber`, `source`, `bullet`, `primer`, `powder`, `powder_min`, `powder_max`, `oal_max`, `case_length_max`, `case_length_trimto`) VALUES ('$caliber', '$source', '$bullet', '$primer', '$powder', '$powder_min', '$powder_max', '$oal_max', '$clm', '$clt')";
		$result = mysqli_query($link, $sql);
		if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">New data added.</div></div>'; }
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while adding new data.</div></div>'; }
		print $notice;
	}
?>