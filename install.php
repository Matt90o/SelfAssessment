<html>
	<body>
	<title>Setup</title>
	<h2>Please fill in your database variables:</h2>
	<form method="POST">
		Database Host: <input type="text" name="host" value="localhost"></br>
		Database User: <input type="text" name="username" value="root"></br>
		Database Password: <input type="text" name="password"></br>
		Database Name: <input type="text" name="databasename" value="selfassessment"></br>
		<input type="submit" name="submit" value="Submit">
	</form>

		
<?php
	if (isset($_POST['submit'])) {
		// Create connection
		$con = mysqli_connect($_POST['host'],$_POST['username'],$_POST['password'],$_POST['databasename']);

		// Check connection
		if (mysqli_connect_errno($con)) {
			echo '<p>Failed to connect to MySQL: ' . mysqli_connect_error() . '</p>';
		} else {
			// Set database defines.
			
			$filename = 'inc/define.php';
			$handle = fopen($filename, 'rw');
			$handle2 = fopen('inc/define2.php', 'w');
			
			if ($handle) {
				while (($buffer = fgets($handle)) !== false) {
					if ( strstr($buffer, "define('DB_HOST'") ) {
						fwrite($handle2, "\tdefine('DB_HOST', '" . $_POST['host']  . "');\n");
					} elseif (strstr($buffer, "define('DB_USER'") ) {
						fwrite($handle2, "\tdefine('DB_USER', '" . $_POST['username']  . "');\n");
					} elseif (strstr($buffer, "define('DB_PASSWORD'") ) {	
						fwrite($handle2, "\tdefine('DB_PASSWORD', '" . $_POST['password']  . "');\n");
					} elseif (strstr($buffer,"define('DB_NAME'") ) {
						fwrite($handle2, "\tdefine('DB_NAME', '" . $_POST['databasename']  . "');\n");
					} else {
						fwrite($handle2, $buffer);
					}
				}
				
				fclose($handle);
				fclose($handle2);
			}
			
			rename('inc/define2.php', 'inc/define.php');
			
			
			mysqli_query($con, "CREATE TABLE IF NOT EXISTS `program` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `title` varchar(70) NOT NULL,
								  `xmlpath` varchar(70) NOT NULL,
								  PRIMARY KEY (`id`)
								)  ");
			
			mysqli_close($con);
			
			echo '<p>Setup for the Webtool was succesfull. Redirecting you to the webtool.</p>';
	//		header('Refresh: 5; url=index.php');
		}
	}
?>
	</body>
	
</html>
