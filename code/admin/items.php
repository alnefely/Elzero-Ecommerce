<?php

	ob_start(); //Output Buffering Start

	session_start();

	$pageTitle = 'Items';

	if ( isset($_SESSION['Username']) ) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ( $do == 'Manage' ) { // Start Manage Page

			$stmt = $con->prepare("SELECT 
										items . *,
										categories.Name AS category_name,
										users.Username 
									FROM 
										items
									INNER JOIN
										categories 
									ON 
										categories.ID = items.Cat_ID
									INNER JOIN
										users
									ON 
										users.UserID = items.Member_ID
									ORDER BY
									Item_ID DESC");
			$stmt->execute(); // Execute The Statement

			// Assign To Variable
			$items = $stmt->fetchAll();

			if (! empty($items)){ ?>

				<h1 class="text-center">Manage Items</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">

							<tr>
								<td>#ID</td>
								<td>Name</td>
								<td>Description</td>
								<td>Price</td>
								<td>Adding Date</td>
								<td>Category</td>
								<td>Username</td>
								<td>Control</td>
							</tr>

							<?php 

								foreach ( $items as $item ) {

									echo "<tr>";

										echo "<td>" . $item['Item_ID'] . "</td>";
										echo "<td>" . $item['Name'] . "</td>";
										echo "<td>" . $item['Description'] . "</td>";
										echo "<td>" . $item['Price'] . "</td>";
										echo "<td>" . $item['Add_Date'] . "</td>";
										echo "<td>" . $item['category_name'] . "</td>";
										echo "<td>" . $item['Username'] . "</td>";
										echo "<td>
											<a href='items.php?do=Edit&itemid=". $item['Item_ID'] ."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='items.php?do=Delete&itemid=". $item['Item_ID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
											if ($item['Approve'] == 0) {
												echo "<a href='items.php?do=Approve&itemid=". $item['Item_ID'] ."' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
											}
										echo "</td>";
									echo "</tr>";

								} // End For Loop							
							?>
						</table>
					</div><!-- End Div Table -->
				
					<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>
				</div>
			<?php } else {
				echo '<div class="container">';
					echo '<div class="nice-message"><b>There\'s No Items To Show</b></div>';
					echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>';
				echo '</div>';
				} ?>	

		<?php } // End Manage Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Add' ) { // Start Add Page ?>

			<h1 class="text-center">Add New Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
						<!-- Start name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Name</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="name" 
								class="form-control" 
								required="required" 
								placeholder="Name Of The Item" />
							</div>
						</div>
						<!-- End Name Field -->

						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Description</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="description"
								class="form-control" 
								required="required"
								placeholder="Description Of The Item" />
							</div>
						</div>
						<!-- End Description Field -->

						<!-- Start Price Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Price</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="price"
								class="form-control" 
								required="required"
								placeholder="Price Of The Item" />
							</div>
						</div>
						<!-- End Price Field -->

						<!-- Start Country_Made Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Country</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="country"
								class="form-control" 
								required="required" 
								placeholder="Country OF Made" />
							</div>
						</div>
						<!-- End Country_Made Field -->

						<!-- Start Status Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Status</label>
							<div class="col-sm-10 col-md-8">
								<select name="status">
									<option value="0">...</option>
									<option value="1">New</option>
									<option value="2">Like New</option>
									<option value="3">Used</option>
									<option value="4">Very Old</option>
								</select>
							</div>
						</div>
						<!-- End Status Field -->

						<!-- Start Members Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Member</label>
							<div class="col-sm-10 col-md-8">
								<select name="member">
									<option value="0">...</option>
									<?php 

										$allMembers = getAllFrom("*", "users", "", "", "UserID");
										foreach ($allMembers as $user){
											echo "<option value='". $user['UserID'] ."'>". $user['Username'] ."</option>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Members Field -->

						<!-- Start Category Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Category</label>
							<div class="col-sm-10 col-md-8">
								<select name="category">
									<option value="0">...</option>
									<?php 

										$allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID");
										foreach ($allCats as $cat){
											echo "<option value='". $cat['ID'] ."'>". $cat['Name'] ."</option>";
											$childCats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']}", "", "ID");
											foreach ($childCats as $child) {
												echo "<option value='". $child['ID'] ."'>--- ". $child['Name'] ."</option>";
											}
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Category Field -->

						<!-- Start Tags Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Tags</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="tags"
								class="form-control"
								placeholder="Separate Tags With Comma (,)" />
							</div>
						</div>
						<!-- End Tags Field -->

						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>
				</div> <!-- End Container Div -->

		<?php } // End Add Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Insert' ) { // Start Insert Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1 class='text-center'>Insert Item</h1>";

				echo "<div class='container'>";

				// Get variables From the Form
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$status 	= $_POST['status'];
				$member 	= $_POST['member'];
				$cat 		= $_POST['category'];
				$tags 		= $_POST['tags'];
				
				// Validate The Form
				$formErrors = array();

				if (empty($name) ) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($country)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				} 

				if ($status == 0) {
					$formErrors[] = 'You Must Choose <strong>Status</strong>';
				} 

				if ($member == 0) {
					$formErrors[] = 'You Must Choose The <strong>Member</strong>';
				} 

				if ($cat == 0) {
					$formErrors[] = 'You Must Choose The <strong>Category</strong>';
				} 

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>" . $error ."</div>";
				}

				// Check If theres No Error Proceed The Update Operation
				if (empty($formErrors)) {
				
					// Insert Item Info In DataBase
					$stmt = $con->prepare("INSERT INTO
										   items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, 	Member_ID, tags)
										   VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember, :ztags)");
					$stmt -> execute(array(
						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zprice'	=> $price,
						'zcountry'	=> $country,
						'zstatus'	=> $status,
						'zcat'		=> $cat,
						'zmember'	=> $member,
						'ztags' 	=> $tags
					));
				
					//Echo Success Message
					$theMsg = "<div class='alert alert-success'> <strong>" . $stmt->rowCount() . "</strong> Record Inserted </div>";
					redirectHome($theMsg, 'back', 1);

				}

			} else {

				echo "<div class='container'>";
					$errorMeg = "<h1>Sorry You Cannot Browse This Page Directly</h1>";
					redirectHome($errorMeg);
				echo "</div>";
			}

			echo "</div>"; // End Container Div 

		} // End Insert Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Edit' ) { // Start Edit Page

			// Check If Get Request item Is Numeric & Get Its Interer Value
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
			
			// Select All Data Depend On This ID 
			$stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");

			// Excute Query
			$stmt->execute( array($itemid) );
			
			// Fetch The Data
			$item = $stmt -> fetch();

			// The Row Count
			$count = $stmt -> rowCount();

			if ( $count > 0 ) { ?>

				<h1 class="text-center">Edit Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="itemid" value="<?php echo $itemid; ?>"/>

						<!-- Start name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Name</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="name" 
								class="form-control"
								required="required"
								value="<?php  echo $item['Name'];?>"/>
							</div>
						</div>
						<!-- End Name Field -->

						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Description</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="description"
								class="form-control" 
								required="required"
								value="<?php  echo $item['Description'];?>"/>
							</div>
						</div>
						<!-- End Description Field -->

						<!-- Start Price Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Price</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="price"
								class="form-control" 
								required="required" 
								value="<?php  echo $item['Price'];?>" />
							</div>
						</div>
						<!-- End Price Field -->

						<!-- Start Country_Made Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Country</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="country"
								class="form-control" 
								required="required" 
								value="<?php  echo $item['Country_Made'];?>" />
							</div>
						</div>
						<!-- End Country_Made Field -->

						<!-- Start Status Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Status</label>
							<div class="col-sm-10 col-md-8">
								<select name="status">
									<option value="0">...</option>
									<option value="1" <?php if($item['Status'] == 1){echo "selected";} ?> >New</option>
									<option value="2" <?php if($item['Status'] == 2){echo "selected";} ?> >Like New</option>
									<option value="3" <?php if($item['Status'] == 3){echo "selected";} ?> >Used</option>
									<option value="4" <?php if($item['Status'] == 4){echo "selected";} ?> >Very Old</option>
								</select>
							</div>
						</div>
						<!-- End Status Field -->

						<!-- Start Members Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Member</label>
							<div class="col-sm-10 col-md-8">
								<select name="member">
									<?php 
										$stmt = $con->prepare("SELECT * FROM users");
										$stmt->execute();
										$users = $stmt->fetchAll();
										foreach ($users as $user){
											echo "<option value='". $user['UserID'] ."'";
											if($item['Member_ID'] == $user['UserID']){echo 'selected';}
											echo ">". $user['Username'] ."</option>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Members Field -->

						<!-- Start Category Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Category</label>
							<div class="col-sm-10 col-md-8">
								<select name="category">
									<?php 
										$stmt2 = $con->prepare("SELECT * FROM categories");
										$stmt2->execute();
										$cats = $stmt2->fetchAll();
										foreach ($cats as $cat){
											echo "<option value='". $cat['ID'] ."'";
											if($item['Cat_ID'] == $cat['ID']){echo 'selected';}
											echo ">". $cat['Name'] ."</option>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Category Field -->

						<!-- Start Tags Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Tags</label>
							<div class="col-sm-10 col-md-8">
								<input 
								type="text" 
								name="tags"
								class="form-control"
								placeholder="Separate Tags With Comma (,)" 
								value="<?php echo $item['tags']?>" />
							</div>
						</div>
						<!-- End Tags Field -->

						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
							</div>
						</div>
						<!-- End Submit Field -->
					</form>

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
									WHERE item_id= ?");
			$stmt->execute(array($itemid)); // Execute The Statement

			// Assign To Variable
			$rows = $stmt->fetchAll();

			if( ! empty($rows) ){

			?>

			<h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">

						<tr>
							<td>Comment</td>
							<td>User Name</td>
							<td>Added Date</td>
							<td>Control</td>
						</tr>

						<?php 

							foreach ( $rows as $row ) {

								echo "<tr>";
									echo "<td>" . $row['comment'] . "</td>";
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
				<?php } ?>
			</div>
			
			<?php 
			} else {

				echo "<div class='container'>";
				echo '<h1></h1>';
					$errorMeg = "<div class='alert alert-danger'> The ID Is Not Exist </div>";
					redirectHome($errorMeg);
				echo '</div>';

			} // End Check Id Is Exist In Database

		} // End Edit Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Update' ) { // Start Update Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1 class='text-center'>Update Item</h1>";
				echo "<div class='container'>";

				// Get variables From the Form
				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$status 	= $_POST['status'];
				$cat  		= $_POST['category'];
				$member 	= $_POST['member'];
				$tags 		= $_POST['tags'];


				// Validate The Form
				$formErrors = array();

				if (empty($name) ) {
					$formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
				}

				if (empty($price)) {
					$formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
				}

				if (empty($country)) {
					$formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
				} 

				if ($status == 0) {
					$formErrors[] = 'You Must Choose <strong>Status</strong>';
				} 

				if ($member == 0) {
					$formErrors[] = 'You Must Choose The <strong>Member</strong>';
				} 

				if ($cat == 0) {
					$formErrors[] = 'You Must Choose The <strong>Category</strong>';
				} 

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>" . $error ."</div>";
				}

				// Check If theres No Error Proceed The Update Operation
				if ( empty($formErrors) ) {

					// Update The DataBase With this Info
					$stmt = $con->prepare("UPDATE
												items
											SET 
												Name = ?,
												Description =?,
												Price = ?,
												Country_Made = ?,
												Status = ?,
												Cat_Id = ?,
												Member_ID = ?,
												tags = ?
											WHERE
												Item_ID =? ");

					$stmt->execute( array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id) );

					//Echo Success Message
					$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Updated </div>";
					redirectHome($errorMsg, 'back', 1);
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

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Delete' ) { // Start Delete Page

			echo "<h1 class='text-center'>Deleted Item</h1>";
			echo "<div class='container'>";

			// Chech If Get Request userid Is Numeric & Get The Integer Value Of It
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :0;
			
			// Select All Data Depend On This ID
			$check = checkItem('Item_ID', 'items', $itemid); // This Is Custom Function

			// If The ID Is Exist Show The Form
			if ( $check > 0 ) { 

				$stmt = $con-> prepare("DELETE FROM items WHERE Item_ID = :zitemid");
				$stmt->bindparam(":zitemid", $itemid);
				$stmt->execute();

				$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Deleted </div>";
				redirectHome($errorMsg, 'back', 1);

			} else {

				$errorMeg = "<div class='alert alert-danger'>Sorry..! The Id Is Not Exist</div>";
				redirectHome($errorMeg);

			
			}

			echo "<div>";

		} // End Delete Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Approve' ) { // Start Approve Page

			echo "<h1 class='text-center'>Approve Item</h1>";
			echo "<div class='container'>";

			// Chech If Get Request itemid Is Numeric & Get The Integer Value Of It
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) :0;
			
			// Select All Data Depend On This ID
			$check = checkItem('Item_ID', 'items', $itemid); // This Is Custom Function

			// If The ID Is Exist Show The Form
			if ( $check > 0 ) { 

				$stmt = $con-> prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

				$stmt->execute(array($itemid));

				$errorMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Approved </div>";
				redirectHome($errorMsg, 'back', 1);

			} else {

				$errorMeg = "<div class='alert alert-danger'>Sorry..! The Id Is Not Exist</div>";
				redirectHome($errorMeg);

			
			}

			echo "<div>";

		} // End Approve Page

//////////////////////////////////////////////////////////////////////////

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();

	}

	ob_end_flush(); // Release The Output

?>

