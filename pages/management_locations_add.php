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
	
	# Submission
	$panel_type = 'panel-default';
	# Is name empty? if not proceed, otherwise show red panel.
	if (empty($_POST['name']) && empty($_POST['icon'])) { $panel_type = 'panel-default'; }
	elseif (!empty($_POST['icon']) && empty($_POST['name'])) { $panel_type = 'panel-danger'; $panel_notice = "ERROR: Name is mandatory."; }
	else {
		# if name is set and match 'A-Z, a-z, 0-9, - and space' proceed. Otherwise show red panel.
		if (!preg_match('!^[\w -]*$!', $_POST['name'])) { 
			$panel_type = 'panel-danger';
			$panel_notice = "Error: Name contain illegal character(s).";
		}
		else {
			# Check for parent level and ID. Check sanity.
			if ($_GET['parent'] == '0') { $_GET['parent'] = '0-0'; }
			$parent_lev = explode("-", $_GET['parent'])[0];
			$parent_id = explode("-", $_GET['parent'])[1];

			if ($parent_lev == '0') { $table = 'inv_locations_1'; $parent_id = '0'; }
			elseif ($parent_lev == '1') { $table = 'inv_locations_2'; }
			elseif ($parent_lev == '2') { $table = 'inv_locations_3'; }
			elseif ($parent_lev == '3') { $table = 'inv_locations_4'; }
			else { $table = 'inv_locations_1'; }
			
			$name = $_POST['name'];
			$icon = $_POST['icon'];
			if (!empty($_POST['description'])) { $desc = $_POST['description']; }
			else { $desc = ''; }
			
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "INSERT INTO `$table` (`parent`, `name`, `description`, `icon`) VALUES ('$parent_id', '$name', '$desc', '$icon')";
			$result = mysqli_query($link, $sql);
			if ($result) {
				$panel_type = 'panel-success';
				$panel_notice = "Location has been added. <a href=\"management_locations.php\" title=\"Return\" alt=\"Return\">Return to Locations</a>";
			}
			else {
				$panel_type = 'panel-danger';
				$panel_notice = "Error: Can't add location to database.";
			}
		}
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
					$tpath = $path;
					$tpath .= '/'. $file;
					print '<option data-img-src="'. $tpath .'" value="'. $tpath .'">'. $file .'</option>';
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
					
					<div class="panel <?PHP print $panel_type; ?>">
					<div class="panel-heading">
						Add a new location
					</div>
					<div class="panel-body">
						<form role="form" method="post">
							<?PHP if (!empty($panel_notice)) { print "<div>$panel_notice</div><br>"; } ?>
							<div class="form-group">
								<input class="form-control" placeholder="Name" name="name">
								<p class="help-block">Name is mandatory. A-Z, a-z, 0-9, - and space.</p>
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="Description" name="description">
							</div>
							<div class="form-group">
								<input class="form-control" type="hidden" placeholder="" name="parent" value="<?PHP if (!empty($_GET['parent'])) { print $_GET['parent']; } elseif (!empty($_POST['parent'])) { print $_POST['parent']; } else { print '0'; } ?>" disabled>
							</div>
							<div class="form-group">	
								<select class="image-picker" id="image-picker" name="icon">
									<option data-img-src="../icons/default-48.png" value="../icons/default-48.png">../icons/default-48.png</option>
									<?PHP getDirectory( "../icons/locations" ); ?>
								</select>
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div>
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