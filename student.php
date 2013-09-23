<?php

	// Initialization of frameworks used, global variables, database etc.
	require_once("init.php");
	
	/*
	 * Get user information from the session.
	 */
	if (isset($_SESSION['userID'])) {
		$RB_user = R::load('user', $_SESSION['userID']);
	} else {
		header('Location: index.php');
	}
	// Check if the user tried to log out
	if(isset($_GET['logout']))
		$RB_user->logout();
	
	// Check if the RedBean Object has an ID. If so, the user has been retrieved from the database and is logged in.
	if($RB_user->id) {
				
		$TPL->assign('LoggedIn', true);
		
		// Check if user has submitted the webtool.
		if (isset($_POST['submitstudent'])) {
				
			if (!isset($_POST['itemproof'])) {
				$errormessage = "Please enter itemproofs to submit.";
			} else {
				// Update database
				var_dump($_POST);
			/*
				foreach($_POST['itemproof'] as $prooflevel => $proof) {
					$RB_item = R::dispense('item');
					$RB_item->userid = $RB_user->id;
					$RB_item->competenceid = $_POST['competenceid'];
					$RB_item->status = STATUS_PENDING; 
					 
					$RB_item->timestamp = R::isoDate();
					$RB_item->itemproof = $prooflevel;
					R::store($RB_item);
				}
			*/
			}
		} elseif(isset($_POST["submitsupervisor"]))
			R::exec('UPDATE item SET status = :new_status WHERE userid = :user_id AND status = :current_status', array(':user_id' => $RB_user->id, ':current_status' => STATUS_PENDING, ':new_status' => STATUS_APPROVED));
		
		// We generate our full webtool. A very large portion of the code is present in this function.
		// It fills in our global variables needed to display the webtool.
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