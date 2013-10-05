<?php
	function generate_webtool($userID) 
	{
		//TODO: Add tooltip functionality!
		
		// Define our global variables which our generate_template function will affect.
		global 	$TPL, $HEADER, $BODY, $FOOTER, $JAVASCRIPT;
		
		// Variables used by the generate_template function
		$tag = '';
		$newtag = '';
		$LastPage = array();	
		
		// Load user from database.
		$user = R::findOne('user','studentid = ?', array($userID));

		
		/*
		 *
		 *	Importing XML table & assigning our rainTPL objects
		 *
		 *	XML syntax is very important, because we are going to import the xml file as a SimpleXMLElement object.
		 *	With errors in our .xml, our simplexml_load_file function will throw a lot of errors and the page will not load correctly!
		 *	
		 *	Please use http://validator.w3.org/check to validate .xml files before calling the simplexml_load_file function!
		 *
		 */ 
		 
		 // Get the correct xml to load (depending on the program the user has registered for)
		$program = R::load('program', $user->program);
		$xml = simplexml_load_file($program->xmlpath);
		
		// Get all items belonging to the user, highest itemlevel first
		$RB_item = R::find('item', 'userid = :user_id', array(':user_id' => $user->id));

		// Let's build our Navigation bar first and put it inside an array with correct HTML mark-up.
		// We also build our tags here to identify each competence. We need this to correctly submit the form by the user.
		foreach($xml->compAreas->compArea as $compArea) 
		{ 
			$compAreaTitle = (string)$compArea->compAreaTitle;
			$tag = explode(" ", $compArea->compAreaTitle);
			$newtag = '';
			foreach($tag as $tag_explode)	$newtag .= strtolower($tag_explode);
			
			$tag = $newtag;
			$navBar[] = 
			array( 
				"Title" => (string)$compArea->compAreaTitle,
				"Caption" => (string)$compArea->compAreaCaption,
				"CompAreaID"   => $tag
			);
		}
		 
		// This giant loop gets all our info from the XML and builds the output. Our template handles the correct HTML mark-up.
		
		// Our outer loop consists of looping through the Competence Area's
		foreach($xml->compAreas->compArea as $compArea) 
		{
			// In our outer loop we get our Competence Area Title and we assign a tag to each Area.
			// We need this tag in order to identify the pagination for the correct Competence Area.
			$compAreaTitle = (string)$compArea->compAreaTitle;
			$tag = explode(" ", $compArea->compAreaTitle);
			$newtag = '';
			foreach($tag as $tag_explode)	$newtag .= strtolower($tag_explode);
			$tag = $newtag;
			$total_items = 0;
			$total_pending_items = 0;
			$total_approved_items = 0;
			$CompAreaCounter = 1;
			$pages = array();
			
			// We loop through each competence, and get our information from these loops
			foreach($compArea->comps->comp as $comp) 
			{
				$items = array();
				// In this loop all of our itemproofs and levels will be imported into the array.
				foreach($comp->items->item as $item) 
				{				
					// First we create an ID for each competence page. This ID is built from the Competence Area Tag and a counter for the page number.
					// This ID is also used to map the Student information to the correct Competences.
					$CompetenceID = $tag . $CompAreaCounter;
					
					// Now we get the competence levels and their descriptions...
					// Now we get our Item Levels in the correct order. We also have to check whether the student has checked this item and retrieve that from the database.
					$itemproofs = array();
					$ItemProofCounter = 0;
					$itemproofstatus = array();
					$highest = 0;
					$status = NULL;
										
					// Then we get our competence proofs and their descriptions...
					foreach($item->itemProofs->itemProof as $itemProof) 
					{
						if (!empty($RB_item)) {
							foreach($RB_item as $user_item) {
								if ( !(strcmp($user_item->competenceid, $CompetenceID) != 0) ) {
									if ( (int)$user_item->itemproof == $ItemProofCounter && (int)$user_item->itemlevel == $highest)  {
										$status = (int)$user_item->status;
										$itemvalue = (string)$user_item->itemvalue;
										break;
									} else {
										$status = STATUS_DEFAULT;
										$itemvalue = '';
									}	
								}
							}
						}
						if ($status == STATUS_PENDING)
							$total_pending_items++;
						else if ($status == STATUS_APPROVED)
							$total_approved_items++;
						$itemproofs[] = array(
											"ItemProofID" => (string)$ItemProofCounter,
											"ProofDescription" => (string)$itemProof,
											"Status" => $status,
											"Itemvalue" => $itemvalue);
						$ItemProofCounter++;
					}
					$total_items += $ItemProofCounter;
					
					// We put all of these descriptions in one array, which we can access in our template file.
					$items = array( "ItemProofs" => $itemproofs );
					$caption = 'caption';
					
					// Now we have all of our page data, let's put this in our pages[] array alongside with descriptions belonging to this loop.					
					$pages[] = array(
									"Counter" => $CompAreaCounter,
									"CompetenceID" => $CompetenceID,
									"ItemTitle" => (string)$item->itemTitle,
									"ItemCaption" => (string)$item->itemCaption,
									"CompTitle" => (string)$comp->compTitle,
									"LevelCaption" => (string)$item->itemLevels->attributes()->$caption,
									"ProofCaption" => (string)$item->itemProofs->attributes()->$caption,
									"BSc" => (string)$comp->compCaption->BSc,
									"MSc" => (string)$comp->compCaption->MSc,
									"Items" => $items
					);
					
					// Now we set our last page visited. Initially this is our first Competence.
					if ($CompAreaCounter == 1)
						$LastPage[$tag] = 1;
					// And we up the counter.
					$MaxPages[$tag] = $CompAreaCounter;
					$CompAreaCounter++;
				}
				
			}

			// TODO: Build progress bar
			$progress['pending'] = round($total_pending_items / $total_items * 100);
			$progress['approved'] = round($total_approved_items / $total_items * 100);
			$progress['na'] = round( ($total_items-$total_approved_items-$total_pending_items) / $total_items * 100);
			// This is the complete array with all of our data. 
			$competences[] = array( 
				"CompAreaTitle" => (string)$compArea->compAreaTitle,
				"CompAreaCaption" => (string)$compArea->compAreaCaption,
				"CompAreaID"  => $tag,
				"CompProgress" => $progress,
				"Pages" => $pages
			);
		}
	
		// We assign this array to our $TPL object. We will loop through this array in our template file.
		$TPL->assign('NavigationBar', $navBar);
		$TPL->assign('Competences', $competences);
				
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
?>