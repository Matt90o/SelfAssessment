<?php

	require_once("init.php");
	
	$user = R::dispense('user');
	
	$user->email = "john@doe.com";
	$user->password = md5("test");
	$user->student_id = 0658642;
	$user->firstname = "John";
	$user->lastname = "van Doe";
	$user->program = "Electrical Engineering";
	$user->supervisor_id = 0;
	
	$id = R::store($user);
	
?>