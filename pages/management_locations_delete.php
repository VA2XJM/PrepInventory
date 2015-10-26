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
	
	$panel_type = 'panel-red';
	# Loading existing data
	if (!empty($_GET['id'])) {
		$id_lev = explode("-", $_GET['id'])[0];
		$id_id = explode("-", $_GET['id'])[1];
		
		if (is_numeric($id_lev) && is_numeric($id_id)) {
			if ($id_lev == '1') { $table = 'inv_locations_1'; }
			elseif ($id_lev == '2') { $table = 'inv_locations_2'; }
			elseif ($id_lev == '3') { $table = 'inv_locations_3'; }
			elseif ($id_lev == '4') { $table = 'inv_locations_4'; }
			else { $table = 'inv_locations_1'; }
			
			$sql = "SELECT * FROM `$table` WHERE `id`='$id_id'";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) < 1) { $panel_type = 'panel-danger'; $panel_notice = "ERROR: wrong ID."; }
			else {
				while($row = mysqli_fetch_assoc($result)) {
					$name = $row['name'];
					$desc = $row['description'];
					$icon = $row['icon'];
				}
			}
		}
		else { $panel_type = 'panel-danger'; $panel_notice = "ERROR: wrong ID."; }
	}

	# Delete
	if (!empty($_GET['action']) && $_GET['action'] == 'delete') {
		$id_lev = explode("-", $_GET['id'])[0];
		$id_id = explode("-", $_GET['id'])[1];
		
		if (is_numeric($id_lev) && is_numeric($id_id)) {
			if ($id_lev == '1') { $table = 'inv_locations_1'; }
			elseif ($id_lev == '2') { $table = 'inv_locations_2'; }
			elseif ($id_lev == '3') { $table = 'inv_locations_3'; }
			elseif ($id_lev == '4') { $table = 'inv_locations_4'; }
			else { $table = 'inv_locations_1'; }
			
			$sql = "DELETE FROM `$table` WHERE `id`='$id_id'";
			$result = mysqli_query($link, $sql);
			
			$sql = "SELECT `parent` FROM `inv_locations_2` WHERE `parent` NOT IN (SELECT `id` FROM `inv_locations_1`)";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) { 
				while($row = mysqli_fetch_assoc($result)) {
					$sql2 = "DELETE FROM `inv_locations_2` WHERE `parent`='". $row['parent'] ."'";
					$result2 = mysqli_query($link, $sql2);
				}
			}
			
			$sql = "SELECT `parent` FROM `inv_locations_3` WHERE `parent` NOT IN (SELECT `id` FROM `inv_locations_2`)";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) { 
				while($row = mysqli_fetch_assoc($result)) {
					$sql2 = "DELETE FROM `inv_locations_3` WHERE `parent`='". $row['parent'] ."'";
					$result2 = mysqli_query($link, $sql2);
				}
			}
			
			$sql = "SELECT `parent` FROM `inv_locations_4` WHERE `parent` NOT IN (SELECT `id` FROM `inv_locations_3`)";
			$result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) > 0) { 
				while($row = mysqli_fetch_assoc($result)) {
					$sql2 = "DELETE FROM `inv_locations_4` WHERE `parent`='". $row['parent'] ."'";
					$result2 = mysqli_query($link, $sql2);
				}
			}
			
			$panel_type = 'panel-success'; $panel_notice = "Location deleted.<br><a href=\"management_locations.php\" title=\"Return\" alt=\"Return\">Return to Locations</a>";
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
	
	<!-- FA IconPicker -->
	<link rel="stylesheet" href="../css/image-picker.css" type="text/css" />
	
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
					<h1 class="page-header">Management - Locations - Delete</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-4"></div>
				<div class="col-lg-4">
					<!-- CODE -->
					
					<div class="panel <?PHP print $panel_type; ?>">
					<div class="panel-heading">
						Delete location
					</div>
					<div class="panel-body">
						<?PHP if (!empty($panel_notice)) { print "<div>$panel_notice</div><br>"; } else { ?>
						<div align="center"> Are you sure you want to delete this location? </div>
						<?PHP if (!empty($icon)) { print '<img src="'. $icon .'" style="float: left;">'; } ?>
						<div style="padding: 5px;">
							<?PHP if (!empty($name) && !empty($desc)) { print "&emsp;<b>$name</b><br>&emsp;$desc<br>"; } ?>
						</div>
						<div align="center"><a href="management_locations_delete.php?id=<?PHP print $_REQUEST['id']; ?>&action=delete">Delete</a> &emsp; <a href="management_locations.php" title="Cancel" alt="Cancel">Cancel</a></div>
						<?PHP } ?>
					</div>
					</div>
					<!-- /CODE -->
				</div>
				<div class="col-lg-4"></div>
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
	
	<!-- FA IconPicker -->
	<script type="text/javascript" src="../js/image-picker.js" ></script>
	<script type="text/javascript" >
		$('.image-picker').imagePicker({
			selectorHeight: 100,
			selectorWidth: 1000,
			imageMaxHeight: 100,
			imageMaxWidth: 48
		});
	</script>
</body>

</html>

<?PHP include('xinc.foot.php'); ?>