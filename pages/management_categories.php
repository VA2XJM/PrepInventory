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
					<h1 class="page-header">Management - Categories</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->

					<div class="panel panel-default">
						<div class="panel-heading">
							Categories listing.
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
						<a href="management_categories_add.php?parent=0" title="Add new category" alt="Add new category">Add a new category</a>
						<hr>
							<?php
								$sql = "SELECT * FROM `inv_categories_1` ORDER BY `name` ASC";
								$result = mysqli_query($link, $sql);
								if (mysqli_num_rows($result) < 1) { print "No categories has been configured yet."; }
								else {
									while($row = mysqli_fetch_assoc($result)) {
										print '<img src="'. $row['icon'] .'" class="icon"> ' . $row["name"] . ' <a href="management_categories_add.php?parent=1-'. $row['id'] .'" title="Add subcategory" alt="Add subcategory"><i class="fa fa-plus-square fa-fw"></i></a> <a href="management_categories_edit.php?id=1-'. $row['id'] .'"><i class="fa fa-pencil-square fa-fw"></i></a><br>';
										
										$sql2 = "SELECT * FROM `inv_categories_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
										$result2 = mysqli_query($link, $sql2);
										if (mysqli_num_rows($result2) > 0) {
											while($row2 = mysqli_fetch_assoc($result2)) {
												print '&emsp; <img src="'. $row2['icon'] .'" class="icon"> ' . $row2["name"] . ' <a href="management_categories_edit.php?id=2-'. $row2['id'] .'"><i class="fa fa-pencil-square fa-fw"></i></a><br>';
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