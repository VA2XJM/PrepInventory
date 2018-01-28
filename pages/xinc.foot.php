<?PHP
	# Update last activity
	if (!empty($_SESSION['uid'])) {
		if (empty($zlastact)) { $zlastact = $_SERVER['REQUEST_URI']; }
		$zuid = $_SESSION['uid'];
		$znow = time();
		$sql = "UPDATE `users` SET `last_activity` = '$znow', `last_activity_note` = '$zlastact' WHERE `uid`='$zuid'";
		$result = mysqli_query($link, $sql);
	}

	mysqli_close($link);
?>