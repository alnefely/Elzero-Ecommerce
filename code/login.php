<?php
	ob_start();
	session_start();
	$pageTitle = 'Login';
	if ( isset($_SESSION['user']) ) {
		header('Location: index.php'); // Redirect To  Page
	}

	include "init.php";

	//Check If User Coming From HTTP Post Request
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

		if (isset($_POST['login'])) {

			$user    = $_POST['username'];
			$pass    = $_POST['password'];

			$hashedPass  = sha1($pass);

			// check If User Exist In DataBase
			$stmt = $con->prepare("SELECT 
										UserID, Username, Password
										FROM 
											users 
										WHERE 
											Username = ?
										AND 
											Password = ?");
			$stmt->execute(array($user, $hashedPass));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();

			// Check > 0 This Mean the Database Contain Record About This Username 
			if ( $count > 0 ) {

				$_SESSION['user'] = $user;	// Register Session Name

				$_SESSION['uid'] = $get['UserID'];  //Register User IdD In Session

				header('Location: index.php');	// Redirect To Dashboard Page
				exit();
			} 
			
		} else {

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];

			// Filter Username
			if(isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

				if (strlen($filterdUser) < 4 ) {
					$formErrors[] = 'Username Must Be Larger Than 4 Characters';
				}
			}

			// Filter Password
			if(isset($password) && isset($password2)) {

				if(empty($password)) {

					$formErrors[] = 'Sorry Password Cant Be Empty';
				}

				if(sha1($password) !== sha1($password2)) {

					$formErrors[] = 'Sorry Password Is Not Match';
				}
			}

			// Filter Email
			if(isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true){

					$formErrors[] = 'This Email Is Not Valid';

				}
			}
				// Check If theres No Error Proceed The User Add
				if ( empty($formErrors) ) {

					//Check If User Exist In Database
					$check = checkItem("Username", "users", $username);

					if ($check == 1) {

						$formErrors[] = 'Sorry This User Is Exists';

					} else {

						// Insert User Info In DataBase
						$stmt = $con->prepare("INSERT INTO
											   users(Username, Password, Email, RegStatus, Date)
											   VALUES(:zuser, :zpass, :zemail, 0, now()) ");
						$stmt->execute(array(
							'zuser' 	=> $username,
							'zpass' 	=> sha1($password),
							'zemail'	=> $email
						));

						//Echo Success Message
						$successMsg = 'Congrats You Are Now Registerd User';

					}

				}

		} // End Else Request
	}// End If Request Method
?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span>
	</h1>

	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
		<div class="input-container">
			<input 
			class="form-control" 
			type="text" 
			name="username" 
			autocomplete="off" 
			placeholder="Username"
			required="required" />
		</div>

		<div class="input-container">
			<input 
			class="form-control" 
			type="password" 
			name="password" 
			autocomplete="new-password"
			placeholder="Password"
			required="required" />
		</div>

		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
	</form>
	<!-- End Login Form -->

	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

		<div class="input-container">
			<input 
			pattern=".{4,}"
			title="Username Must Be 4 Chars"
			class="form-control" 
			type="text" 
			name="username" 
			autocomplete="off" 
			placeholder="Type Your Username"
			required />
		</div>

		<div class="input-container">
			<input
			minlength="5"
			class="form-control" 
			type="password" 
			name="password" 
			autocomplete="new-password"
			placeholder="type Your Password"
			required />
		</div>

		<div class="input-container">
			<input
			minlength="5"
			class="form-control" 
			type="password" 
			name="password2" 
			autocomplete="new-password"
			placeholder="type a Password again"
			required />
		</div>

		<div class="input-container">
			<input 
			class="form-control" 
			type="email" 
			name="email"
			placeholder="Type a Valiad Email"
			required />
		</div>

		<input class="btn btn-primary btn-block" name="signup" type="submit" value="Signup">
	</form>
	<!-- End Signup Form -->

	<div class="the-error text-center">
		
	<?php 

		if (! empty($formErrors)) {

			foreach($formErrors as $error){

				echo $error . '<br>';
			}

		}

		if (isset($successMsg)) {

			echo '<div class="msg success">'. $successMsg .'</div>';

		}

	?>

	</div> <!-- End Div Error -->

</div> <!-- End Div Container -->


<?php
	include $tpl . 'footer.php'; 
	ob_end_flush();
?>