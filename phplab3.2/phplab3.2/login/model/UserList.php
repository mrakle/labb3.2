<?php

namespace login\model;


require_once("UserCredentials.php");
require_once("common/model/PHPFileStorage.php");

/**
 * represents All users in the system
 *

 */
class UserList {
	/**
	 * Temporary solution with only one user "Admin" in PHPFileStorage
 	 * You might want to use a database instead.
	 * @var \common\model\PHPFileStorage
	 */
	private $adminFile;

	/**
	 * We only have one user in the system right now.
	 * @var array of UserCredentials
	 */
	private $users;


	public function  __construct( ) {
		$this->users = array();
		
		$this->loadAdmin();
	}

	/**
	 * Do we have this user in this list?
	 * @throws  Exception if user provided is not in list
	 * @param  UserCredentials $fromClient
	 * @return UserCredentials from list
	 */
	public function findUser(UserCredentials $fromClient) {
		foreach($this->users as $user) {
			if ($user->isSame($fromClient) ) {
				\Debug::log("found User");
				return  $user;
			}
		}
		throw new \Exception("could not login, no matching user");
	}

	public function update(UserCredentials $changedUser) {
		//this user needs to be saved since temporary password changed
		$this->adminFile->writeItem($changedUser->getUserName(), $changedUser->toString());

		\Debug::log("wrote changed user to file", true, $changedUser);
		$this->users[$changedUser->getUserName()->__toString()] = $changedUser;
	}

	/**
	 * Temporary function to store "Admin" user in file "data/admin.php"
	 * If no file is found a new one is created.
	 * 
	 * @return [type] [description]
	 */
	private function loadAdmin() {
		$userTrue = true;
		$this->adminFile = new \common\model\PHPFileStorage("data/admin.php");
		try {
			//Read admin from file
			$adminUserString = $this->adminFile->readItem("Admin");
			$admin = UserCredentials::fromString($adminUserString);

		} catch (\Exception $e) {
			\Debug::log("Could not read file, creating new one", true, $e);
			
			
			/**
			 * TODO KOPPLA nya användare
			 */
			//Create a new user
			$userName = new UserName("Admin");               //***TODO MODDA VIDARE.
			$password = Password::fromCleartext("Password");
			$admin = UserCredentials::create( $userName, $password);
			$this->update($admin);
			$userTrue = true;
		}
		if($userTrue == true ){
			$conDatabase = mysqli_connect("exbladet.se.mysql","exbladet_se","123456","exbladet_se")OR die("DÖÖÖÖÖÖÖÖ");
		
			if(mysqli_connect_errno($conDatabase)){
				$this->errorMessageReg = "Misslyckades kontakt med databas!" . mysqli_connect_error();
			}
			$query = mysqli_query($conDatabase,"SELECT * FROM mytable")OR die("DÖÖÖÖÖÖÖÖ");;
			while($row_num = mysqli_fetch_array($query)){
				$dbUsername = $row_num[0];
				$dbPassword = $row_num[1];
				
				$userName = new UserName($dbUsername);               
				$password = Password::fromCleartext($dbPassword);
				$admin = UserCredentials::create($userName, $password);
				$this->update($admin);
			}
		}	
			

		$this->users[$admin->getUserName()->__toString()] = $admin;
	}
}