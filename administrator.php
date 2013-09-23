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
	
	if($RB_user->id && $RB_user->usertype == UT_ADMINISTRATOR) {
		if(isset($_POST["submitsupervisor"]))
			R::exec('UPDATE item SET status = :new_status WHERE userid = :user_id AND status = :current_status', array(':user_id' => $RB_user->id, ':current_status' => STATUS_PENDING, ':new_status' => STATUS_APPROVED));
		
	}
?>