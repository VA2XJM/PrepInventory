<?PHP
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	# Check if session exists.
	#  If Session (UID) is not existing, redirect to login.php
	#  Else show the page.
	if (empty($_SESSION['username'])) {
		header('location:login.php');
		die();
	}

	#
	## Adding a new lot
	#
	if (!empty($_POST['name'])) {
		$name = $_POST['name'];
		$sql = "INSERT INTO `reloading_shell_lots` (`name`) VALUES ('$name')";
		$result = mysqli_query($link, $sql);
		if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">New shell lot added.</div></div>'; }
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while adding new shell lot.</div></div>'; }
	}

	#
	## Removing caliber
	#
	if (!empty($_GET['delete'])) {
		if (is_numeric($_GET['delete'])) {
			$id = $_GET['delete'];
			$sql = "DELETE FROM `reloading_shell_lots` WHERE `id`='$id'";
			$result = mysqli_query($link, $sql);

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Shell lot deleted.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while deleting shell lot.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Calibers
	#
	print '<h1>Shell lots</h1> Here is the actual list of shell lots on record. You can add new ones or delete existing ones. <b>Deleting a shell lot will not remove any other data.</b>';

	if (isset($notice)) { print $notice; }

	print '<table border="1" width="80%"><tr><th>Caliber (Qty)</th><th>Lot #</th><th>Reload / Reload Max</th><th>Trim / Trim Max</th><th>Details</th><th width="10px">&nbsp;</th></tr>';
	$sql = "SELECT *, t1.id AS `lotid`, t2.name AS `calibername` FROM reloading_shell_lots t1 LEFT JOIN reloading_calibers t2 ON t1.caliber = t2.id WHERE t1.discarded = '0' ORDER BY t2.name";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			print '<tr><td>'.$row['calibername'].' ('.$row['qty'].')</td><td>'.$row['lotid'].'</td><td>'.$row['reload'].' / '.$row['reload_max'].'</td><td>'.$row['trim'].' / '.$row['trim_max'].'</td><td>'.$row['details'].'</td><td><a href="?page=shelllots&delete='. $row['lotid'] .'"><i class="fa fa-minus-square fa-fw"></i></a></td></tr>';
		}
	}
	print '</table>';

	print '<h3>Create a new shell lot</h3>';
?>
	<div class="panel panel-default">
		<div class="panel-heading">
			Create a new shell lot
		</div>
		<div class="panel-body">
			<form role="form" method="post">
				- Caliber Selection
				- Qty
				- Max Trim
				- Max Reload
				- Details
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>

