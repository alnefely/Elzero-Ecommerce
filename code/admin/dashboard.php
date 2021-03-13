<?php 

	ob_start(); // Output Buffering Start

	session_start();

	if ( isset($_SESSION['Username']) ) {

		$pageTitle = 'Dashboard';

		include 'init.php';

		/* Start Dashboard Page */

		$numUsers = 5; // Number Of Latest Users

		$latestUsers = getLatest('*', 'users', 'UserID', $numUsers); // Latest Users Array

		$numItems = 5; // Number Of Latest Items

		$latestItems = getLatest('*', 'items', 'Item_ID', $numItems); // Latest Items Array

		$numComments = 5; // Number Of Latest Comments

		$latestComments = getLatest('*', 'items', 'Item_ID', $numComments); // Latest Items Array

		?>
		<div class="home-stats">	
			<div class="container text-center">
				<h1>Dashboard Welcome Admin <?php echo '[ '.$_SESSION['Username'] . ' ]'; ?></h1>
				<div class="row">

					<div class="col-md-3 edit">
						<div class="stat st-members">
							<i class="fa fa-users"></i>
							<div class="info">
								Total Members
								<span>
									<a href="members.php"><?php echo countItems('UserID', 'users') ?></a>
								</span>
							</div>
						</div>
					</div>

					<div class="col-md-3 edit">
						<div class="stat st-panding">
							<i class="fa fa-user-plus"></i>
							<div class="info">
								Panding Members
								<span>
									<a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus', 'users', 0); ?></a>
								</span>
							</div>
						</div>
					</div>

					<div class="col-md-3 edit">
						<div class="stat st-items">
							<i class="fa fa-tag"></i>
							<div class="info">
								Total Items
								<span>
									<a href="items.php"><?php echo countItems('Item_ID', 'items') ?></a>
								</span>

							</div>
						</div>
					</div>

					<div class="col-md-3 edit">
						<div class="stat st-comments">
							<i class="fa fa-comments"></i>
							<div class="info">
								Total Comments
								<span>
									<a href="comments.php"><?php echo countItems('c_id', 'comments') ?></a>
								</span>
							</div>
						</div>
					</div>

				</div> <!-- End Row Div1 -->
			</div> <!-- End Container Div1 -->
		</div>

		<div class="latest">
			<div class="container">
				<div class="row">

					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-users"></i> 
								Latest <?php echo '<b>' . $numUsers . '</b>'; ?> Register Users
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div> <!-- End Panel Heading -->
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
									<?php 
										if(! empty($latestUsers)){
											foreach ( $latestUsers as $user ) {
												echo '<li>';
													echo $user['Username'];
													echo '<a href="members.php?do=Edit&userid='. $user['UserID'] .'">';
														echo "<span class='btn btn-success pull-right'>";
															echo "<i class='fa fa-edit'></i> Edit";

															if ($user['RegStatus'] == 0) {
																echo "<a href='members.php?do=Activate&userid=". $user['UserID'] ."' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Activate</a>";
															}

														echo '</span>';
													echo '</a>';
												echo '</li>';
											}
										} else {
											echo '<b>There\'s No Users To Show</b>';
										}
									?>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-tag"></i> 
								Latest <?php echo '<b>' . $numItems . '</b>'; ?> Items
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div> <!-- End Panel Heading -->
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
									<?php 
										if(! empty($latestItems)){
											foreach ( $latestItems as $item ) {
												echo '<li>';
													echo $item['Name'];
													echo '<a href="items.php?do=Edit&itemid='. $item['Item_ID'] .'">';
														echo "<span class='btn btn-success pull-right'>";
															echo "<i class='fa fa-edit'></i> Edit";

															if ($item['Approve'] == 0) {
																echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] ."' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Approve</a>";
															}

														echo '</span>';
													echo '</a>';
												echo '</li>';
											}
										} else {
											echo '<b>There\'s No Items To Show</b>';
										}
									?>
								</ul>
							</div>
						</div>
					</div>
					

				</div> <!-- End Row Div2 -->

				<!-- Start Latest Comments -->
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-comments-o"></i> 
								Latest <?php echo '<b>' . $numComments . '</b>' ; ?> Comments
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div> <!-- End Panel Heading -->
							<div class="panel-body">
								<?php
									// Select All Users Except Admin
									$stmt = $con->prepare("SELECT 
																comments.*, users.Username AS Member
															FROM 
																comments
															INNER JOIN
																users
															ON 
																users.UserID = comments.user_id
															ORDER BY 
																c_id DESC
															LIMIT $numComments");
									$stmt->execute(); 
									$comments = $stmt->fetchAll();

									if(! empty($comments)){
										foreach ($comments as $comment) { 
											echo '<div class="comment-box">';
												echo '<span class="member-n">';
													echo '<a href="members.php?do=Edit&userid='. $comment['user_id'] .'">'. $comment['Member'] .'</a>';
												echo '</span>';
												echo '<p class="member-c">' . $comment['comment'] . '</p>';
											echo '</div>';
										}
									} else {
										echo '<b>There\'s No Comments To Show</b>';
									}
								?>
							</div>
						</div>
					</div>					

				</div> <!-- End Row Div2 -->
				<!-- Start Latest Comments -->

			</div> <!-- End Container Div2 -->
		</div>

		<?php
		/* End Dashboard Page */

		include $tpl . 'footer.php';

	} else {
		header('Location: index.php');
		exit();
	}

	ob_end_flush();

?>