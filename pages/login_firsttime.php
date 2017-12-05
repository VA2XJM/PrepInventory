<?PHP
	session_start();
	include('xinc.config.php');

	# If username and password is provided, identity is validated.
	if (!empty($_POST['submit'])) {
		$userid = $_SESSION['id'];
		$sql = "SELECT * FROM `users` WHERE `uid` = '$userid'";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) < 1) { $error = 1; }
		else {
			while($row = mysqli_fetch_assoc($result)) {
				if ($row['password'] !== $_POST['temppass']) { $error = 2; }
				else {
					$np1 = $_POST['np1'];
					$np2 = $_POST['np2'];
					if (empty($np1) || empty($np2)) { $error = 3; }
					elseif ($np1 !== $np2) { $error = 4; }
					else {
						$lastact = time(); $lastactnote = 'First Login';
						$sql = "UPDATE `users` SET `password` = '$np1', `last_activity` = '$lastact', `last_activity_note` = '$lastactnote' WHERE `uid`='$userid'";
						$result = mysqli_query($link, $sql);
						if ($result) { $_SESSION['notice'] = '<div class="panel panel-green"><div class="panel-heading">Welcome! Your new password is set.</div></div>'; header('location:index.php'); }
						else { $error = 5; }
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

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

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

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Change your password</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="login_firsttime.php" method="post">
                            <fieldset>
				<?PHP
					if (!empty($error) && $error == '1') { print '<div class="form-group"><span style="color: #FF0000;">Wrong username or password.</span></div>'; }
					elseif (!empty($error) && $error == '2') { print '<div class="form-group"><span style="color: #FF0000;">Temporary password do not match.</span></div>'; }
					elseif (!empty($error) && $error == '3') { print '<div class="form-group"><span style="color: #FF0000;">New password is missing.</span></div>'; }
					elseif (!empty($error) && $error == '4') { print '<div class="form-group"><span style="color: #FF0000;">New password do not match.</span></div>'; }
					elseif (!empty($error) && $error == '5') { print '<div class="form-group"><span style="color: #FF0000;">Error.</span></div>'; }
				?>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Temporary Password" name="temppass" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="New Password" name="np1" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Repeat New Password" name="np2" type="password" value="">
                                </div>
                                <input type="submit" name="submit" value="Continue">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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