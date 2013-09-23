<?php
	// Initialization of includes, initialization of frameworks used, global variables, database etc.
	require_once("init.php");
	
	//  Get user information from the session.
	if (isset($_SESSION['userID'])) {
		$RB_user = R::load('user', $_SESSION['userID']);
	} else {
		// User not present in session: Check if the login form has been filled in, if so check if we find a record in the database.
		if (isset($_POST['loginsubmit'])) {
			// User tried to log in: Get user credentials by email or studentid.
			$RB_user = R::findOne('user', 'password = :password AND (email = :email OR studentid = :email)', 
							array(':email' => $_POST['email'], ':password' => md5($_POST['password'])));
			if ($RB_user != NULL) {
				$RB_user->login();
			} else {
				// User entered incorrect credentials
				$TPL->assign('Errormessage', ERROR_EMAILPASSWORD);
				$TPL->assign('Emailvalue', $_POST['email']);
				$RB_user = R::dispense('user');
			}
		} 
	}
	
	// Check if the RedBean Object has an ID. If so, the user has been retrieved from the database and is logged in.
	if($RB_user->id) {
		// Check if the user tried to log out, if not, Invoke the controller based on the usertype.	
		if(isset($_GET['logout'])) {
			$RB_user->logout();
		} else {
			$TPL->assign('Usertype', $RB_user->usertype);
			header('Location: ' . $RB_user->usertype . '.php');
		}
	} else {
		// Assign our Login template to our $TPL object
		$TPL->assign('Studentname', '');
		$HEADER = $TPL->draw('header', $return_string = true);
		$BODY = $TPL->draw('login', $return_string = true);
		$FOOTER = '';
	}
	generate_html();
	R::close();
?>



