<?PHP
	session_start();
	include('xinc.config.php');

	# If session var UID contain a value, we assume the user wished to log out.
	if (!empty($_SESSION['uid'])) {
		$_SESSION['uid'] = '';
		$_SESSION['username'] = '';
		session_destroy();
		$zlastact = "Logged out!";
	}

	# If username and password is provided, identity is validated.
	if (!empty($_POST['username']) && !empty($_POST['password'])) {
		$username = $_POST['username'];
		$sql = "SELECT * FROM `users` WHERE `username` = '$username'";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) < 1) { $error = 1; }
		else {
			while($row = mysqli_fetch_assoc($result)) {
				if ($row['password'] !== $_POST['password']) { $error = 1; }
				elseif ($row['disabled'] == '1') { $error = 2; }
				else {
					$_SESSION['username'] = $_POST['username'];
					$_SESSION['role'] = $row['role'];
					$_SESSION['name'] = $row['name_first'] .' '. $row['name_last'];
					$_SESSION['uid'] = $row['uid'];
					if ($row['last_activity'] > 0) { header('location:index.php'); }
					else { header('location:login_firsttime.php'); }
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
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="login.php" method="post">
                            <fieldset>
				<?PHP
					if (!empty($error) && $error == '1') { print '<div class="form-group"><span style="color: #FF0000;">Wrong username or password.</span></div>'; }
					elseif (!empty($error) && $error == '2') { print '<div class="form-group"><span style="color: #FF0000;">Your account has been suspended.</span></div>'; }
				?>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <input type="submit" value="Login">
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