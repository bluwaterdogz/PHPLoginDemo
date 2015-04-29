<?php include_once("databaseConnection.php"); ?>
<?php include_once("session.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="as10.css">
	<title>Assignment 10 Php</title>
</head>
<body>

	<?php include_once("header.php"); ?> 

	<header>
		<h2>Register as a new user</h2>
	</header>

	<form action="user.php" method="POST" id="newuserform">

			<label for="username">Username (Maximum 64 Characters)</label>
			<input type="text" size="50" maxlength="64" name="username" placeholder="Username">
			<label for="password">Password (Minimum 12 Characters)</label>

			<input type="password" size="50" name="password" placeholder="Password">
			<label for="repeat_password">Please Repeat Password</label>

			<input type="password" size="50" name="password_repeat" placeholder="Password">
			<button type="submit" name="register">Register</button>

	</form>

	<?php 
		if(isset($_COOKIE['registration_message'])){
			echo "<h1 style=\"color: blue\">".$_COOKIE['registration_message']."</h1>";

			setcookie("registration_message","");
		}
	?>

</body>
</html>