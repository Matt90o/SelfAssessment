<?php
	
	/*
	 * Database related defines
	 */
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'selfassessment');

	/*
	 * Version Number
	 */
	define('VERSION_NO','v0.2');
	
	/*
	 * Error messages
	 */
	define('ERROR_EMAILPASSWORD','The Email address or password you entered is incorrect. <small><a href="newpassword.php">Forgot your password?</a></small>');
	define('ERROR_EMAILREGISTERED', 'The Email address you entered is already registered. <small><a href="newpassword.php">Forgot your password?</a></small>');
	define('ERROR_STUDENTIDINVALID','The Student ID you entered is invalid.');
	define('ERROR_STUDENTIDREGISTERED', 'The Student ID you entered is already registered. <small><a href="newpassword.php">Forgot your password?</a></small>');
	define('ERROR_MINPASSWORD','The chosen password needs to be at least 6 characters long.');
	define('ERROR_REPEATPASSWORD','The passwords do not match.');
	define('ERROR_FIRSTNAME','The first name you entered is invalid.');
	define('ERROR_LASTNAME','The last name you entered is invalid.');
	
	/*
	 * Item Statuses
	 */
	 define('STATUS_DEFAULT', 0);
	 define('STATUS_PENDING', 1);
	 define('STATUS_APPROVED', 2);
	 define('STATUS_DISABLED',3);
	 
	 /*
	  * User types (UT)
	  */
	  define('UT_STUDENT', 0);
	  define('UT_SUPERVISOR', 1);
	  define('UT_SUPERUSER', 2);
?>