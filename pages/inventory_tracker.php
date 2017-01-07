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
					<h1 class="page-header">Consumption Tracker</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<table width="100%"><tr><th>Item</th><th>Quantity Per Week</th><th>Quantity Per Month</th><th>Quantity Per Year</th></tr>
					<?PHP
						$sql = "SELECT * FROM inv_items ORDER BY `name`";
						$result = mysqli_query($link, $sql);
						if (mysqli_num_rows($result) > 0) {
							while($row = mysqli_fetch_assoc($result)) {
								$item = $row['name'];
								$itemid = $row['id'];

								# Retrieve unit of measurement
								$unitid = $row['unit'];
								$sql2 = "SELECT * FROM inv_units WHERE id = $unitid";
								$result2 = mysqli_query($link, $sql2);
								if (mysqli_num_rows($result2) > 0) {
									while($row2 = mysqli_fetch_assoc($result2)) { $unit = $row2['name']; }
								}
								else { $unit = 'Unknown Unit'; }

								# Retrieve First consumption date.
								$sql2 = "SELECT * FROM inv_log WHERE item = $itemid AND action = '-' ORDER BY `id` ASC LIMIT 0,1";
								$result2 = mysqli_query($link, $sql2);
								if (mysqli_num_rows($result2) > 0) {
									while($row2 = mysqli_fetch_assoc($result2)) { 
										$firstdate = strtotime($row2['datetime']);
										$now = time();
										$datediff = $now - $firstdate;

										$weeks = floor($datediff / (60 * 60 * 24 * 7));
										$months = floor($datediff / (60 * 60 * 24 * 30));
										$years = floor($datediff / (60 * 60 * 24 * 365));
									}
								}
								else { $firstdate = 'x'; }
								
								# Check if items have been used before otherwise nothing is shown. Then retrieve the quantity and count.
								if ($firstdate !== 'x') {
									$sql2 = "SELECT SUM(qty) AS qty FROM inv_log WHERE item = $itemid AND action = '-'";
									$result2 = mysqli_query($link, $sql2);
									if (mysqli_num_rows($result2) > 0) {
										while($row2 = mysqli_fetch_assoc($result2)) { 
											$qty = $row2['qty'];

											$qtyweeks = round($qty / $weeks);
											$qtymonths = round($qty / $months);
											$qtyyears = round($qty / $years);
										}
									}
									else { $firstdate = 'x'; } 
									print "<tr><td>$item ($unit)</td><td>$qtyweeks</td><td>$qtymonths</td><td>$qtyyears</td></tr>"; 
								}
							}
						}
					?>
					</table>
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