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
	
	# Icon List
	function getDirectory( $path = '.', $level = 0 ){
		$ignore = array( 'cgi-bin', '.', '..' );
		// Directories to ignore when listing output. Many hosts
		// will deny PHP access to the cgi-bin.

		$dh = @opendir( $path );
		// Open the directory to the handle $dh
		
		while( false !== ( $file = readdir( $dh ) ) ){
		// Loop through the directory
		
			if( !in_array( $file, $ignore ) ){
			// Check that this file is not to be ignored
				
				if( is_dir( "$path/$file" ) ){
				// Its a directory, so we need to keep reading down...

					getDirectory( "$path/$file", ($level+1) );
					// Re-call this same function but on a new directory.
					// this is what makes function recursive.
				
				} else {
					$tpath = "http://";
					$tpath .= $_SERVER['SERVER_NAME'];
					$tpath .= dirname(dirname($_SERVER['REQUEST_URI']));
					$tpath .= str_replace("..", "", $path);
					$tpath .= '/'. $file;
					
					$xpath = str_replace("../icons/", "", $path);
					$xpath .= '/'. $file;
					echo '<option data-img-src="'. $tpath .'" value="'. $xpath .'">'. $file .'</option>';
				}
			
			}
		
		}
		closedir( $dh );
		// Close the directory handle
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
					<h1 class="page-header">Management - Locations - Add</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->
					
					<div class="panel panel-default">
						<div class="panel-heading">
							Add a new location
						</div>
						<div class="panel-body">
							<form role="form">
								<div class="form-group">
									<input class="form-control" placeholder="Name" name="name">
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Description" name="description">
								</div>
								<div class="form-group">	
									<select class="image-picker" >
										<?PHP
											### DEBUG image picker...
											getDirectory( "../icons" ); print "\n";
										?>
									</select>
								</div>
								<button type="submit" class="btn btn-default">Submit Button</button>
							</form>
				        </div>

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

	<!-- Morris Charts JavaScript -->
	<script src="../bower_components/raphael/raphael-min.js"></script>
	<script src="../bower_components/morrisjs/morris.min.js"></script>
	<script src="../js/morris-data.js"></script>

	<!-- Custom Theme JavaScript -->
	<script src="../dist/js/sb-admin-2.js"></script>
	
	<!-- FA IconPicker -->
	<script type="text/javascript" src="../js/image-picker.js" ></script>
	<script type="text/javascript" >
		$('.image-picker').imagePicker({
			selectorHeight: 100,
			selectorWidth: 1000,
			imageMaxHeight: 48,
			imageMaxWidth: 48
		});
	</script>
</body>

</html>

<?PHP include('xinc.foot.php'); ?>