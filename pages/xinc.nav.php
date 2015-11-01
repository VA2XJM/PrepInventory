		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">PrepInventory <?PHP print $g_version; ?></a>
			</div>
			<!-- /.navbar-header -->

			<ul class="nav navbar-top-links navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
					</a>

					<ul class="dropdown-menu dropdown-tasks">
						<?PHP
							# Load item details
							$sql = "SELECT * FROM `inventory` ORDER BY (qty/qty_max) ASC LIMIT 5";
							$result = mysqli_query($link, $sql);
							if (mysqli_num_rows($result) > 0) {
								while($row = mysqli_fetch_assoc($result)) {
									$inv_id = $row['id'];
									$inv_item = $row['item'];
									$inv_location = $row['location'];
									$inv_qty = $row['qty'];
									$inv_qtymax = $row['qty_max'];
									$inv_percent = $inv_qty / $inv_qtymax * 100;
									# Load more details
									$sqlz = "SELECT * FROM `inv_items` WHERE `id` = '$inv_item'";
									$resultz = mysqli_query($link, $sqlz);
									if (mysqli_num_rows($resultz) > 0) {
										while($rowz = mysqli_fetch_assoc($resultz)) {
											$item_name = $rowz['name'];
											$item_desc = $rowz['description'];
											$item_keywords = $rowz['keywords'];
											$item_icon = $rowz['icon'];
											$item_cat = $rowz['cat'];
											$item_unit = $rowz['unit'];
											
											$pcact = '';
											if ($inv_percent < '1') { $pclevel = 'progress-bar-danger'; $pcact = ' progress-striped active'; }
											elseif ($inv_percent < '30') { $pclevel = 'progress-bar-danger'; }
											elseif ($inv_percent < '60') { $pclevel = 'progress-bar-warning'; }
											elseif ($inv_percent < '90') { $pclevel = 'progress-bar-info'; }
											elseif ($inv_percent < '101') { $pclevel = 'progress-bar-success'; }
											else { $pclevel = 'progress-bar-success'; $pcact = ' progress-striped active'; }
											print '<li><a href="inventory_details.php?id='. $inv_id .'"><div><p><strong>'. $item_name .'</strong><span class="pull-right text-muted">Stock Level: '. floor($inv_percent) .'%</span>';
											if ($inv_percent < '1') { $inv_percent = '100'; }
											print '	<div class="progress'. $pcact .'"><div class="progress-bar '. $pclevel .'" role="progressbar" aria-valuenow="'. $inv_percent .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $inv_percent .'%"></div></div></div></a></li><li class="divider"></li>';
										}
									}
								}
							}
						?>

						<li>
							<a class="text-center" href="inventory.php">
								<strong>See All Items</strong>
								<i class="fa fa-angle-right"></i>
							</a>
						</li>
					</ul>
					<!-- /.dropdown-tasks -->
				</li>
				<!-- /.dropdown -->
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<!-- fa-bell-o: No notifications -=- fa-bell: new notification -->
						<i class="fa fa-bell-o fa-fw"></i>  <i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-alerts">
						<li>
							<a href="#">
								<div>
									<i class="fa fa-comment fa-fw"></i> New Comment
									<span class="pull-right text-muted small">4 minutes ago</span>
								</div>
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#">
								<div>
									<i class="fa fa-twitter fa-fw"></i> 3 New Followers
									<span class="pull-right text-muted small">12 minutes ago</span>
								</div>
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#">
								<div>
									<i class="fa fa-envelope fa-fw"></i> Message Sent
									<span class="pull-right text-muted small">4 minutes ago</span>
								</div>
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#">
								<div>
									<i class="fa fa-tasks fa-fw"></i> New Task
									<span class="pull-right text-muted small">4 minutes ago</span>
								</div>
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="#">
								<div>
									<i class="fa fa-upload fa-fw"></i> Server Rebooted
									<span class="pull-right text-muted small">4 minutes ago</span>
								</div>
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a class="text-center" href="alerts.php">
								<strong>See All Alerts</strong>
								<i class="fa fa-angle-right"></i>
							</a>
						</li>
					</ul>
					<!-- /.dropdown-alerts -->
				</li>
				<!-- /.dropdown -->
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="#"><i class="fa fa-user fa-fw"></i> <?PHP print $_SESSION['username']; ?></a>
						</li>
						<li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
						</li>
						<li class="divider"></li>
						<li><a href="login.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
						</li>
					</ul>
					<!-- /.dropdown-user -->
				</li>
				<!-- /.dropdown -->
			</ul>
			<!-- /.navbar-top-links -->

			<div class="navbar-default sidebar" role="navigation">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">
						<li class="sidebar-search">
							<div class="input-group custom-search-form">
								<input type="text" class="form-control" placeholder="Search...">
								<span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<i class="fa fa-search"></i>
								</button>
							</span>
							</div>
							<!-- /input-group -->
						</li>
						<li>
							<a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
						</li>
						<li>
							<a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Inventory<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a href="inventory.php">Overview</a>
								</li>
								<li>
									<a href="inventory_shopping.php">Shopping List</a>
								</li>
								<li>
									<a href="inventory_add.php">Add item in inventory</a>
								</li>
							</ul>
							<!-- /.nav-second-level -->
						</li>
						<li>
							<a href="#"><i class="fa fa-wrench fa-fw"></i> Management<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a href="management_categories.php">Categories</a>
								</li>
								<li>
									<a href="management_locations.php">Locations</a>
								</li>
								<li>
									<a href="management_items.php">Items</a>
								</li>
								<li>
									<a href="management_units.php">Units</a>
								</li>
							</ul>
							<!-- /.nav-second-level -->
						</li>
						<li>
							<a href="#"><i class="fa fa-files-o fa-fw"></i> Paper Forms<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a href="paperforms_maninvpickup.php">Manual Inventory Pick Up</a>
								</li>
							</ul>
							<!-- /.nav-second-level -->
						</li>
						<li>
							<a href="#"><i class="fa fa-support fa-fw"></i> Help & About<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li>
									<a href="https://github.com/xJMV/PrepInventory" target="_BLANK">GitHub</a>
								</li>
								<li>
									<a href="https://github.com/xJMV/PrepInventory/wiki" target="_BLANK">Help</a>
								</li>
								<li>
									<a href="https://github.com/xJMV/PrepInventory/issues" target="_BLANK">Bugs & Issues</a>
								</li>
							</ul>
							<!-- /.nav-second-level -->
						</li>
					</ul>
				</div>
				<!-- /.sidebar-collapse -->
			</div>
			<!-- /.navbar-static-side -->
		</nav>