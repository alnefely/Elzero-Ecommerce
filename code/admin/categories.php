<?php
	/*
	============================================
	=== Category Page
	============================================
	*/

	ob_start();

	session_start();

	$pageTitle = 'Categories';

	if ( isset($_SESSION['Username']) ) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ( $do == 'Manage' ) { // Start Manage Page

			$sort = 'ASC';

			$sort_array = array('ASC', 'DESC');

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

				$sort = $_GET['sort'];

			}

			$stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
			$stmt2->execute();
			$cats = $stmt2->fetchAll();

			if (! empty($cats))	{
			?>
				<h1 class="text-center">Manage Categories</h1>
				<div class="container categories">
					<div class="panel panel-default">
						<div class="panel-heading">
						<i class="fa fa-edit"></i> Manage Categories
						<div class="pull-right option">
							<i class="fa fa-sort"></i> Ordering: [
							<a class="<?php if($sort == 'ASC') {echo'active';} ?>" href="?sort=ASC">Asc</a> |
							<a class="<?php if($sort == 'DESC') {echo'active';} ?>" href="?sort=DESC">Desc</a> ]
							<i class="fa fa-eye"></i> View: [
							<span class="active" data-view="full">Full</span> |
							<span data-view="classic">Classic</span> ]
						</div>
						</div>
						<div class="panel-body">	
						<?php 
							foreach ( $cats as $cat ) {
								echo '<div class="cat">';
									echo '<div class="hidden-buttons">';
										echo "<a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
										echo "<a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
									echo '</div>';
									echo '<h3>' . $cat['Name'] . '</h3>';
									echo "<div class='full-view'> ";
										echo '<p>';  if($cat['Description'] == '' ){echo 'This Is Category Has No Description';} else{echo $cat['Description'];} echo '</p>';
										if($cat['Visibility'] == 1){echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';}
										if($cat['Allow_Comment'] == 1){echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
										if($cat['Allow_Ads'] == 1){echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>';}
									echo "</div>";

									// Get Child Categories
									$childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID", "ASC");
					      			if (! empty($childCats)){
						      			echo '<h4 class="child-head">Child Categories</h4>';
						      			echo '<ul class="list-unstyled child-cats">';
							      			foreach ($childCats as $c) {
												echo"<li class='chiled-link'>
														<a href='categories.php?do=Edit&catid=". $c['ID'] ."'>" . $c['Name'] . "</a>
														<a href='categories.php?do=Delete&catid=". $c['ID'] ."' class='confirm show-delete'> Delete</a>
													</li>"; 
											} // End For Loop $allCats
										echo '</ul>';
									}

								echo '</div>';
								echo '<hr>';
							}
						?>
						</div>
					</div>
					<a class="add-catgory btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Catrgory</a>
				</div>
			<?php
			} else {
				echo '<div class="container">';
					echo '<div class="nice-message"><b>There\'s No Categories To Show</b></div>';
					echo '<a class="add-catgory btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Catrgory</a>';
				echo '</div>';
			}
			?>

		<?php } // End Manage Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Add' ) { // Start Add Page ?>

			<h1 class="text-center">Add New Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">

						<!-- Start name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Name</label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category"/>
							</div>
						</div>
						<!-- End Name Field -->

						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Description</label>
							<div class="col-sm-10 col-md-8"">
								<input type="text" name="description" class="form-control" placeholder="Describe The Category"/>
							</div>
						</div>
						<!-- End Description Field -->

						<!-- Start Ordering Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Ordering</label>
							<div class="col-sm-10 col-md-8"">
								<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories"/>
							</div>
						</div>
						<!-- End Ordering Field -->

						<!-- Start Category Type -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Parent</label>
							<div class="col-sm-10 col-md-8"">
								<select name="parent">
									<option value="0">None</option>
									<?php 
										$allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");

										foreach ($allCats as $cat) {
											echo '<option value="'. $cat['ID'] .'">'. $cat['Name'] .'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Category Type -->

						<!-- Start Visibility Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Visible</label>
							<div class="col-sm-10 col-md-8"">

								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" checked />
									<label for="vis-yes">Yes</label>
								</div>

								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" />
									<label for="vis-no">No</label>
								</div>
							
							</div>
						</div>
						<!-- End Visibility Field -->

						<!-- Start Commenting Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Allow Commenting</label>
							<div class="col-sm-10 col-md-8"">

								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" checked />
									<label for="com-yes">Yes</label>
								</div>

								<div>
									<input id="com-no" type="radio" name="commenting" value="1" />
									<label for="com-no">No</label>
								</div>
							
							</div>
						</div>
						<!-- End Commenting Field -->

						<!-- Start Ads Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Allow Ads</label>
							<div class="col-sm-10 col-md-8"">

								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" checked />
									<label for="ads-yes">Yes</label>
								</div>

								<div>
									<input id="ads-no" type="radio" name="ads" value="1" />
									<label for="ads-no">No</label>
								</div>
							
							</div>
						</div>
						<!-- End Ads Field -->

						<!-- Start Submit Field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Submit Field -->

					</form>
				</div> <!-- End Container Div -->

		<?php } // End Add Page 

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Insert' ) { // Start Insert Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1 class='text-center'>Insert Category</h1>";

				echo "<div class='container'>";

				// Get variables From the Form
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$parent 	= $_POST['parent'];
				$order 		= $_POST['ordering'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$ads 		= $_POST['ads'];
				
				//Check If Category Exist In Database
				$check = checkItem("Name", "categories", $name);

				if ($check == 1) {

					$theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist</div>';
					redirectHome($theMsg, 'back');

				} else {

					// Insert Category Info In DataBase
					$stmt = $con->prepare("INSERT INTO
										   categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
										   VALUES(:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment, :zads)");
					$stmt->execute(array(
						'zname' 		=> $name,
						'zdesc' 		=> $desc,
						'zparent' 		=> $parent,
						'zorder'		=> $order,
						'zvisible'		=> $visible,
						'zcomment'		=> $comment,
						'zads'			=> $ads,
					));

					//Echo Success Message
					$theMsg = "<div class='alert alert-success'> <strong>".$stmt->rowCount() . "</strong> Record Updated </div>";
					redirectHome($theMsg, 'back', 1);

				}

			} else {

				echo "<div class='container'>";
					$errorMeg = "<h1>Sorry You Cannot Browse This Page Directly</h1>";
					redirectHome($errorMeg, 'back', 2);
				echo "</div>";
			}

			echo "</div>"; // End Container Div 

		} // End Insert Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Edit' ) { // Start Edit Page

			// Check If Get Request catid Is Numeric & Its Integer Value
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :0;
			
			// Select All Data Depend On This ID
			$stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

			// Execute Query
			$stmt->execute( array($catid) );

			//Fetch The Data
			$cat = $stmt -> fetch();

			// The Row Count
			$count = $stmt->rowCount();

			// If There's Such ID Show The Form
			if ( $count > 0 ) { ?>

				<h1 class="text-center">Edit Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="catid" value="<?php echo $catid ?>" />

						<!-- Start name Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Name :</label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php  echo $cat['Name']; ?>"/>
							</div>
						</div>
						<!-- End Name Field -->

						<!-- Start Description Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Description :</label>
							<div class="col-sm-10 col-md-8"">
								<input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php  echo $cat['Description']; ?>"/>
							</div>
						</div>
						<!-- End Description Field -->

						<!-- Start Category Type -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">?Parent</label>
							<div class="col-sm-10 col-md-8"">
								<select name="parent">
									<option value="0">None</option>
									<?php 
										$allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");

										foreach ($allCats as $c) {
											echo "<option value='". $c['ID'] . "'";
											if ($cat['parent'] == $c['ID'] ) {
												echo ' selected';
											}
											echo ">". $c['Name'] ."</option>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Category Type -->

						<!-- Start Ordering Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Ordering :</label>
							<div class="col-sm-10 col-md-8"">
								<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php  echo $cat['Ordering']; ?>"/>
							</div>
						</div>
						<!-- End Ordering Field -->

						<!-- Start Visibility Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Visible :</label>
							<div class="col-sm-10 col-md-8"">

								<div>
									<input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){echo 'checked';} ?> />
									<label for="vis-yes">Yes</label>
								</div>

								<div>
									<input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1){echo 'checked';} ?> />
									<label for="vis-no">No</label>
								</div>
							
							</div>
						</div>
						<!-- End Visibility Field -->

						<!-- Start Commenting Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Allow Commenting :</label>
							<div class="col-sm-10 col-md-8"">

								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0){echo 'checked';} ?> />
									<label for="com-yes">Yes</label>
								</div>

								<div>
									<input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo 'checked';} ?> />
									<label for="com-no">No</label>
								</div>
							
							</div>
						</div>
						<!-- End Commenting Field -->

						<!-- Start Ads Field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-lable">Allow Ads :</label>
							<div class="col-sm-10 col-md-8"">

								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked';} ?> />
									<label for="ads-yes">Yes</label>
								</div>

								<div>
									<input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked';} ?> />
									<label for="ads-no">No</label>
								</div>
							
							</div>
						</div>
						<!-- End Ads Field -->

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

			// If There's No Such ID Show Error Message 
			} else {

				echo "<div class='container'>";
				echo '<h1></h1>';
					$errorMeg = "<div class='alert alert-danger'> This ID Not Exist </div>";
					redirectHome($errorMeg);
				echo '</div>';
			}

		} // End Edit Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Update' ) { // Start Update Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST'){

				echo "<h1 class='text-center'>Update Members</h1>";
				echo "<div class='container'>";

				// Get variables From the Form
				$id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['ordering'];
				$parent 	= $_POST['parent'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$ads 		= $_POST['ads'];

				// Update The DataBase With this Info
				$stmt = $con->prepare("UPDATE 
										categories
									SET	
										Name = ?,
										Description = ?,
										Ordering = ?,
										parent = ?,
										Visibility = ?,
										Allow_Comment = ?,
										Allow_Ads = ?
									WHERE
										ID = ?
										");
				$stmt->execute( array($name, $desc, $order, $parent, $visible, $comment, $ads, $id) );

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

			echo "</div>"; // End Container Div  From Updata Page

		} // End Update Page

//////////////////////////////////////////////////////////////////////////

		elseif ( $do == 'Delete' ) { // Start Delete Page

			echo "<h1 class='text-center'>Deleted Category</h1>";
			echo "<div class='container'>";

			// Chech If Get Request catid Is Numeric & Get The Integer Value Of It
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) :0;
			
			// Select All Data Depend On This ID
			$check = checkItem('ID', 'categories', $catid); // This Is Custom Function

			// If The ID Is Exist Show The Form
			if ( $check > 0 ) { 
				$stmt = $con-> prepare("DELETE FROM categories WHERE ID = :zid");
				$stmt->bindparam(":zid", $catid);
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

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();

	}

	ob_end_flush(); // Release The Output

?>