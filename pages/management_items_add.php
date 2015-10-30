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
			$name = $_POST['name'];
			$icon = $_POST['icon'];
			$category = $_POST['category'];
			$unit = $_POST['unit'];
			if (!empty($_POST['description'])) { $desc = $_POST['description']; }
			else { $desc = ''; }
			
			# Execute MySQL. If there is not error show green panel and notification.
			# Else show red panel and error notification.
			$sql = "INSERT INTO `inv_items` (`name`, `description`, `keywords`, `icon`, `cat`, `unit`) VALUES ('$name', '$desc', '', '$icon', '$category', '$unit')";
			$result = mysqli_query($link, $sql);
			if ($result) {
				$panel_type = 'panel-success';
				$panel_notice = "Item has been added. <a href=\"management_items.php\" title=\"Return\" alt=\"Return\">Return to Items</a>";
			}
			else {
				$panel_type = 'panel-danger';
				$panel_notice = "Error: Can't add item to database.";
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
					<h1 class="page-header">Management - Items - Add</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- CODE -->
					
					<div class="panel <?PHP print $panel_type; ?>">
					<div class="panel-heading">
						Add a new item
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
								<label>Unit</label>
								<select class="form-control" name="unit">
									<?php
										$sql = "SELECT * FROM `inv_units` ORDER BY `name` ASC";
										$result = mysqli_query($link, $sql);
										if (mysqli_num_rows($result) < 1) { print ""; }
										else {
											while($row = mysqli_fetch_assoc($result)) {
												print '<option value="'. $row["id"] .'">' . $row["name"] . '</option>';
											}
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>Category</label>
								<select class="form-control" name="category">
									<?php
										$sql = "SELECT * FROM `inv_categories_1` ORDER BY `name` ASC";
										$result = mysqli_query($link, $sql);
										if (mysqli_num_rows($result) < 1) { print ""; }
										else {
											while($row = mysqli_fetch_assoc($result)) {
												print '<option value="1-'. $row["id"] .'">' . $row["name"] . '</option>';
												
												$sql2 = "SELECT * FROM `inv_categories_2` WHERE parent = '". $row['id'] ."' ORDER BY `name` ASC";
												$result2 = mysqli_query($link, $sql2);
												if (mysqli_num_rows($result2) > 0) {
													while($row2 = mysqli_fetch_assoc($result2)) {
														print '<option value="2-'. $row2["id"] .'"> - ' . $row2["name"] . '</option>';
													}
												}
											}
										}
									?>
								</select>
							</div>
							<div class="form-group">	
								<select class="image-picker" id="image-picker" name="icon">
									<option data-img-src="../icons/default-48.png" value="../icons/default-48.png">../icons/default-48.png</option>
									<?PHP getDirectory( "../icons" ); ?>
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