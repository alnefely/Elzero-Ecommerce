<?php 
	ob_start();
	session_start();
	$pageTitle = 'Create New Item'; // Page Title
	include "init.php";
	if (isset($_SESSION['user'])) {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$formErrors = array();

			$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
			$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$country 	= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$status 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$category 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
			$tags 		= filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

			if(strlen($name) < 4){
				$formErrors[] = 'Item Title Must Be At Least 4 Characters';
			}

			if(strlen($desc) < 15){
				$formErrors[] = 'Item Description Must Be At Least 10 Characters';
			}

			if(strlen($country) < 2){
				$formErrors[] = 'Item Country Must Be At Least 2 Characters';
			}

			if(empty($price)){
				$formErrors[] = 'Item Price Must Be Empty';
			}

			if(empty($status)){
				$formErrors[] = 'Item Status Must Be Empty';
			}

			if(empty($category)){
				$formErrors[] = 'Item Category Must Be Empty';
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
					'zcat'		=> $category,
					'zmember'	=> $_SESSION['uid'],
					'ztags'		=> $tags
				));

				if($stmt){
					//Echo Success Message
					$succesMsg = 'Item Has Been Added';
				}
			}
		}
?>
	<h1 class="text-center"><?php echo $pageTitle; ?></h1>

	<div class="create-ad block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading"><?php echo $pageTitle; ?></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
								<!-- Start name Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-lable">Name</label>
									<div class="col-sm-10 col-md-10">
										<input
										pattern=".{4,}"
										title="This Field Require At Least 4 Characters"
										type="text" 
										name="name" 
										class="form-control live-name" 
										placeholder="Name Of The Item"
										required />
									</div>
								</div>
								<!-- End Name Field -->
								<!-- Start Description Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-lable">Description</label>
									<div class="col-sm-10 col-md-10">
										<input
										pattern=".{15,}"
										title="Description Must Be 10 Chars"
										type="text" 
										name="description"
										class="form-control live-des" 
										placeholder="Description Of The Item"
										required />
									</div>
								</div>
								<!-- End Description Field -->
								<!-- Start Price Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-lable">Price</label>
									<div class="col-sm-10 col-md-10">
										<input 
										type="text" 
										name="price"
										class="form-control live-price"
										placeholder="Price Of The Item"
										required />
									</div>
								</div>
								<!-- End Price Field -->
								<!-- Start Country_Made Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-lable">Country</label>
									<div class="col-sm-10 col-md-10">
										<input 
										type="text" 
										name="country"
										class="form-control"
										placeholder="Country OF Made"
										required />
									</div>
								</div>
								<!-- End Country_Made Field -->
								<!-- Start Status Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-lable">Status</label>
									<div class="col-sm-10 col-md-10">
										<select name="status" required>
											<option value="">...</option>
											<option value="1">New</option>
											<option value="2">Like New</option>
											<option value="3">Used</option>
											<option value="4">Very Old</option>
										</select>
									</div>
								</div>
								<!-- End Status Field -->
								<!-- Start Category Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-lable">Category</label>
									<div class="col-sm-10 col-md-10">
										<select name="category" required>
											<option value="">...</option>
											<?php

												$cats = getAllFrom('*', 'categories', '', '', 'ID');
												foreach ($cats as $cat){
													echo "<option value='". $cat['ID'] ."'>". $cat['Name'] ."</option>";
												}
											?>
										</select>
									</div>
								</div>
								<!-- End Category Field -->

								<!-- Start Tags Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-lable">Tags</label>
									<div class="col-sm-10 col-md-10">
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
						</div> <!-- End Div col-md-8 -->

						<div class="col-md-4">
							<div class="thumbnail item-box live-preview">
					          	<span class="price-tag">$0</span>
								<img class="img-responsive" src="avatar.png" alt=""/>
								<div class="caption">
									<h3>Title</h3>
									<p>Descraption</p>
								</div>
							</div> <!-- End Div item-box -->
						</div> <!-- End Div col-md-4 -->
					</div> <!-- End Div Row -->
					<!-- Start Looping Through Errors -->
					<?php 
						if(! empty($formErrors)){
							foreach($formErrors as $error) {
								echo '<div class="alert alert-danger">'. $error .'</div>';
							}
						}

						if (isset($succesMsg)) {
							echo '<div class="alert  alert-success">' . $succesMsg . '</div>';
						}
					?>
					<!-- End Looping Through Errors -->
				</div> <!-- End Panel-Body -->
			</div> <!-- End Panel-Heading -->
		</div> <!-- End Div Container -->
	</div> <!-- End Div Create-Ad -->

	
<?php
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>