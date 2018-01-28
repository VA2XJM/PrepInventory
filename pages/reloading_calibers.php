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
	## Adding a new caliber
	#
	if (!empty($_POST['name'])) {
		$name = $_POST['name'];
		$sql = "INSERT INTO `reloading_calibers` (`name`) VALUES ('$name')";
		$result = mysqli_query($link, $sql);
		if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">New caliber added.</div></div>'; }
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while adding new caliber.</div></div>'; }
	}

	#
	## Removing caliber
	#
	if (!empty($_GET['delete'])) {
		if (is_numeric($_GET['delete'])) {
			$id = $_GET['delete'];
			$sql = "DELETE FROM `reloading_calibers` WHERE `id`='$id'";
			$result = mysqli_query($link, $sql);

			$sql = "DELETE FROM `reloading_data` WHERE `caliber`='$id'";
			$xresult = mysqli_query($link, $sql);
			$sql = "DELETE FROM `reloading_shell_lots` WHERE `caliber`='$id'";
			$xresult = mysqli_query($link, $sql);
			$sql = "DELETE FROM `reloading_data` WHERE `caliber`='$id'";
			$xresult = mysqli_query($link, $sql);
			$sql = "DELETE FROM `reloading_batches` WHERE `caliber`='$id'";
			$xresult = mysqli_query($link, $sql);

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Caliber deleted.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while deleting caliber.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Calibers
	#
	print '<h1>Calibers</h1> Here is the actual list of calibers recorded. You can add new ones or delete existing ones. <b>Deleting a caliber will also delete data associated with it.</b>';

	if (isset($notice)) { print $notice; }

	print '<table border="1">';
	$sql = "SELECT * FROM reloading_calibers ORDER BY name ASC";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			print '<tr><td>'.$row['name'].' <a href="?page=calibers&delete='. $row['id'] .'" onclick="return confirm(\'Are you certain you wish to delete this caliber?\')"><i class="fa fa-minus-square fa-fw"></i></a></td></tr>';
		}
	}
	print '</table>';

	print '<h3>Add a caliber</h3>';
?>
	<div class="panel panel-default">
		<div class="panel-heading">
			Add a new caliber
		</div>
		<div class="panel-body">
			<form role="form" method="post">
				<div class="form-group">
					<input class="form-control" placeholder="Caliber" name="name">
					<p class="help-block">Name is mandatory. A-Z, a-z, 0-9, - and space.</p>
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>

