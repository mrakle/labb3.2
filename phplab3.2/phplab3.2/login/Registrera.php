<?php
    namespace login;
   
    $start = new Registrera();
	
    class Registrera{
    
	
	public $errorMessageReg;
	
	
    public function __construct(){
    	
		$this->regStart();
    }
	
	public function regStart(){
			
		
		if($this->isNewMemberValid()){
			$this->connectDB();
		
		}
			$this->regHtmlForm();
	}
	
	public function isNewMemberValid(){
		
		if($_POST){
			
			$newUserName = $_POST['newUserName'];
			$newPassword = $_POST['newPassword'];
			$repitNewPassword = $_POST['repitNewPassword'];
			
			
			if($newUserName!=strip_tags($newUserName)){
			echo "Du får inte använda otillåtna tecken!";
				return false;
			}
			
			if(strlen($_POST['newUserName'])<3 && strlen($_POST['newPassword'])<6){
			
				$this->errorMessageReg = "Användarnamnet har för få tecken. Minst 3 tecken";
				$this->errorMessageReg .= "Lösenorden har för få tecken. Minst 6 tecken";
				return false;
			}
			
			if(strlen($_POST['newUserName'])<3){
				
				$this->errorMessageReg = "Användarnamnet har för få tecken. Minst 3 tecken";
				return false;
			}
			elseif(strlen($_POST['newPassword'])<6){
				$this->errorMessageReg = "Lösenorden har för få tecken. Minst 6 tecken";
				return false;
			}
			
			if($_POST['newPassword'] == $_POST['repitNewPassword']){
				$this->errorMessageReg .= "RÄTT";
				
			}else{
				$this->errorMessageReg = "FEL";
				return false;
			}
			
			return true;
			
		}
		
	}
	
    public function connectDB(){
    	
		$newUserName = $_POST['newUserName'];
		$newPassword = $_POST['newPassword'];
		$itsOK =true;
		
		/*if(get_magic_quotes_gpc()){
			$newUserName = stripslashes($newUserName);
			$newPassword = stripslashes($newPassword);
		}
		$newUserName = mysql_real_escape_string($newPassword);
		$newPassword = mysql_real_escape_string($newPassword);*/
		
    	$conDatabase = mysqli_connect("exbladet.se.mysql","exbladet_se","123456","exbladet_se");
		
		if(mysqli_connect_errno($conDatabase)){
			$this->errorMessageReg = "Misslyckades kontakt med databas!" . mysqli_connect_error();
		}
		
		
		
		//***FINNS ANVÄNDARE
		//$result = mysqli_query($conDatabase);
		$query = mysqli_query($conDatabase,"SELECT * FROM mytable");
		
			while($row_num = mysqli_fetch_array($query)){
				
				if($newUserName == $row_num[0] || $newUserName == 'Admin'){
					 echo "Användare finns redan!!!";
					$itsOK = false;
					break;
				}
				
			}
			
			
		//($query)>0)
		
		if($itsOK == true){
			mysqli_query($conDatabase,"INSERT INTO mytable (username, password)
			VALUES ('$newUserName', '$newPassword')");
			echo "Grattis nu är nu medlem!";
		}
			
			mysqli_close($conDatabase);
    }
	
    public function regHtmlForm(){
    	 setlocale(LC_ALL, "sv_SE", "sv_SE.utf-8", "sv", "swedish"); 
		
			/**
			 * Sparar användarnamn
			 * Tar bort taggar
			 */
		  $usernameSave = null; 
                                if(isset($_POST['newUserName'])){
                                        $usernameSave =strip_tags($_POST['newUserName']);
                                }
		
	    $NewMemberHTML = "
		 <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"> 
	        <html xmlns=\"http://www.w3.org/1999/xhtml\"> 
	           <meta http-equiv='content-type' content='text/html; charset=utf-8' />
	          <body>
	         
	            <h1>Registrera Användare</h1>
				$this->errorMessageReg	  	
				<form action='?login' method='post' enctype='multipart/form-data'>
				 <a href='../index.php'>Tillbaka till framtiden</a>
					<fieldset>
						
						<legend>Ny användare - Välj användarnamn och lösenord</legend>
						<label for='' >Användarnamn :</label>
						<input type='text' size='20' name='newUserName' id='regUserNameID' value='".$usernameSave."' />
						<label for='regPasswordID' >Lösenord  :</label>
						<input type='password' size='20' name='newPassword' id='PasswordID' value='' />
						<label for='RepeteRegPasswordID' >Repitera Lösenord  :</label>
						<input type='password' size='20' name='repitNewPassword' id='repPasswordID' value='' />
						
						<input type='submit' name='regButton'  value='Registrera' />
					</fieldset>
				</form>
				<p>".strftime('%A, den %d %B år %Y. Klockan är: [%H:%M:%S] ')."<p>
	          </body>
	        </html>";
			
		echo $NewMemberHTML;
			
		}
	}
	
	
	
	/**
	 * TODO ANTÄCKNINGAR
	 * 
	 * TODO Logga in med nyregistrerad användare.. UserList.php
	 * ex... if(...)?Admin:newUserName
	 */
	 