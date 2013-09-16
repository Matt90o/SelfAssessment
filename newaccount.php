<?php
	require_once("init.php");
	
	// Get all Master programs
	$programarray = R::getAll('select * from program');
	$TPL->assign('Program', $programarray);
	
	$errormessage = array();
	
	// Check to see if user has submitted the form
	if 	(isset($_POST['email'])) {
		
		// Next we check for errors in the form. We append each error to our $errormessage array. Our template takes care of displaying the errors in the correct format.
		// We start with checking for a valid email address
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			array_push($errormessage, 'The Email address you entered is incorrect.');
		} else {
			// If the Email address is valid, check if the email address is already registered.			
			$user = R::findOne('user','email = ?', array( $_POST['email']) );
			if ($user != NULL)
				array_push($errormessage, ERROR_EMAILREGISTERED);
		}
		
		// Check for a valid student ID
		if (strlen($_POST['studentID']) != 7) {
			array_push($errormessage,  ERROR_STUDENTIDINVALID);
		} else {
			// If the student ID is valid, check if the student id is already registered. 
			$user = R::findOne('user', 'studentid = ?', array( $_POST['studentID'] ));
			if ($user != NULL) {
				array_push($errormessage, ERROR_STUDENTIDREGISTERED);
				$TPL->assign('StudentIDvalue', $_POST['studentID']);
			}
		}
		// Check for a valid name
		if (strlen($_POST['firstname']) < 2) 
			array_push($errormessage, ERROR_FIRSTNAME);
		else
			$TPL->assign('Firstnamevalue', $_POST['firstname']);
		if (strlen($_POST['lastname']) < 2)
			array_push($errormessage, ERROR_LASTNAME);
		else 
			$TPL->assign('Lastnamevalue', $_POST['lastname']);
		
		// Check for a valid password
		if (strlen($_POST['password']) < 6) {
			array_push($errormessage, ERROR_MINPASSWORD);
		} else if (strcmp($_POST['password'], $_POST['password2']) != 0) {
			array_push($errormessage, ERROR_REPEATPASSWORD);
		}
		
		// Check if we have any errors. If not, we continue creating the user.
		if (empty($errormessage)) {
			$RB_user = R::dispense('user');
			$RB_user->firstname = $_POST['firstname'];
			$RB_user->lastname = $_POST['lastname'];
			$RB_user->studentid = $_POST['studentID'];
			$RB_user->password = md5($_POST['password']);
			$RB_user->email = $_POST['email'];
			$RB_user->usertype = UT_STUDENT;
			
			// We will insert the program as a program_id into the database. 
			foreach($programarray as $program) {
				if (strcmp($program['title'],$_POST['program']) == 0) {
					$RB_user->program = key($program) + 1;
				}
			}
			
			// Save the user to the database.
			R::store($RB_user);
			
			// Draw our creation succesfull account.
			$BODY = $TPL->draw('successful', $return_string = true);
			// And redirect after a couple of seconds
			header( "refresh:4;url='index.php'" );
		} else {
			// Assign our error message.
			$TPL->assign('Errormessage', $errormessage);
			// Assign the Email value last entered
			$TPL->assign('Emailvalue', $_POST['email']);
			// Draw the account creation again.
			$BODY = $TPL->draw('newaccount', $return_string = true);
		}
	} else {
		$BODY = $TPL->draw('newaccount', $return_string = true);
	}
	
	$HEADER = $TPL->draw('header', $return_string = true);
	generate_html();
	
	
?>