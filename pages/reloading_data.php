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
	## Removing data
	#
	if (!empty($_GET['delete'])) {
		if (is_numeric($_GET['delete'])) {
			$id = $_GET['delete'];
			$sql = "DELETE FROM `reloading_data` WHERE `id`='$id'";
			$result = mysqli_query($link, $sql);

			$sql = "DELETE FROM `reloading_batches` WHERE `caliber`='$id'";
			$xresult = mysqli_query($link, $sql);

			if ($result) {	$notice = '<div class="panel panel-success"><div class="panel-heading">Data deleted.</div></div>'; }
			else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Error while deleting data.</div></div>'; }
		}
		else { $notice = '<div class="panel panel-danger"><div class="panel-heading">Numerical value required.</div></div>'; }
	}


	#
	## Load and Display Data
	#
	print '<h1>Data</h1> Here is the actual list of data recorded. You can add new ones or delete existing ones. <b>Deleting data will also delete associated batches.</b>';

	if (isset($notice)) { print $notice; }

	print '<table border="1" width="80%"><tr><th>Caliber</th><th>Bullet</th><th>Powder</th><th>Primer</th><th width="10px">&nbsp;</th></tr>';
	$sql = "SELECT *, t1.id AS `dataid` FROM reloading_data t1 LEFT JOIN reloading_calibers t2 ON t1.caliber = t2.id ORDER BY t2.name";
	$result = mysqli_query($link, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			# Bullet Name
			$bullet_id = $row['bullet'];
			$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$bullet_id' ORDER BY t2.name";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $bullet_name = $rowx['name']; } }

			# Powder Name
			$powder_id = $row['powder'];
			$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$powder_id' ORDER BY t2.name";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $powder_name = $rowx['name']; } }

			# Primer Name
			$primer_id = $row['primer'];
			$sqlx = "SELECT *, t1.id AS `invid` FROM inventory t1 LEFT JOIN inv_items t2 ON t1.item = t2.id WHERE t1.id = '$primer_id' ORDER BY t2.name";
			$resultx = mysqli_query($link, $sqlx);
			if (mysqli_num_rows($resultx) > 0) { while($rowx = mysqli_fetch_assoc($resultx)) { $primer_name = $rowx['name']; } }

			print '<tr><td>'.$row['name'].'</td><td>'.$bullet_name.'</td><td>'.$powder_name.'</td><td>'.$primer_name.'</td><td><a href="?page=data_edit&id='. $row['dataid'] .'"><i class="fa fa-wrench fa-fw"></i></a> <a href="?page=data&delete='. $row['dataid'] .'" onclick="return confirm(\'Are you certain you wish to delete this reloading data?\')"><i class="fa fa-minus-square fa-fw"></i></a></td></tr>';
		}
	}
	print '</table>';

	print '<p><a href="?page=data_add">Add a data</a></p>';
?>