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
	
	if($RB_user->id && $RB_user->usertype == UT_MODERATOR) {
		
		if(isset($_GET['logout'])) {
			$RB_user->logout();
		} else {
			$TPL->assign('Username', $RB_user->firstname . " " . $RB_user->lastname);
			$TPL->assign('Usertype', $RB_user->usertype);
			$TPL->assign('LoggedIn', true);
			
			if(isset($_GET['StudentID'])) {
				$TPL->assign('StudentID',$_GET['StudentID']);
				$RB_student = R::findOne('user', 'studentid = ?', array($_GET['StudentID']));
				// Student is chosen to display. Build the webtool for the student.
				// TODO: Check if student exists!
				if($RB_user->id){
					if (isset($_POST['submitmoderator'])) {
						// Update database
						$pending_item = R::find('item', 'userid = :userid AND status = :status AND competenceid = :competenceid',
						 array(':userid' => $RB_student->id, ':status' => STATUS_PENDING, ':competenceid' => $_POST['competenceid']));
						 
						foreach($pending_item as $current_item) {
							$current_item->status = STATUS_APPROVED;
							R::store($current_item);
						}
					} 
					// We generate our full webtool. A very large portion of the code is present in this function.
					// It fills in our global variables needed to display the webtool.
					generate_webtool($_GET['StudentID']);
				}
			} else {
				$TPL->assign('StudentID', null);
				// Get a list of all students
				$RB_student = R::find('user', 'usertype = ?', array('student'));
				$studentarray = array();
				foreach( $RB_student as $student) {
					$RB_program = R::findOne('program', 'id = ?', array($student->program));
					
					$students[] = array( 'StudentID' => $student->studentid,
										 'Name' => $student->firstname . " " . $student->lastname,
										 'Email' => $student->email,
										 'Program' => (string)$RB_program->title);
				}
				$TPL->assign('Students', $students);
			}
			// We build our body in the main template from our /templates folder.
			$HEADER = $TPL->draw('header', $return_string = true);
			$BODY = $TPL->draw('template', $return_string = true);
			$FOOTER = $TPL->draw('footer', $return_string = true);
		}	
	} else {
		header('Location: index.php');
	}
	
	generate_html();
	R::close();
?>