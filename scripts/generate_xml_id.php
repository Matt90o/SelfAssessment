<?php

	/*
	 *	This script generates ID's for all of the Competence Levels and ID's for all the Competence Proofs
	 *	This is needed to map the Levels and Proofs to a Student Account without importing all of the XML into an SQL Database
	 */

	$xml = simplexml_load_file("xml/halfcomp.xml");
	
	var_dump($xml);
	
	foreach($xml->compAreas->compArea as $compArea) {
		foreach($compArea->comps->comp as $comp) {
			foreach($comp->items->item as $item) {
				$level = 0;
				foreach($item->itemProofs->itemProof as $itemProof) {
					// TODO: If item already has attribute, do not add attribute
					$itemProof->addAttribute('ProofID', $level);
					$level++;
				}
			}
		}
	}
	$file = 'halfcompID.xml';
	file_put_contents($file, $xml->asXML());
	echo $xml->asXML();
?>