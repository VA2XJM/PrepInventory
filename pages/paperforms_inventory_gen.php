<?PHP
	session_start();
	include('xinc.config.php');

	# Check if session exists.
	#  If Session (UID) is not existing, redirect to login.php
	#  Else show the page.
	if (empty($_SESSION['username'])) {
		header('location:login.php');
		die();
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>PrepInventory</title>
</head>

<body>
	<div style="align: center;">
		<h1>PrepInventory</h1>
		<h3>Printable Full Inventory</h3>
	</div>

<?php 
		$sql = "SELECT * FROM `inv_locations_1` ORDER BY `name` ASC";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) < 1) { print "No locations has been configured yet."; }
		else {
			print '<table border="1" WIDTH="600">';
			while($row = mysqli_fetch_assoc($result)) {
				# Level 1 locations
				print '<tr><td><img src="'. $row['icon'] .'" class="icon" width="16px" heigth="16px"> ' . $row["name"] . ' </td><td style="width:200px;"></td></tr>';
				$sql2 = "SELECT * FROM `inv_locations_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
				$result2 = mysqli_query($link, $sql2);
				if (mysqli_num_rows($result2) > 0) {
					while($row2 = mysqli_fetch_assoc($result2)) {
						# Level 2 locations
						print '<tr><td>&emsp; <img src="'. $row2['icon'] .'" class="icon" width="16px" heigth="16px"> ' . $row2["name"] . ' </td><td></td></tr>';
						$sql3 = "SELECT * FROM `inv_locations_3` WHERE parent = '". $row2['id'] ."' ORDER BY `name` ASC";
						$result3 = mysqli_query($link, $sql3);
						if (mysqli_num_rows($result3) > 0) {
							while($row3 = mysqli_fetch_assoc($result3)) {
								# Level 3 locations
								print '<tr><td>&emsp;&emsp; <img src="'. $row3['icon'] .'" class="icon" width="16px" heigth="16px"> ' . $row3["name"] . ' </td><td></td></tr>';
								$sql4 = "SELECT * FROM `inv_locations_4` WHERE parent = '". $row3['id'] ."' ORDER BY `name` ASC";
								$result4 = mysqli_query($link, $sql4);
								if (mysqli_num_rows($result4) > 0) {
									while($row4 = mysqli_fetch_assoc($result4)) {
										# Level 4 locations
										print '<tr><td>&emsp;&emsp;&emsp; <img src="'. $row4['icon'] .'" class="icon" width="16px" heigth="16px"> ' . $row4["name"] . ' </td><td></td></tr>';
										# Items for fourth level
										$loc = '4-'. $row4['id'];
										$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.location = '$loc' ORDER BY t2.name";
										$resultx = mysqli_query($link, $sqlx);
										if (mysqli_num_rows($resultx) > 0) {
											while($rowx = mysqli_fetch_assoc($resultx)) {
												$pcact = '';
												$percent = round($rowx['qty'] / $rowx['qty_max'] * 100);
												$qty = $rowx['qty'];
												$qtymax = $rowx['qty_max'];
												if ($percent < '1') { $pclevel = 'progress-bar-danger'; $pcact = ' progress-striped active'; $percent = '100'; }
												elseif ($percent < '30') { $pclevel = 'progress-bar-danger'; }
												elseif ($percent < '60') { $pclevel = 'progress-bar-warning'; }
												elseif ($percent < '90') { $pclevel = 'progress-bar-info'; }
												elseif ($percent < '101') { $pclevel = 'progress-bar-success'; }
												else { $pclevel = 'progress-bar-success'; $pcact = ' progress-striped active'; }
												print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon" width="16px" heigth="16px"> <a href="inventory_details.php?id='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td><td>'.$qty.' / '.$qtymax.' ('.$percent.'%)</td></tr>';
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
										$pcact = '';
										$percent = round($rowx['qty'] / $rowx['qty_max'] * 100);
										$qty = $rowx['qty'];
										$qtymax = $rowx['qty_max'];
										if ($percent < '1') { $pclevel = 'progress-bar-danger'; $pcact = ' progress-striped active'; $percent = '100'; }
										elseif ($percent < '30') { $pclevel = 'progress-bar-danger'; }
										elseif ($percent < '60') { $pclevel = 'progress-bar-warning'; }
										elseif ($percent < '90') { $pclevel = 'progress-bar-info'; }
										elseif ($percent < '101') { $pclevel = 'progress-bar-success'; }
										else { $pclevel = 'progress-bar-success'; $pcact = ' progress-striped active'; }
										print '<tr><td>&emsp;&emsp;&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon" width="16px" heigth="16px"> <a href="inventory_details.php?id='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td><td>'.$qty.' / '.$qtymax.' ('.$percent.'%)</td></tr>';
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
								$pcact = '';
								$percent = round($rowx['qty'] / $rowx['qty_max'] * 100);
								$qty = $rowx['qty'];
								$qtymax = $rowx['qty_max'];
								if ($percent < '1') { $pclevel = 'progress-bar-danger'; $pcact = ' progress-striped active'; $percent = '100'; }
								elseif ($percent < '30') { $pclevel = 'progress-bar-danger'; }
								elseif ($percent < '60') { $pclevel = 'progress-bar-warning'; }
								elseif ($percent < '90') { $pclevel = 'progress-bar-info'; }
								elseif ($percent < '101') { $pclevel = 'progress-bar-success'; }
								else { $pclevel = 'progress-bar-success'; $pcact = ' progress-striped active'; }
								print '<tr><td>&emsp;&emsp; <img src="'. $rowx['icon'] .'" class="icon" width="16px" heigth="16px"> <a href="inventory_details.php?id='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td><td>'.$qty.' / '.$qtymax.' ('.$percent.'%)</td></tr>';
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
						$pcact = '';
						$percent = round($rowx['qty'] / $rowx['qty_max'] * 100);
						$qty = $rowx['qty'];
						$qtymax = $rowx['qty_max'];
						if ($percent < '1') { $pclevel = 'progress-bar-danger'; $pcact = ' progress-striped active'; $percent = '100'; }
						elseif ($percent < '30') { $pclevel = 'progress-bar-danger'; }
						elseif ($percent < '60') { $pclevel = 'progress-bar-warning'; }
						elseif ($percent < '90') { $pclevel = 'progress-bar-info'; }
						elseif ($percent < '101') { $pclevel = 'progress-bar-success'; }
						else { $pclevel = 'progress-bar-success'; $pcact = ' progress-striped active'; }
						print '<tr><td>&emsp; <img src="'. $rowx['icon'] .'" class="icon" width="16px" heigth="16px"> <a href="inventory_details.php?id='. $rowx['invid'] .'">' . $rowx["name"] . '</a></td><td>'.$qty.' / '.$qtymax.' ('.$percent.'%)</td></tr>';
					}
				}
				# /Items
				print '<tr><td></td><td></td></tr>';
			}
			print '</table>';
		}
	?>

</body>

</html>

<?PHP include('xinc.foot.php'); ?>