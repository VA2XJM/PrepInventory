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
	
	# Check if an item ID is given.
	if (!empty($_GET['id'])) { $get_id = $_GET['id']; }
	else { $get_id = '0'; }
	
	# Check if action has been made. if so execute prior to retrieve data from DB.
	if (isset( $_POST['more']) || isset( $_POST['less'])) {
		$sql = "SELECT * FROM `inventory` WHERE `id` = '$get_id'";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) < 1) { header('location:inventory.php'); die(); }
		else {
			while($row = mysqli_fetch_assoc($result)) {
				$t_qty = $row['qty'];
				$t_item = $row['item'];
			}
			
			# Set qty to 0 if nothing submited.
			if (empty($_POST['qty'])) { $_POST['qty'] = '0'; }
			
			# If we add more:
			if (isset( $_POST['more'])) { 
				$t_new = $t_qty + $_POST['qty']; 
				$inv_act = '+';  $inv_qty = $_POST['qty'];
			}
			
			# If we get some out
			elseif (isset( $_POST['less'])) { 
				$t_new = $t_qty - $_POST['qty'];
				$inv_act = '-'; $inv_qty = $_POST['qty'];
				if ($t_new < '1') { $t_new = '0'; $inv_qty = $t_qty; } 
			}
			
			# Else remain to equity.
			else { $t_new = $t_qty; $inv_act = '='; }
			
			$sql = "UPDATE `inventory` SET `qty`='$t_new' WHERE `id`='$get_id'";
			$result = mysqli_query($link, $sql);
			
			# Add a line to the inventory log
			$username = $_SESSION['username'];
			$sql = "INSERT INTO `inv_log` (`item`, `action`, `qty`, `user`) VALUES ('$t_item', '$inv_act', '$inv_qty', '$username')";
			$result = mysqli_query($link, $sql);
		}
	}
	# Load item details
	$sql = "SELECT * FROM `inventory` WHERE `id` = '$get_id'";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) < 1) { header('location:inventory.php'); die(); }
	else {
		while($row = mysqli_fetch_assoc($result)) {
			$inv_id = $row['id'];
			$inv_item = $row['item'];
			$inv_location = $row['location'];
			$inv_qty = $row['qty'];
			$inv_qtymax = $row['qty_max'];

			# Load location
			$loc_lev = explode("-", $inv_location)[0];
			$loc_id = explode("-", $inv_location)[1];
			$sql2 = "SELECT * FROM `inv_locations_$loc_lev` WHERE `id` = '$loc_id'";
			$result2 = mysqli_query($link, $sql2);
			if (mysqli_num_rows($result2) > 0) {
				while($row2 = mysqli_fetch_assoc($result2)) {
					$loc_name = $row2['name'];
				}
			}
			
			# Load more details
			$sql2 = "SELECT * FROM `inv_items` WHERE `id` = '$inv_item'";
			$result2 = mysqli_query($link, $sql2);
			if (mysqli_num_rows($result2) > 0) {
				while($row2 = mysqli_fetch_assoc($result2)) {
					$item_name = $row2['name'];
					$item_desc = $row2['description'];
					$item_keywords = $row2['keywords'];
					$item_icon = $row2['icon'];
					$item_cat = $row2['cat'];
					$item_unit = $row2['unit'];
					
					$cat_lev = explode("-", $item_cat)[0];
					$cat_id = explode("-", $item_cat)[1];
					$table = 'inv_categories_'. $cat_lev;
					$sql3 = "SELECT * FROM `$table` WHERE `id` = '$cat_id'";
					$result3 = mysqli_query($link, $sql3);
					if (mysqli_num_rows($result3) > 0) {
						while($row3 = mysqli_fetch_assoc($result3)) {
							$cat_name = $row3['name'];
							$cat_desc = $row3['description'];
							$cat_icon = $row3['icon'];
							
							$sql4 = "SELECT * FROM `inv_units` WHERE `id` = '$item_unit'";
							$result4 = mysqli_query($link, $sql4);
							if (mysqli_num_rows($result4) > 0) {
								while($row4 = mysqli_fetch_assoc($result4)) {
									$unit_name = $row4['name'];
								}
							}
						}
					}
				}
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
					<h1 class="page-header">Inventory - Details</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->

					<span class="pull-right text-muted"><a href="inventory.php">Back to Inventory</a> &nbsp;</span>
					<div class="panel panel-default">
						<div class="panel-heading">
							<?PHP print '<img src="'. $item_icon .'" style="float: left;"> &emsp;' . $item_name . '<br>&emsp;' . $item_desc .''; ?>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<?PHP
								$percent = $inv_qty / $inv_qtymax * 100;
								$pcact = '';
								if ($percent < '1') { $pclevel = 'progress-bar-danger'; $pcact = ' progress-striped active'; }
								elseif ($percent < '30') { $pclevel = 'progress-bar-danger'; }
								elseif ($percent < '60') { $pclevel = 'progress-bar-warning'; }
								elseif ($percent < '90') { $pclevel = 'progress-bar-info'; }
								elseif ($percent < '101') { $pclevel = 'progress-bar-success'; }
								else { $pclevel = 'progress-bar-success'; $pcact = ' progress-striped active'; }
								print '	<p><strong>Stock:</strong> '. $inv_qty .' '. $unit_name .'&emsp;<strong>Maximum:</strong> '. $inv_qtymax .' '. $unit_name .' <span class="pull-right text-muted">Stock Level: '. floor($percent) .'%</span></p>';
								if ($percent < '1') { $percent = '100'; }
								print '	<div class="progress'. $pcact .'"><div class="progress-bar '. $pclevel .'" role="progressbar" aria-valuenow="'. $percent .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $percent .'%"></div></div>';
							?>
							<div><strong>Category:</strong> <img src="<?PHP print $cat_icon; ?>" class="icon"> <?PHP print $cat_name; ?></div>
							<div><strong>Location:</strong> <?PHP print $loc_name; ?></div>
							<hr>
							<div class="form-inline">
								<form role="form" method="post">
									<label>Quantity</label><br>
									<input class="form-control" placeholder="0" name="qty" type="number" autocomplete="off" autofocus> <button type="submit" class="btn btn-default" name="less">-</button> <button type="submit" class="btn btn-default" name="more">+</button>
									<p class="help-block">Enter a quantity and press '-' or ENTER to remove it from inventory or '+' to add it to inventory.</p>
								</form>
							</div>
						</div>
						<div class="panel-footer" align="center">
							<a href="inventory_edit.php?id=<?PHP print $get_id; ?>">Edit this item</a> - <a href="inventory_delete.php?id=<?PHP print $get_id; ?>">Delete this item</a>
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