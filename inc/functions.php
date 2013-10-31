<?php
	function generate_webtool($studentID, $lastpage = '') 
	{	
		// Define our global variables which our generate_template function will affect.
		global 	$TPL, $HEADER, $BODY, $FOOTER, $JAVASCRIPT;
		
		// Variables used by the generate_webtool function
		$LastPage = array();	
		
		// Load student and program from database. Based on these two factors the webtool is generated
		$RB_student = R::findOne('user','studentid = ?', array($studentID));
		$RB_program = $RB_student->program;
		$User_Items = $RB_student->ownUserItem;
	
		$CompAreaCounter = 1;
		// Our outer loop consists of looping through the Competence Area's
		foreach($RB_program->sharedComparea as $comparea) 
		{
			$total_items = 0;
			$total_pending_items = 0;
			$total_approved_items = 0;
			$pages = array();
			$CompetenceCounter = 1;
			$CompAreaTag = generate_tag((string)$comparea->title);
			
			// We loop through each competence, and get our information from these loops
			foreach($comparea->sharedCompetence as $comp) 
			{
				$items = array();
				// In this loop all of our items will be imported into the array.
				foreach($comp->sharedItem as $item) 
				{
					$Status = STATUS_DEFAULT;
					$ItemValue = OPTION_NA;
					// Now we get the competence levels and their descriptions...
					// Now we get our Item Levels in the correct order. We also have to check whether the student has checked this item and retrieve that from the database.
					foreach ($User_Items as $user_item) {
						if (strcmp($user_item->item_id,(string)$item->id) == 0) {
							$Status = $user_item->status;
							$ItemValue = $user_item->value;
							break;
						} else {
							$Status = STATUS_DEFAULT;
						}
					}
					// We put all of these descriptions in one array, which we can access in our template file.
					////////////////////////////////////////////////////////////////////////////////////////////////////////TODO: TOOLTIPS IN DESCRIPTION!!!!
					$items[] = array( "ItemID"			=> $item->id,
									  "ItemDescription" => $item->description,
									  "ItemStatus"		=> $Status,
									  "ItemValue"		=> $ItemValue );
					$total_items++;
				}
				// Now we have all of our page data, let's put this in our pages[] array alongside with descriptions belonging to this loop.
				$CompetenceTag = $CompAreaTag . $CompetenceCounter;					
				$pages[] = array(
								"CompetenceTag" => $CompetenceTag,
								"CompetenceCounter" => $CompetenceCounter,
								"CompetenceTitle" => (string)$comp->title,
								"CompetenceDescription" => (string)$comp->description,
								"Items" => $items
				);
				// Now we set our last page visited. Initially this is our first Competence.
				/////////////////////////////////////////////////////////////////////////////////////////////////////////TODO: LASTPAGE FIXX!!!!
				if ($CompetenceCounter == 1) {
					$LastPage[$CompAreaTag] = 1;
				}
				// And we up the counter.
				$MaxPages[$CompAreaTag] = $CompetenceCounter;
				$CompetenceCounter++;
			}
			// Progress bar per competence area.
			$progress['pending'] = round($total_pending_items / $total_items * 100);
			$progress['approved'] = round($total_approved_items / $total_items * 100);
			$progress['na'] = round( ($total_items-$total_approved_items-$total_pending_items) / $total_items * 100);
			
			// This is the complete array with all of our data. 
			$CompetenceAreas[] = array( 
				"CompAreaTag" => $CompAreaTag,
				"CompAreaCounter" => $CompAreaCounter,
				"CompAreaTitle" => (string)$comparea->title,
				"CompAreaDescription" => (string)$comparea->description,
				"CompProgress" => $progress,
				"Pages" => $pages
			);
			$CompAreaCounter++;
		}

		// We assign this array to our $TPL object. We will loop through this array in our template file.
		$TPL->assign('NavigationBar', $navBar);
		$TPL->assign('CompAreas', $CompetenceAreas);
				
		// Here we build our javascript and encode our php variables to javascript variables using json_encode.
		$javascript = "var LastPageArray = " . json_encode($LastPage) . "\n";
		$javascript .= "var MaxPageArray = " . json_encode($MaxPages) . "\n";
		$TPL->assign('Javascript', $javascript);
	}

	function generate_html() 
	{
		global $TPL, $HEADER, $BODY, $FOOTER;
		echo $HEADER . $BODY . $FOOTER;
	}
	
	function generate_tag($InputString)
	{
		$tag = explode(" ", (string)$InputString);
		foreach($tag as $tag_explode)	$OutputString .= strtolower($tag_explode);
		return $OutputString;
	}
?>