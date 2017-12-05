<?PHP
	session_start();
	include('xinc.config.php');

	# Check if session exists.
	#  If Session (UID) is not existing, redirect to login.php
	#  Else show the page.
	if (empty($_SESSION['username']) || $_SESSION['role'] != 'admin') {
		header('location:login.php');
		die();
	}
	
	# Actions
	if (!empty($_GET['action'])) {
		$userid = $_GET['id'];
		$action = $_GET['action'];
		
		# Toggle user status (Enabled/Disabled)
		if ($action == 'toggle') {
			$value = $_GET['value'];
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "UPDATE `users` SET `disabled` = '$value' WHERE `uid`='$userid'";
			$result = mysqli_query($link, $sql);
			if ($result) { $notice = '<div class="panel panel-green"><div class="panel-heading">User has been updated.</div></div>'; }
			else { $notice = '<div class="panel panel-red"><div class="panel-heading">Error: Couldn\'t update the user.</div></div>'; }
		}

		# Delete user
		if ($action == 'delete') {
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "UPDATE `users` SET `deleted` = '1' WHERE `uid`='$userid'";
			$result = mysqli_query($link, $sql);
			if ($result) { $notice = '<div class="panel panel-green"><div class="panel-heading">User has been deleted.</div></div>'; }
			else { $notice = '<div class="panel panel-red"><div class="panel-heading">Error: Couldn\'t delete the user.</div></div>'; }
		}
	}
	
	# Adding new user.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['role'])) { $notice = '<div class="panel panel-red"><div class="panel-heading">Error: Username, E-Mail & Role are mandatory. Username must be unique.</div></div>'; }
		else {
			$p_username = $_POST['username'];
			$p_email = $_POST['email'];
			$p_role = $_POST['role'];
			$g_password = substr(md5(mt_rand()), 0, 8);
			
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "INSERT INTO `users` (`uid`, `username`, `password`, `email`, `role`, `last_activity`) VALUES (NULL, '$p_username', '$g_password', '$p_email', '$p_role', '0')";
			$result = mysqli_query($link, $sql);
			if ($result) { $notice = '<div class="panel panel-green"><div class="panel-heading">User has been added with temporary password: '.$g_password.'.</div></div>'; }
			else { $notice = '<div class="panel panel-red"><div class="panel-heading">Error: Couldn\'t add new user. Check that the username do not already exists.</div></div>'; }
		}
	}
	
	### Load table data
	$sql = "SELECT * FROM `users` WHERE `deleted`='0' ORDER BY `username` ASC";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) < 1) { $data = ''; }
	else {
		$data = '';
		while($row = mysqli_fetch_assoc($result)) {
			$uid = $row['uid'];
			$username = $row['username'];
			$email = $row['email'];
			$namef = $row['name_first'];
			$namel = $row['name_last'];
			$location = $row['location'];
			$rating = $row['rating'];
			$role = $row['role'];
			$disabled = $row['disabled'];
			$lastact = $row['last_activity'];
			$lastactnote = $row['last_activity_note'];
			
			# Toggle Enabled/Disabled
			if  ($disabled == '2') { $toggle = ''; }
			elseif ($disabled == '1') { $toggle = '<a href="admin_users.php?id='.$uid.'&action=toggle&value=0" title="Enable this user"><i class="fa fa-toggle-off fa-2x"></i></a>'; }
			else { $toggle = '<a href="admin_users.php?id='.$uid.'&action=toggle&value=1" title="Disable this user"><i class="fa fa-toggle-on fa-2x"></i></a>'; }

			# Delete a user
			if  ($disabled == '2') { $delete = ''; }
			else { $delete = '<a href="admin_users.php?id='.$uid.'&action=delete" title="Delete this user" onclick="return confirm(\'Are you certain you wish to delete this user?\')"><i class="fa fa-times fa-2x"></i></a>'; }
				
			$data .= '<tr><td>'.$username.'</td> <td>'.$namef.' '.$namel.'</td> <td>'.$email.'</td> <td>'.$location.'</td> <td>'.$rating.'</td> <td>'.$role.'</td> <td>'.date("Y-m-d H:i:s", $lastact).'<br><small>'.$lastactnote.'</small></td> <td>'.$toggle.' &nbsp; '.$delete.'</td></tr>';
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

	<title>PrepInventory - Admin - Users Management</title>
	
	<!-- PrepInventory CSS -->
	<link href="../dist/css/PrepInventory.css" rel="stylesheet">

	<!-- Bootstrap Core CSS -->
	<link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- MetisMenu CSS -->
	<link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

	<!-- Timeline CSS -->
	<link href="../dist/css/timeline.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="../dist/css/sb-admin.css" rel="stylesheet">
	<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
	<link href="../dist/css/dataTables.bootstrap4.css" rel="stylesheet">

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
					<h1 class="page-header">Admin - Users Management</h1>
					<?PHP if (!empty($notice)) { print $notice; } ?>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->
					<div class="row">
						<div class="col-lg-12 col-md-6">
							<div class="table-responsive">
								<table class="table table-bordered" width="100%" id="dataTable" cellspacing="0">
									<thead>
										<tr>
											<th>Username</th>
											<th>Name</th>
											<th>E-Mail</th>
											<th>Location</th>
											<th>Rating</th>
											<th>Role</th>
											<th>Last Activity</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Username</th>
											<th>Name</th>
											<th>E-Mail</th>
											<th>Location</th>
											<th>Rating</th>
											<th>Role</th>
											<th>Last Activity</th>
											<th>Actions</th>
										</tr>
									</tfoot>
									<tbody>
										<?PHP print $data; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-lg-12 col-md-6">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<a id="new"></a>New User
								</div>
								<div class="panel-body">
									<form role="form" method="post">
										<div class="form-group">
											<label>New User Details.</label>
											<p class="form-inline"><input class="form-control" placeholder="Username" name="username" value="<?PHP if (!empty($_POST['username'])) { print $_POST['username']; } ?>"> &nbsp; <input class="form-control" placeholder="E-Mail" name="email" value="<?PHP if (!empty($_POST['email'])) { print $_POST['email']; } ?>"> &nbsp; <select class="form-control" name="role"><option value="user" <?PHP if (!empty($p_role) && $p_role == 'user') { print 'selected="selected"'; } ?>>User</option><option value="admin" <?PHP if (!empty($p_role) && $p_role == 'admin') { print 'selected="selected"'; } ?>>Admin</option></select></p>
										</div>
										<button type="submit" class="btn btn-default">Submit</button>
									</form>
								</div>
							</div>
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
	<script src="../bower_components/datatables-responsive/media/js/dataTables.responssive.js"></script>
	<script src="../bower_components/jquery-easing/jquery.easing.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- Metis Menu Plugin JavaScript -->
	<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

	<!-- Custom Theme JavaScript -->
	<script src="../dist/js/sb-admin.min.js"></script>
	<script src="../dist/js/sb-admin-2.js"></script>
	<script src="../dist/js/dataTables.bootstrap4.js"></script>
	<script src="../dist/js/jquery.dataTables.js"></script>

</body>

</html>

<?PHP include('xinc.foot.php'); ?>