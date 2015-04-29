<?php 
	// make connection

	$dbHost ="localhost";
	$dbUser ="bmvelasquez";
	$dbPass ="E1r509YQPmqEHAsM";
	$dbName ="bmvelasquez";
	
	$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

	// Register new user
	if( isset($_POST['register']) ){
		if( strlen($_POST['username']) <= 64 ){		
			if(isset($_POST['password']) and strlen($_POST['password']) >= 2 ){
				if($_POST['password'] == $_POST['password_repeat']){

					$newPassword = htmlspecialchars($_POST["password"]);
					$newUsername = htmlspecialchars($_POST["username"]);

					$salt = substr(base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM)), 0, 16);
					$hashed = crypt($newPassword, '$6$'.$salt.'$');

					$ip = $_SERVER['REMOTE_ADDR'];
					$eventType = 'create';

					$stmt = $conn->prepare("INSERT INTO USER (Username, Password) VALUES (?,?)");
					$stmt->bind_param( 'ss' , $newUsername, $hashed);
					$stmt->execute();
					$stmt->close();	

					$query2 = "SELECT UserID FROM USER WHERE Username = \"$newUsername\"";
					$result = $conn->query($query2);
					$row = $result->fetch_array(MYSQLI_ASSOC);
					$userID = $row['UserID'];

					$stmt = $conn->prepare("INSERT INTO AuthLogEntry (IP, EventType, UserID) VALUES (INET_ATON(?),?,?)");
					$stmt->bind_param( 'sss' , $ip, $eventType, $userID);
					$stmt->execute();
					$stmt->close();	

					setcookie("registration_message" ,  "Account Created");
				}else{
					setcookie("registration_message" , "Passwords Do Not Match");
				}
			}else if (strlen($_POST['password']) > 0){
				setcookie("registration_message" ,  "Passwords must be more than 12 characters");
			}
		}else{
			setcookie("registration_message" , "Username cannot be larger than 64 characters");
		}
	}

	// Log in
	if( isset($_POST['loginpassword']) and isset($_POST['loginusername']) ){

		$loginUsername = htmlspecialchars($_POST["loginusername"]);
		$loginPassword = htmlspecialchars($_POST["loginpassword"]);

		$results = $conn->query("SELECT Password, UserID from USER WHERE Username = \"$loginUsername\"")->fetch_array(MYSQLI_ASSOC);
		$retrievedHashed = $results["Password"];
		$queriedLoginID = $results["UserID"];

		$explodedHashed = explode('$', $retrievedHashed);
		$algorithm = $explodedHashed[1];
		$salt = $explodedHashed[2];
		$loginPWHashed = crypt($loginPassword , "$".$algorithm."$".$salt."$");
		
		$test = $loginPWHashed == $retrievedHashed ? true : false;

		if($test){

			session_start();

			if(!isset($_SESSION["username"])){

				$_SESSION["username"] = $loginUsername;

			}
			if(!isset($_SESSION["loggedin"])){				
				$_SESSION["loggedin"] = true;
			}
			if(!isset($_SESSION["message"])){	
				$_SESSION["message"] = "Welcome Back ".$_SESSION["username"];
			}
		
			$ip = $_SERVER['REMOTE_ADDR']; 
			$eventTypeLogin = "login";

			$stmt = $conn->prepare("INSERT into AuthLogEntry (IP, UserID, EventType ) VALUES (INET_ATON(?) , ? , ?)");
				$stmt->bind_param('sss', $ip, $queriedLoginID , $eventTypeLogin);
				$stmt->execute();
				$stmt->close();

		}
	}

	if(isset($_POST['logout'])){
		// Query UserID for AuthLogEntry table $_SESSION["username"]
		session_start();
		$query3 = "SELECT UserID from USER WHERE Username =\"".$_SESSION["username"]."\"";
		$results3 = $conn->query($query3)->fetch_array(MYSQLI_ASSOC);
		$queriedLogoutID = $results3["UserID"];

		$ip = $_SERVER['REMOTE_ADDR']; 
		$eventTypeLogout = "logout";

		// Insert UserID into AuthLogEntry table
		$stmt = $conn->prepare("INSERT into AuthLogEntry (IP, UserID, EventType ) VALUES (INET_ATON(?) , ? , ?)");
			$stmt->bind_param('sss', $ip, $queriedLogoutID , $eventTypeLogout);
			$stmt->execute();
			$stmt->close();

		// End session

		$_SESSION = array();
		if(ini_get("session.use_cookies")){
			$params = session_get_cookie_params();
			setcookie(session_name() , "" , 1 , $params["path"],$params["domain"],$params["secure"],$params["httponly"]);
		}
		session_destroy();
	}
?>