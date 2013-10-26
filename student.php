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
	
	if($RB_user->id && $RB_user->usertype == UT_STUDENT) {
		
		if(isset($_GET['logout'])) {
			$RB_user->logout();
		} else {
					
			// Assign the Student name to the template
			$TPL->assign('Username', $RB_user->firstname . " " . $RB_user->lastname);
			$TPL->assign('Usertype', $RB_user->usertype);
			$TPL->assign('LoggedIn', true);
			$competenceid = '';
			// Check if user has submitted the webtool.
			if (isset($_POST['submitstudent'])) {
				$competenceid = $_POST['competenceid'];
				// Update database			
				foreach($_POST['itemproof'] as $prooflevel => $proof) {
					if (strcmp($proof, OPTION_NA) != 0) {
						
						$item = R::findOne('item', 'userid = :userid AND competenceid = :competenceid AND itemproof = :prooflevel',
								array(':userid' => $RB_user->id, ':competenceid' => $competenceid, ':prooflevel' => $proof));
						if (!$item->id) {
							$RB_item = R::dispense('item');
							$RB_item->userid = $RB_user->id;
							$RB_item->competenceid = $competenceid;
							$RB_item->status = STATUS_PENDING; 
							$RB_item->itemvalue = $proof;
							$RB_item->timestamp = R::isoDate();
							$RB_item->itemproof = $prooflevel;
							R::store($RB_item);
						}
					}
				}
			
			}
			// We generate our full webtool. A very large portion of the code is present in this function.
			// It fills in our global variables needed to display the webtool.
			generate_webtool($RB_user->studentid, $competenceid);
			
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