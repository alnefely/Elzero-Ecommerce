<?php

	ob_start(); // Output Buffering Start

	/*
	================================================
	== Manage Comments Page 
	== You Can Edit | Delete | Approve Members From Here
	================================================
	*/

	session_start();

	$pageTitle = 'Comments';

	if ( isset($_SESSION['Username']) ) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

/**************************** Start Manage Members Page *****************************************/
		
		if ( $do == 'Manage' ) { // Manage Members Page 

			// Select All Users Except Admin
			$stmt = $con->prepare("SELECT 
										comments.*, items.Name AS Item_Name, users.Username AS Member
									FROM 
										comments
									INNER JOIN
										items
									ON 
										items.Item_ID = comments.item_id
									INNER JOIN
										users
									ON
										users.UserID = comments.user_id
									ORDER BY
									c_id DESC");
			$stmt->execute(); // Execute The Statement

			// Assign To Variable
			$rows = $stmt->fetchAll();

			if (! empty($rows)){?>

				<h1 class="text-center">Manage Comments</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">

							<tr>
								<td>ID</td>
								<td>Comment</td>
								<td>Item Name</td>
								<td>User Name</td>
								<td>Added Date</td>
								<td>Control</td>
							</tr>

							<?php 

								foreach ( $rows as $row ) {

									echo "<tr>";

										echo "<td>" . $row['c_id'] . "</td>";
										echo "<td>" . $row['comment'] . "</td>";
										echo "<td>" . $row['Item_Name'] . "</td>";
										echo "<td>" . $row['Member'] . "</td>";
										echo "<td>" . $row['comment_date'] . "</td>";
										echo "<td>
												<a href='comments.php?do=Edit&comid=". $row['c_id'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
												<a href='comments.php?do=Delete&comid=". $row['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
											
												if ($row['status'] == 0) {

													echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";

												}

											echo "</td>";

									echo "</tr>";

								} // End For Loop							

							?>


						</table>
					</div><!-- End Div Table -->
				</div>
			<?php 
			} else {
				echo '<div class="container">';
					echo '<div class="nice-message"><b>There\'s No Comments To Show</b></div>';
				echo '</div>';
			} ?>

		<?php
/***************************** Start Edit Comment Page ****************************************/
		} elseif ( $do == 'Edit' ) { // Start Edit Comment Page 

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :0;
			
			$stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
			$stmt->execute( array($comid) );
			$row = $stmt -> fetch();
			$count = $stmt->rowCount();

			if ( $count > 0 ) { ?>

				<h1 class="text-center">Edit Comment</h1>

				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="comid" value="<?php echo $comid; ?>">;

						<!-- Start Comment Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Comment</label>
							<div class="col-sm-10 col-md-8">
							<textarea style="height:300px;resize: none;" class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
							</div>
						</div>
						<!-- End Comment Field -->

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

/********************************** Start Update Comment Page ******************************************************/
		} elseif ( $do == 'Update' ) { // Update Comment Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1 class='text-center'>Update Comment</h1>";
				echo "<div class='container'>";

				// Get variables From the Form
				$comid 		= $_POST['comid'];
				$comment 	= $_POST['comment'];

				// Update The DataBase With this Info
				$stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");

				$stmt->execute( array($comment, $comid) );

				//Echo Success Message
				$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Updated </div>";
				redirectHome($errorMsg, 'back', 1);

			} else {
				echo '<div class="container">';
				echo '<h1></h1>';
					$errorMsg = "<div class='alert alert-danger'>Sorry You Can Not Browse This Page Directly</div>";
					redirectHome($errorMsg);
				echo "</div>";
			}

			echo "</div>"; // End Container Div  
		} // End Update Comment Page 

		elseif ($do == 'Delete') { // Delete Comment Page

			echo "<h1 class='text-center'>Deleted Comment</h1>";
			echo "<div class='container'>";

			// Chech If Get Request comid Is Numeric & Get The Integer Value Of It
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :0;
			
			// Select All Data Depend On This ID
			$check = checkItem('c_id', 'comments', $comid); // This Is Custom Function

			// If The ID Is Exist Show The Form
			if ( $check > 0 ) { 
				$stmt = $con-> prepare("DELETE FROM comments WHERE c_id = :zid");
				$stmt->bindparam(":zid", $comid);
				$stmt->execute();

				$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Deleted </div>";
				redirectHome($errorMsg, 'back', 1);

			} else {

				$errorMeg = "<div class='alert alert-danger'>Sorry..! The Id Is Not Exist</div>";
				redirectHome($errorMeg);

			
			}

			echo "<div>";

		} // End Delete Comment Page

		elseif ($do = 'Approve') { // Start Approve Page

			echo "<h1 class='text-center'>Approve Comment</h1>";
			echo "<div class='container'>";

			// Chech If Get Request comid Is Numeric & Get The Integer Value Of It
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) :0;
			
			// Select All Data Depend On This ID
			$check = checkItem('c_id', 'comments', $comid); // This Is Custom Function

			// If The ID Is Exist Show The Form
			if ( $check > 0 ) { 

				$stmt = $con-> prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

				$stmt->execute(array($comid));

				$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Approved </div>";
				redirectHome($errorMsg, 'back', 1);

			} else {

				$errorMeg = "<div class='alert alert-danger'>Sorry..! The Id Is Not Exist</div>";
				redirectHome($errorMeg);

			
			}

			echo "<div>";

		} // End Approve Members Page

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');
		exit();
	}

	ob_end_flush();
?>