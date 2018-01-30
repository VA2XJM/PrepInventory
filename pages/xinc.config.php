<?PHP
	# PrepInventory Configuration File
	# 
	# Change settings below only if you know what you are doing.
	
	# MySQL - Set to use your MySQL database
	$conf['mysql']['address'] = '127.0.0.1';
	$conf['mysql']['username'] = 'root';
	$conf['mysql']['password'] = 'vertrigo';
	$conf['mysql']['database'] = 'prepinv';

	# Multi-user database
	# # If you wish to have multiple instances using the same system, you can activate
	# #  this feature. It is useful to run multiple single users inventory into one install.
	# # All data are stored into separate database that you need to create. Some features
	# #  will be disabled (ex: Admin User Management). You will also need to edit users manually

	# # THIS FEATURE IS MADE FOR SPECIAL PURPOSE ONLY. I WILL NOT HELP YOU WITH IT.
	# # NO DATA LOSS SHOULD HAPPEN IF YOU DO NOT DESTROY YOU ORIGINAL DATABASE.

	$conf['multiuser']['status'] = '0'; # Set to 0 for regular setup or to 1 for multi-user.
	$conf['multiuser']['prefix'] = 'pi_'; # Set a prefix to distinguish all database.
		# -> now create a new database for each user. Ex: pi_username
		# -> edit username in the database and password.

	if ($conf['multiuser']['status'] == '1' ) {
		if (isset($_POST['username']) || isset($_SESSION['username'])) {
			if (isset($_POST['username'])) { $dbuser = $_POST['username']; }
			if (isset($_SESSION['username'])) { $dbuser = $_SESSION['username']; }

			$conf['mysql']['database'] = $conf['multiuser']['prefix'] . $dbuser;
		}
	}


	####################################################################################

	# Do not change settings below, this could break your setup and result in data loss. 
	# Do not change settings below, this could break your setup and result in data loss. 
	# Do not change settings below, this could break your setup and result in data loss. 
	# Do not change settings below, this could break your setup and result in data loss. 
	# Do not change settings below, this could break your setup and result in data loss. 
	$g_version = '0.4.0';

	####################################################################################

	# MySQL connection
	$link = mysqli_connect($conf['mysql']['address'], $conf['mysql']['username'], $conf['mysql']['password'], $conf['mysql']['database']);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
?>