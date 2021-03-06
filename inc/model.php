<?php

	class Model_User extends RedBean_SimpleModel {
		
		public function dispense() 
		{
			
        }
		
		public function logout()
		{
			unset($_SESSION['userID']);
			header('Location: index.php');
		}
		
		public function login()
		{
			$_SESSION['userID'] = $this->bean->id;
		}
    
		public function getview()
		{
			return $this->bean->usertype;
		}
	}

    class Model_Session extends RedBean_SimpleModel {

        public function dispense() 
        {
        	$_SESSION['sessID'] = session_id();
			$this->bean->sessID = session_id();
			session_start();
        }
		
		public function delete() 
		{
			unset($_SESSION['sess_id']);
        	unset($_SESSION['userID']);
        	session_destroy();
		}
    }

?>