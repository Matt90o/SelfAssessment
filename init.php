<?php

	/*
	*
	*	Initialization and including of necessary files	
	*
	*/
	
	// Including all necessary frameworks and custom files.
	include "/inc/define.php";
	include "/inc/rain.tpl.class.php";
	include "/inc/rb.php";
	include "/inc/model.php";
	include "/inc/functions.php";
	
	/*
	 * RainTPL Configuration
	 */
	raintpl::configure("base_url", null );
	raintpl::configure("tpl_dir", "templates/" );
	raintpl::configure("cache_dir", "tmp/" );
	$TPL = new RainTPL;
	$TPL->assign('Errormessage', '');
	$TPL->assign('Emailvalue', '');
	$TPL->assign('StudentIDvalue', '');
	$TPL->assign('Firstnamevalue', '');
	$TPL->assign('Lastnamevalue', '');
	$TPL->assign('LoggedIn', false);
	$TPL->assign('Javascript', '');
	$TPL->assign('Version', VERSION_NO);
	
	/*
	 * RedBeanPHP Configuration
	 */
	R::setup('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
	$RB_session = R::dispense('session');
	$RB_user = R::dispense('user');
	
	/*
	 * Global Variables
	 */
	$HEADER = '';
	$BODY = '';
	$FOOTER = '';
	
?>