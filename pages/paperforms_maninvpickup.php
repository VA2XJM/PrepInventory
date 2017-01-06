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
					<h1 class="page-header">Paper Forms</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->

					<div class="panel panel-default">
						<div class="panel-heading">
							Manual Inventory Pick List
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							Select a location below to generate a manual pick list for this location.
							<hr>
							<?php
								$sql = "SELECT * FROM `inv_locations_1` ORDER BY `name` ASC";
								$result = mysqli_query($link, $sql);
								if (mysqli_num_rows($result) < 1) { print "No root locations has been configured yet."; }
								else {
									while($row = mysqli_fetch_assoc($result)) {
										print '<img src="'. $row['icon'] .'" class="icon"> ' . $row["name"] . ' <a href="paperforms_maninvpickup_gen.php?loc=1-'. $row['id'] .'" target="_BLANK"><i class="fa fa-print fa-fw"></i></a><br>';
										$sql2 = "SELECT * FROM `inv_locations_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
										$result2 = mysqli_query($link, $sql2);
										if (mysqli_num_rows($result2) > 0) {
											while($row2 = mysqli_fetch_assoc($result2)) {
												print '&emsp; <img src="'. $row2['icon'] .'" class="icon"> ' . $row2["name"] . ' <a href="paperforms_maninvpickup_gen.php?loc=2-'. $row2['id'] .'" target="_BLANK"><i class="fa fa-print fa-fw"></i></a><br>';
												$sql3 = "SELECT * FROM `inv_locations_3` WHERE parent = '". $row2['id'] ."' ORDER BY `name` ASC";
												$result3 = mysqli_query($link, $sql3);
												if (mysqli_num_rows($result3) > 0) {
													while($row3 = mysqli_fetch_assoc($result3)) {
														print '&emsp;&emsp; <img src="'. $row3['icon'] .'" class="icon"> ' . $row3["name"] . ' <a href="paperforms_maninvpickup_gen.php?loc=3-'. $row3['id'] .'" target="_BLANK"><i class="fa fa-print fa-fw"></i></a><br>';
														$sql4 = "SELECT * FROM `inv_locations_4` WHERE parent = '". $row3['id'] ."' ORDER BY `name` ASC";
														$result4 = mysqli_query($link, $sql4);
														if (mysqli_num_rows($result4) > 0) {
															while($row4 = mysqli_fetch_assoc($result4)) {
																print '&emsp;&emsp;&emsp; <img src="'. $row4['icon'] .'" class="icon"> ' . $row4["name"] . ' <a href="paperforms_maninvpickup_gen.php?loc=4-'. $row4['id'] .'" target="_BLANK"><i class="fa fa-print fa-fw"></i></a><br>';
															}
														}
													}
												}
											}
										}
										print '<br>';
									}
								}
							?>
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