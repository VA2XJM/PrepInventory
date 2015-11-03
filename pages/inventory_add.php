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
	
	# Submission
	$panel_type = 'panel-default';
	# Is name empty? if not proceed, otherwise show red panel.
	if (empty($_POST['item']) && empty($_POST['location'])) { $panel_type = 'panel-default'; }
	elseif (!empty($_POST['item']) && empty($_POST['qty-max'])) { $panel_type = 'panel-danger'; $panel_notice = "ERROR: Quantities are mandatory."; }
	else {
		# if name is set and match 'A-Z, a-z, 0-9, - and space' proceed. Otherwise show red panel.
		if (!is_numeric($_POST['qty']) || !is_numeric($_POST['qty-max'])) { 
			$panel_type = 'panel-danger';
			$panel_notice = "Error: Quantitites must be numerical.";
		}
		else {
			$item = $_POST['item'];
			$location = $_POST['location'];
			if (empty($_POST['qty'])) { $_POST['qty'] = 0; }
			$qty = $_POST['qty'];
			$qtymax = $_POST['qty-max'];
			
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "INSERT INTO `inventory` (`item`, `location`, `qty`, `qty_max`) VALUES ('$item', '$location', '$qty', '$qtymax')";
			$result = mysqli_query($link, $sql);
			if ($result) {
				# Display green panel to user
				$panel_type = 'panel-success';
				$panel_notice = "Item has been added to inventory. <a href=\"inventory.php\" title=\"Return\" alt=\"Return\">Return to Inventory</a>";

				# Add a line to the inventory log
				$username = $_SESSION['username'];
				$sql = "INSERT INTO `inv_log` (`item`, `action`, `qty`, `user`) VALUES ('$item', '+', '$qty', '$username')";
				$result = mysqli_query($link, $sql);
			}
			else {
				$panel_type = 'panel-danger';
				$panel_notice = "Error: Can't add item to inventory.";
			}
		}
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
					<h1 class="page-header">Inventory - Add Item</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->

					<div class="panel <?PHP print $panel_type; ?>">
						<div class="panel-heading">
							Add an item to the inventory
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<!-- CODE -->
							
							<form role="form" method="post">
								<?PHP if (!empty($panel_notice)) { print "<div>$panel_notice</div><br>"; } ?>
								<div class="form-group">
									<label>Item</label>
									<select class="form-control" name="item">
										<?php
											$sql = "SELECT * FROM `inv_items` ORDER BY `name` ASC";
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
								<div class="form-group">
									<label>Location</label>
									<select class="form-control" name="location">
										<?php
											$sql = "SELECT * FROM `inv_locations_1` ORDER BY `name` ASC";
											$result = mysqli_query($link, $sql);
											if (mysqli_num_rows($result) < 1) { print ""; }
											else {
												while($row = mysqli_fetch_assoc($result)) {
													print '<option value="1-'. $row['id'] .'">'. $row['name'] .'</option>';
													$sql2 = "SELECT * FROM `inv_locations_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
													$result2 = mysqli_query($link, $sql2);
													if (mysqli_num_rows($result2) > 0) {
														while($row2 = mysqli_fetch_assoc($result2)) {
															print '<option value="2-'. $row2['id'] .'">->'. $row2['name'] .'</option>';
															$sql3 = "SELECT * FROM `inv_locations_3` WHERE parent = '". $row2['id'] ."' ORDER BY `name` ASC";
															$result3 = mysqli_query($link, $sql3);
															if (mysqli_num_rows($result3) > 0) {
																while($row3 = mysqli_fetch_assoc($result3)) {
																	print '<option value="3-'. $row3['id'] .'">-->'. $row3['name'] .'</option>';
																	$sql4 = "SELECT * FROM `inv_locations_4` WHERE parent = '". $row3['id'] ."' ORDER BY `name` ASC";
																	$result4 = mysqli_query($link, $sql4);
																	if (mysqli_num_rows($result4) > 0) {
																		while($row4 = mysqli_fetch_assoc($result4)) {
																			print '<option value="4-'. $row4['id'] .'">--->'. $row4['name'] .'</option>';
																		}
																	}
																}
															}
														}
													}
												}
											}
										?>
									</select>
								</div>
								<div class="form-inline">
									<label>Quantity available &emsp;&emsp;&emsp;&nbsp;  Quantity maximum</label><br>
									<input class="form-control" placeholder="0" name="qty" value="0"> &nbsp; <input class="form-control" placeholder="1" value="1" name="qty-max">
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
							
							<!-- /CODE -->
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