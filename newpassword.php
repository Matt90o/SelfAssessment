<?php
	require_once("init.php");
	
	// Global RedBeanPHP Variables
	$RB_user = R::dispense('user');
	
	$HEADER = $TPL->draw('header', $return_string = true);
    $BODY = $TPL->draw('newpassword', $return_string = true);
	
	generate_html();
?>