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
				if($RB_user->id){
					if (isset($_POST['submit'])) {
						$competenceid = $_POST['competenceid'];
						foreach($_POST['item'] as $ItemID => $ItemValue) {
							
							$Student_Items = $RB_student->ownUserItem;
							foreach ($Student_Items as $item) {
								if (strcmp($item->item_id,(string)$ItemID) == 0) {
									// Student has this item
									$RB_item = $item;
									break;
								}
							}
							if (!$RB_item->id) {
								if (strcmp($RB_item->value, OPTION_NA) == 0) {
									R::trash($RB_item);
								} else {
									// Item is already in DB and checked as yes or no, so approve the item in the database with given status, yes or no.
									
								}
								// Save item to database
								$RB_item = R::load('item', $ItemID);
								$RB_item->link('user_item',	
								array('value' => $ItemValue, 
									  'status' => STATUS_PENDING,
									  'timestamp' => R::isoDate()))->user = $RB_user;
								R::store($RB_item);
							}
							
							// Remove this item from the database if it's NA and if it is in the database. Otherwise ignore all NA items.	
							if ($item->id) {
								if (strcmp($proof, OPTION_NA) == 0) {
									R::trash($item);
								} else {
									// Item is already in DB and checked as yes or no, so approve the item in the database with given status, yes or no.
									$item->itemvalue = $proof;
									$item->status = STATUS_APPROVED;
									R::store($item);
								}
							} else {
								// Item does not exist in database, check if it is a new item which has to be inserted, ignore if its NA.
								if (strcmp($proof, OPTION_NA) != 0) {
									$RB_item = R::dispense('item');
									$RB_item->userid = $RB_student->id;
									$RB_item->competenceid = $competenceid;
									$RB_item->status = STATUS_APPROVED; 
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
				// TODO: No students.
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