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
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>PrepInventory</title>
	
	<!-- PrepInventory CSS -->
	<link href="../dist/css/PrepInventory.css" rel="stylesheet">

	<!-- Bootstrap Core CSS -->
	<link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- MetisMenu CSS -->
	<link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

	<!-- Timeline CSS -->
	<link href="../dist/css/timeline.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="../dist/css/sb-admin-2.css" rel="stylesheet">

	<!-- Morris Charts CSS -->
	<link href="../bower_components/morrisjs/morris.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>

	<div id="wrapper">

		<!-- Navigation -->
		<?PHP include('xinc.nav.php'); ?>

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Inventory - Shopping List</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->

					<div class="panel panel-default">
						<div class="panel-heading">
							Shopping List 
							<span class="pull-right text-muted"><?PHP
								if (!empty($_GET['view']) && $_GET['view'] == 'cat') { $vlink = "inventory_shopping.php?view=itm"; $vname = "Item"; }
								else { $vlink = "inventory_shopping.php?view=cat"; $vname = "Category"; }
								print 'Switch view to: <a href="'. $vlink .'">'. $vname .'</a>';
							?></span>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr><td width="75"></td><td></td><td></td></tr>
									</thead>
									<tbody>
										<?php 
											# Category view
											if (!empty($_GET['view']) && $_GET['view'] == 'cat') {
												# Load categories
												$t_cat_rows = '';
												$sql = "SELECT * FROM `inv_categories_1` ORDER BY `name` ASC";
												$result = mysqli_query($link, $sql);
												if (mysqli_num_rows($result) < '1') { print "No category has been added."; }
												else {
													while($row = mysqli_fetch_assoc($result)) {
														# Load sub-categories
														$t_cat = $row['id'];
														$t_cat_name = $row['name'];
														$t_cat_desc = $row['description'];
														$t_cat_icon = $row['icon'];
														
														$t_scat_rows = '';
														$sql2 = "SELECT * FROM `inv_categories_2` WHERE `parent`='$t_cat' ORDER BY `name` ASC";
														$result2 = mysqli_query($link, $sql2);
														if (mysqli_num_rows($result2) > '0') { 
															while($row2 = mysqli_fetch_assoc($result2)) {
																$t_scat = $row2['id'];
																$t_scat_name = $row2['name'];
																$t_scat_desc = $row2['description'];
																$t_scat_icon = $row2['icon'];
																
																# Load sub-cat items
																$t_tobuy = '0';
																$t_itm_rows = '';
																$sql3 = "SELECT * FROM `inv_items` WHERE `cat`='2-$t_scat' ORDER BY `name` ASC";
																$result3 = mysqli_query($link, $sql3);
																if (mysqli_num_rows($result3) > '0') {
																	while($row3 = mysqli_fetch_assoc($result3)) {
																		$t_itm_id = $row3['id'];
																		$t_itm_name = $row3['name'];
																		$t_itm_desc = $row3['description'];
																		$t_itm_icon = $row3['icon'];
																		$t_itm_unit = $row3['unit'];
																		$t_itm_ttb = '0';
																		
																		# Retrieve unit value
																		$sql4 = "SELECT * FROM `inv_units` WHERE `id`='$t_itm_unit'";
																		$result4 = mysqli_query($link, $sql4);
																		if (mysqli_num_rows($result4) > 0) { 
																			while($row4 = mysqli_fetch_assoc($result4)) {
																				$t_itm_uname = $row4['name'];
																			}
																		}
																		
																		# Retrieve inventory values
																		$sql4 = "SELECT * FROM `inventory` WHERE `item`='$t_itm_id'";
																		$result4 = mysqli_query($link, $sql4);
																		if (mysqli_num_rows($result4) > 0) { 
																			while($row4 = mysqli_fetch_assoc($result4)) {
																				$t_itm_loc = $row4['location'];
																				$t_itm_qty = $row4['qty'];
																				$t_itm_qtymax = $row4['qty_max'];
																				$t_itm_tobuy = $t_itm_qtymax - $t_itm_qty;
																				if ($t_itm_tobuy < 1) { $t_itm_tobuy = '0'; }
																				$t_itm_ttb = $t_itm_ttb + $t_itm_tobuy;
																				
																				if ($t_itm_tobuy > 0) {
																					### Item location tree
																				}
																			}
																		}
																		$t_itm_rows .= '<tr><td>&emsp;&emsp;<img src="'. $t_itm_icon .'"  class="icon"></td><td>'. $t_itm_name .'<br>'. $t_itm_desc .'</td><td class="text-right"><strong>Buy: '. $t_itm_ttb .' '. $t_itm_uname .'</strong></td></tr>';
																	}
																	if (!empty($t_itm_ttb) && $t_itm_ttb > 0) {
																		$t_scat_rows .= '<tr><td>&emsp;<img src="'. $t_scat_icon .'" class="icon"></td><td>'. $t_scat_name .'<br>'. $t_scat_desc .'</td><td class="text-right"></td></tr>';
																		$t_scat_rows .= $t_itm_rows;
																	}
																}
															}
														}
														# Load Cat items
														$t_tobuy = '0';
														$t_itm_rows = '';
														$sql3 = "SELECT * FROM `inv_items` WHERE `cat`='1-$t_cat' ORDER BY `name` ASC";
														$result3 = mysqli_query($link, $sql3);
														if (mysqli_num_rows($result3) > '0') {
															while($row3 = mysqli_fetch_assoc($result3)) {
																$t_itm_id = $row3['id'];
																$t_itm_name = $row3['name'];
																$t_itm_desc = $row3['description'];
																$t_itm_icon = $row3['icon'];
																$t_itm_unit = $row3['unit'];
																$t_itm_ttb = '0';
																
																# Retrieve unit value
																$sql4 = "SELECT * FROM `inv_units` WHERE `id`='$t_itm_unit'";
																$result4 = mysqli_query($link, $sql4);
																if (mysqli_num_rows($result4) > 0) { 
																	while($row4 = mysqli_fetch_assoc($result4)) {
																		$t_itm_uname = $row4['name'];
																	}
																}
																
																# Retrieve inventory values
																$sql4 = "SELECT * FROM `inventory` WHERE `item`='$t_itm_id'";
																$result4 = mysqli_query($link, $sql4);
																if (mysqli_num_rows($result4) > 0) { 
																	while($row4 = mysqli_fetch_assoc($result4)) {
																		$t_itm_loc = $row4['location'];
																		$t_itm_qty = $row4['qty'];
																		$t_itm_qtymax = $row4['qty_max'];
																		$t_itm_tobuy = $t_itm_qtymax - $t_itm_qty;
																		if ($t_itm_tobuy < 1) { $t_itm_tobuy = '0'; }
																		$t_itm_ttb = $t_itm_ttb + $t_itm_tobuy;
																		
																		if ($t_itm_tobuy > 0) {
																			### item location tree
																		}
																	}
																}
																$t_itm_rows .= '<tr><td>&emsp;<img src="'. $t_itm_icon .'"  class="icon"></td><td>'. $t_itm_name .'<br>'. $t_itm_desc .'</td><td class="text-right"><strong>Buy: '. $t_itm_ttb .' '. $t_itm_uname .'</strong></td></tr>';
															}
														}
														
														# Display
														if (!empty($t_scat_rows) || !empty($t_itm_rows)) { print '<tr><td><img src="'. $t_cat_icon .'" class="icon"></td><td>'. $t_cat_name .'<br>'. $t_cat_desc .'</td><td class="text-right"></td></tr>'; }
														print $t_scat_rows;
														print $t_itm_rows;
													}
												}
												
											}
											
											# Item view
											else {
												$sql = "SELECT * FROM `inv_items` ORDER BY `name` ASC";
												$result = mysqli_query($link, $sql);
												if (mysqli_num_rows($result) < 1) { print "No items has been added."; }
												else {
													while($row = mysqli_fetch_assoc($result)) {
														# Set Qty to buy to 0 and set loc empty.
														$qty_tobuy = '0';
														
														# Seek for every location said item exists.
														#  If an item is needed, add qty to the total var and make a line in the loc var.
														$iid = $row['id'];
														$t_disploc = '';
														$sql2 = "SELECT * FROM `inventory` WHERE item = '$iid'";
														$result2 = mysqli_query($link, $sql2);
														if (mysqli_num_rows($result2) > 0) { 
															while($row2 = mysqli_fetch_assoc($result2)) {
																$t_tobuy = $row2['qty_max'] - $row2['qty'];
																$t_loc = $row2['location'];
																if ($t_tobuy > 0) {
																	$qty_tobuy = $qty_tobuy + $t_tobuy;
																	
																	# Get location tree
																	$t_loc_lev = explode("-", $t_loc)[0];
																	$t_loc_id = explode("-", $t_loc)[1];
																	$t_loc_id3 = ''; $t_loc_id2 = ''; $t_loc_id1 = '';  
																	
																	# Get location lvl 4
																	if ($t_loc_lev == '4') {
																		$sql3 = "SELECT * FROM `inv_locations_4` WHERE id = '$t_loc_id'";
																		$result3 = mysqli_query($link, $sql3);
																		if (mysqli_num_rows($result3) > 0) {
																			while($row3 = mysqli_fetch_assoc($result3)) {
																				$t_loc_name4 = $row3['name'];
																				$t_loc_id3 = $row3['parent'];
																			}
																		}
																	}
																	
																	# Get location lvl 3
																	if ($t_loc_lev == '3' || !empty($t_loc_id3)) {
																		if (!empty($t_loc_id3)) { $temp_id = $t_loc_id3; }
																		else { $temp_id = $t_loc_id; }
																		$sql3 = "SELECT * FROM `inv_locations_3` WHERE id = '$temp_id'";
																		$result3 = mysqli_query($link, $sql3);
																		if (mysqli_num_rows($result3) > 0) {
																			while($row3 = mysqli_fetch_assoc($result3)) {
																				$t_loc_name3 = $row3['name'];
																				$t_loc_id2 = $row3['parent'];
																			}
																		}
																	}
																	
																	# Get location lvl 2
																	if ($t_loc_lev == '2' || !empty($t_loc_id2)) {
																		if (!empty($t_loc_id2)) { $temp_id = $t_loc_id2; }
																		else { $temp_id = $t_loc_id; }
																		$sql3 = "SELECT * FROM `inv_locations_2` WHERE id = '$temp_id'";
																		$result3 = mysqli_query($link, $sql3);
																		if (mysqli_num_rows($result3) > 0) {
																			while($row3 = mysqli_fetch_assoc($result3)) {
																				$t_loc_name2 = $row3['name'];
																				$t_loc_id1 = $row3['parent'];
																			}
																		}
																	}
																	
																	# Get location lvl 1
																	if ($t_loc_lev == '1' || !empty($t_loc_id1)) {
																		if (!empty($t_loc_id1)) { $temp_id = $t_loc_id1; }
																		else { $temp_id = $t_loc_id; }
																		$sql3 = "SELECT * FROM `inv_locations_1` WHERE id = '$temp_id'";
																		$result3 = mysqli_query($link, $sql3);
																		if (mysqli_num_rows($result3) > 0) {
																			while($row3 = mysqli_fetch_assoc($result3)) {
																				$t_loc_name1 = $row3['name'];
																			}
																		}
																	}
																	
																	# Make the Display
																	$t_disploc .= '<br>';
																	$t_disploc .= "$t_loc_name1";
																	if (!empty($t_loc_name2)) { $t_disploc .= " > $t_loc_name2"; }
																	if (!empty($t_loc_name3)) { $t_disploc .= " > $t_loc_name3"; }
																	if (!empty($t_loc_name4)) { $t_disploc .= " > $t_loc_name4"; }
																	$t_disploc .= ' : '. $row2['qty'] .' / '. $row2['qty_max'] .' = <strong>'. $t_tobuy .'</strong>';
																}
															}
														}
														
														# Retrieve unit to display
														$iunit = $row['unit'];
														$sql2 = "SELECT * FROM `inv_units` WHERE id = '$iunit'";
														$result2 = mysqli_query($link, $sql2);
														if (mysqli_num_rows($result2) < 1) { print ""; }
														else {
															while($row2 = mysqli_fetch_assoc($result2)) {
																$unit = $row2['name'];
															}
														}
													
														if ($qty_tobuy > 0) { 
															print '<tr><td><img src="'. $row['icon'] .'"></td><td>'. $row['name'] .'<br>'. $row['description'] .'<br>'. $t_disploc .'</td><td class="text-right"><h2 style="margin: 0;">Buy: '. $qty_tobuy .' '. $unit .'</h2></td></tr>';
														}
													}
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->

					<!-- /CODE -->
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

	<!-- jQuery -->
	<script src="../bower_components/jquery/dist/jquery.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- Metis Menu Plugin JavaScript -->
	<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

	<!-- Custom Theme JavaScript -->
	<script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>

<?PHP include('xinc.foot.php'); ?>