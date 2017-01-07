<?PHP
	session_start();
	include('xinc.config.php');

	# Check if session exists.
	#  If Session (UID) is not existing, redirect to login.php
	#  Else show the page IF user is admin.
	if (empty($_SESSION['username'])) {
		header('location:login.php');
		die();
	}
	if ($_SESSION['role'] !== 'admin') {
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
					<h1 class="page-header">Inventory Log</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<table width="100%"><tr><th>Date & Time</th><th>Item</th><th>Location</th><th>Quantity</th><th>User</th></tr>
					<?PHP
						$sql = "SELECT * FROM `inv_log` ORDER BY `id` DESC LIMIT 0,99";
						$result = mysqli_query($link, $sql);
						if (mysqli_num_rows($result) < 1) { print '<tr><td colspan="5">No LOG available.</td></tr>'; }
						else {
							while($row = mysqli_fetch_assoc($result)) {
								$datetime = $row['datetime'];
								$user = $row['user'];
								$location = $row['location'];
								$qty = $row['qty'];
								$action = $row['action'];
								$item_id = $row['item'];
								$log_id = $row['id'];

								# Load item name
								$sql2 = "SELECT * FROM `inv_items` WHERE `id` = '$item_id'";
								$result2 = mysqli_query($link, $sql2);
								if (mysqli_num_rows($result2) < 1) { $item_name = "Unknown Item"; }
								else {
									while($row2 = mysqli_fetch_assoc($result2)) {
										$item_name = $row2['name'];
									}
								}

								# Load location
								if (!empty($location)) { 
									$loc_lev = explode("-", $location)[0];
									$loc_id = explode("-", $location)[1];
									$sql2 = "SELECT * FROM `inv_locations_$loc_lev` WHERE `id` = '$loc_id'";
									$result2 = mysqli_query($link, $sql2);
									if (mysqli_num_rows($result2) > 0) {
										while($row2 = mysqli_fetch_assoc($result2)) {
											$loc_name = $row2['name'];
										}
									}
								}
								else { $loc_name = "Unknown Location"; }

								print '<tr><td>'.$datetime.'</td><td>'.$item_name.'</td><td>'.$loc_name.'</td><td>'.$action.' '.$qty.'</td><td>'.$conf['user'][$user]['name'].'</td></tr>';
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