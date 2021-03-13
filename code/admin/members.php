<?php

	ob_start(); // Output Buffering Start

	/*
	================================================
	== Manage Members Page 
	== You Can Add | Edit | Delete Members From Here
	================================================
	*/

	session_start();

	$pageTitle = 'Members';

	if ( isset($_SESSION['Username']) ) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

/**************************** Start Manage Members Page *****************************************/
		
		if ( $do == 'Manage' ) { // Manage Members Page 

			$query = '';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

				$query = 'AND RegStatus = 0';

			}

			// Select All Users Except Admin
			$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
			$stmt->execute(); // Execute The Statement

			// Assign To Variable
			$rows = $stmt->fetchAll();

			if(! empty($rows)){ 
		?>

				<h1 class="text-center">Manage Members</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table manage-members text-center table table-bordered">

							<tr>
								<td>#ID</td>
								<td>Image</td>
								<td>Username</td>
								<td>Email</td>
								<td>Full Name</td>
								<td>Registered Date</td>
								<td>Control</td>
							</tr>

							<?php 

								foreach ( $rows as $row ) {

									echo "<tr>";

										echo "<td>" . $row['UserID'] . "</td>";
										echo "<td>";
										if (empty($row['user_profile'])){
											echo "<img src='uploads/images/def.png'/>";
										} else {
											echo "<img src='uploads/images/" . $row['user_profile'] . "'/>";
										}
										echo "</td>";
										echo "<td>" . $row['Username'] . "</td>";
										echo "<td>" . $row['Email'] . "</td>";
										echo "<td>" . $row['FullName'] . "</td>";
										echo "<td>" . $row['Date'] . "</td>";
										echo "<td>
												<a href='members.php?do=Edit&userid=". $row['UserID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
												<a href='members.php?do=Delete&userid=". $row['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
											
												if ($row['RegStatus'] == 0) {

													echo "<a href='members.php?do=Activate&userid=". $row['UserID'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";

												}

											echo "</td>";

									echo "</tr>";

								} // End For Loop							

							?>


						</table>
					</div><!-- End Div Table -->
				
					<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Members</a>
				</div>

			<?php } else {
				echo '<div class="container">';
					echo '<div class="nice-message"><b>There\'s No Members To Show</b></div>';
					echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Members</a>';
				echo '</div>';
				} ?>

<!--***************************** Start Add Page  ****************************************-->
		<?php } elseif ($do == 'Add') {  // Start Page Add ?>
		
			<h1 class="text-center">Add New Members</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

						<!-- Start Username Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Username</label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login Into Shop"/>
							</div>
						</div>
						<!-- End Username Field -->

						<!-- Start Password Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Password</label>
							<div class="col-sm-10 col-md-8"">
								<input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Password Must Be Hard & Complex"/>
								<i class="show-pass fa fa-eye fa-2x"></i>
							</div>
						</div>
						<!-- End Password Field -->

						<!-- Start Email Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Email</label>
							<div class="col-sm-10 col-md-8"">
								<input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid"/>
							</div>
						</div>
						<!-- End Email Field -->

						<!-- Start Full Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Full Name</label>
							<div class="col-sm-10 col-md-8"">
								<input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page"/>
							</div>
						</div>
						<!-- End Full Name Field -->

						<!-- Start Profile Image Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">User Image</label>
							<div class="col-sm-10 col-md-8"">
								<input type="file" name="user-profile" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Profile Image Field -->

						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->

					</form>
				</div> <!-- End Container Div -->

<!--***************************** Start Insert Page ****************************************-->
		<?php  

		} elseif ($do == 'Insert') { // Insert Page
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1 class='text-center'>Insert Members</h1>";
				echo "<div class='container'>";

				// Upload Variables
				$imageName = $_FILES['user-profile']['name'];
				$imageSize = $_FILES['user-profile']['size'];
				$imageTmp  = $_FILES['user-profile']['tmp_name'];
				$imageType = $_FILES['user-profile']['type'];

				// List Of Allowed File Typed To Upload
				$imageAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Image Extension
				@$imageExtension = strtolower(end(explode('.', $imageName)));

				// Get variables From the Form
				$user 	= $_POST['username'];
				$pass 	= $_POST['password'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				$hashPass = sha1($_POST['password']);
				
				// Validate The Form
				$formErrors = array();

				if ( strlen($user) < 3 ) {
					$formErrors[] = 'Username Cannot Be Less Than <strong> 3 Characters</strong>';
				}

				if ( strlen($user) > 25 ) {
					$formErrors[] = 'Username Cannot Be More Than <strong> 25 Characters</strong>';
				}

				if ( empty($user) ) {
					$formErrors[] = 'Username Cannot Be <strong> Empty </strong>';
				}

				if ( empty($pass) ) {
					$formErrors[] = 'Password Cannot Be <strong> Empty </strong>';
				} 

				if ( empty($name) ) {
					$formErrors[] = 'Full Name Cannot Be <strong> Empty </strong>';
				} 

				if ( empty($email) ) {
					$formErrors[] = 'Email Cannot Be <strong> Empty </strong>' ;
				}

				if(! empty($imageName) && ! in_array($imageExtension, $imageAllowedExtension) ) {
					$formErrors[] = 'This Extension Is Not <strong> Allowed </strong>' ;
				}

				if(empty($imageName)) {
					$formErrors[] = 'Image Is <strong> Required </strong>' ;
				}

				if($imageSize > 4194304) {
					$formErrors[] = 'Image Can Be Larger Than <strong> 4MB </strong>' ;
				}

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>" . $error ."</div>";
				}

				
				// Check If theres No Error Proceed The Update Operation
				if ( empty($formErrors) ) {

					$image = rand(0, 1000000) . '_' . $imageName;
					
					move_uploaded_file($imageTmp, "uploads\images\\" . $image);

					//Check If User Exist In Database
					$check = checkItem("Username", "users", $user);

					if ($check == 1) {

						$theMsg = '<div class="alert alert-danger">Sorry This User Is Exist</div>';
						redirectHome($theMsg, 'back');

					} else {

						// Insert User Info In DataBase
						$stmt = $con->prepare("INSERT INTO
											   users(Username, Password, Email, FullName, RegStatus, Date, user_profile)
											   VALUES(:zuser, :zpass, :zemail, :zname, 1, now(), :zimg) ");
						$stmt->execute(array(
							'zuser' 	=> $user,
							'zpass' 	=> $hashPass,
							'zemail'	=> $email,
							'zname'		=> $name,
							'zimg' 		=> $image
						));

						//Echo Success Message
						$theMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Updated </div>";
						redirectHome($theMsg, 'back', 1);

					}

				}

				

			} else {

				echo "<div class='container'>";
					$errorMeg = "<h1>Sorry You Cannot Browse This Page Directly</h1>";
					redirectHome($errorMeg, 'back', 2);
				echo "</div>";
			}

			echo "</div>"; // End Container Div 	
			

/***************************** Start Edit Page ****************************************/
		} elseif ( $do == 'Edit' ) { // Start Edit Page 

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :0;
			
			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
			$stmt->execute( array($userid) );
			$row = $stmt -> fetch();
			$count = $stmt->rowCount();

			if ( $count > 0 ) { ?>

				<h1 class="text-center">Edit Members</h1>

				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="userid" value="<?php echo $userid; ?>";

						<!-- Start Username Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Username</label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="username" value="<?php echo $row['Username'] ?>" class="form-control" autocomplete="off" required="required"/>
							</div>
						</div>
						<!-- End Username Field -->

						<!-- Start Password Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Password</label>
							<div class="col-sm-10 col-md-8"">
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>"/>
								<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change"/>
							</div>
						</div>
						<!-- End Password Field -->

						<!-- Start Email Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Email</label>
							<div class="col-sm-10 col-md-8"">
								<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Email Field -->

						<!-- Start Full Name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Full Name</label>
							<div class="col-sm-10 col-md-8"">
								<input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
							</div>
						</div>
						<!-- End Full Name Field -->

						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->

					</form>
				</div> <!-- End Container Div -->
			
		<?php 
			} else {

				echo "<div class='container'>";
				echo '<h1></h1>';
					$errorMeg = "<div class='alert alert-danger'> The ID Is Not Exist </div>";
					redirectHome($errorMeg);
				echo '</div>';

			} // End Check Id Is Exist In Database

/********************************** Start Update Page ******************************************************/
		} elseif ( $do == 'Update' ) { // Update Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1 class='text-center'>Update Members</h1>";
				echo "<div class='container'>";

				// Get variables From the Form
				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];
				// Password Trick
				// Condition ? True : False;
				$pass   = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : $pass = sha1( $_POST['newpassword']);
				
				// Validate The Form
				$formErrors = array();

				if ( strlen($user) < 3 ) {
					$formErrors[] = 'Username Cannot Be Less Than <strong> 3 Characters</strong>';
				}

				if ( strlen($user) > 25 ) {
					$formErrors[] = 'Username Cannot Be More Than <strong> 25 Characters</strong>';
				}

				if ( empty($user) ) {
					$formErrors[] = 'Username Cannot Be <strong> Empty </strong>';
				} 

				if ( empty($name) ) {
					$formErrors[] = 'Full Name Cannot Be <strong> Empty </strong>';
				} 

				if ( empty($email) ) {
					$formErrors[] = 'Email Cannot Be <strong> Empty </strong>' ;
				}

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>" . $error ."</div>";
				}

				// Check If theres No Error Proceed The Update Operation
				if ( empty($formErrors) ) {

					$stmt2 = $con->prepare("SELECT 
											* FROM 
												users 
											WHERE 
												Username = ? 
											AND 
												UserID != ?");
					$stmt2->execute(array($user, $id));

					$count = $stmt2->rowCount();

					if ($count == 1) {
						$errorMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
						redirectHome($errorMsg, 'back', 1);
					} else {
						// Update The DataBase With this Info
						$stmt = $con->prepare("UPDATE users SET Username = ?, Email =?, FullName = ?, Password = ? WHERE UserID =? ");
						$stmt->execute( array($user, $email, $name, $pass, $id) );

						//Echo Success Message
						$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Updated </div>";
						redirectHome($errorMsg, 'back', 1);
					}
				}

			} else {
				echo '<div class="container">';
				echo '<h1></h1>';
					$errorMsg = "<div class='alert alert-danger'>Sorry You Can Not Browse This Page Directly</div>";
					redirectHome($errorMsg);
				echo "</div>";
			}

			echo "</div>"; // End Container Div  
		} // End Update Page 

		elseif ($do == 'Delete') { // Delete Members Page

			echo "<h1 class='text-center'>Deleted Member</h1>";
			echo "<div class='container'>";

			// Chech If Get Request userid Is Numeric & Get The Integer Value Of It
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :0;
			
			// Select All Data Depend On This ID
			$check = checkItem('userid', 'users', $userid); // This Is Custom Function

			// If The ID Is Exist Show The Form
			if ( $check > 0 ) { 
				$stmt = $con-> prepare("DELETE FROM users WHERE UserID = :zuser");
				$stmt->bindparam(":zuser", $userid);
				$stmt->execute();

				$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Deleted </div>";
				redirectHome($errorMsg, 'back', 1);

			} else {

				$errorMeg = "<div class='alert alert-danger'>Sorry..! The Id Is Not Exist</div>";
				redirectHome($errorMeg);

			
			}

			echo "<div>";

		} // End Delete Members Page

		elseif ($do = 'Activate') {

			echo "<h1 class='text-center'>Activate Member</h1>";
			echo "<div class='container'>";

			// Chech If Get Request userid Is Numeric & Get The Integer Value Of It
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) :0;
			
			// Select All Data Depend On This ID
			$check = checkItem('userid', 'users', $userid); // This Is Custom Function

			// If The ID Is Exist Show The Form
			if ( $check > 0 ) { 

				$stmt = $con-> prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

				$stmt->execute(array($userid));

				$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Updated </div>";
				redirectHome($errorMsg, 'back', 1);

			} else {

				$errorMeg = "<div class='alert alert-danger'>Sorry..! The Id Is Not Exist</div>";
				redirectHome($errorMeg);

			
			}

			echo "<div>";

		} // End Activate Members Page

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');
		exit();
	}

	ob_end_flush();
?>