<?php
	// Initialization of frameworks used, global variables, database etc.
	require_once("init.php");
	
	/*
	 * Get user information from the session.
	 */
	if (isset($_SESSION['userID'])) {
		$RB_user = R::load('user', $_SESSION['userID']);
	} else {
		// Check if the login form has been filled in, if so check if we find a record in the database.
		if (isset($_POST['loginsubmit'])) {
			$RB_user = R::findOne( 'user','email = :email AND password = :password', array(':email' => $_POST['email'], ':password' => md5($_POST['password'])) );
			if ($RB_user != NULL) {
				$RB_user->login();
			} else {
				$TPL->assign('Errormessage', ERROR_EMAILPASSWORD);
				$TPL->assign('Emailvalue', $_POST['email']);
				$RB_user = R::dispense('user');
			}
		} 
	}
	// Check if the user tried to log out
	if(isset($_GET['logout']))
		$RB_user->logout();
	
	/*
	 * Generation of web page to be displayed
	 */
	// Check if the RedBean Object has an ID. If so, the user has been retrieved from the database and is logged in.
	if($RB_user->id) {
		// We generate our full webtool. A very large portion of the code is present in this function.
		// It fills in our global variables needed to display the webtool.
		$TPL->assign('LoggedIn', true);
		
		// Check if user has submitted the webtool.
		if (isset($_POST['submitstudent'])) {
				
			if (!isset($_POST['itemproof'])) {
				$errormessage = "Please enter itemproofs to ";
			}
			// Update database
			foreach($_POST['itemproof'] as $prooflevel => $proof) {
				$RB_item = R::dispense('item');
				$RB_item->userid = $RB_user->id;
				$RB_item->competenceid = $_POST['competenceid'];
				$RB_item->itemlevel = $_POST['itemlevel'];
				$RB_item->status = STATUS_PENDING;
				$RB_item->timestamp = R::isoDate();
				$RB_item->itemproof = $prooflevel;
				R::store($RB_item);
			}
			//$RB_user
		}
		
		// TODO: Check to see if user is supervisor. If so, present the user with the administration form. 
		// In administration form the administrator can call the generate_webtool function for every student. 
		generate_webtool($RB_user->id);
		
		// We build our body in the main template from our /templates folder.
		$HEADER = $TPL->draw('header', $return_string = true);
		$BODY = $TPL->draw('template', $return_string = true);
		$FOOTER = $TPL->draw('footer', $return_string = true);
				
		
		
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