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
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr><td width="50"></td><td></td><td></td></tr>
									</thead>
									<tbody>
										<?php 
											# Category view
											if (!empty($_GET['view']) && $_GET['view'] == 'cat') {
											
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
														$sql2 = "SELECT * FROM `inventory` WHERE item = '$iid'";
														$result2 = mysqli_query($link, $sql2);
														if (mysqli_num_rows($result2) < 1) { print ""; }
														else {
															while($row2 = mysqli_fetch_assoc($result2)) {
																$t_tobuy = $row2['qty_max'] - $row2['qty'];
																if ($t_tobuy > 0) {
																	$qty_tobuy = $qty_tobuy + $t_tobuy;
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
															print '<tr><td><img src="'. $row['icon'] .'"></td><td>'. $row['name'] .'<br>'. $row['description'] .'</td><td class="text-right"><h2 style="margin: 0;">Buy: '. $qty_tobuy .' '. $unit .'</h2></td></tr>';
															### Add locations
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