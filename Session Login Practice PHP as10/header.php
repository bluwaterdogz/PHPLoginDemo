	<header>
		<div id="leftheader"><a href="."><h1>CIS 33 Assignment 10: User authentication</h1></a></div>
		<div id="rightheader"><a href=".">Home</a>
		<?php 
		session_start();
	if(!isset($_SESSION["loggedin"])){ 
		echo ' | Log in:
				<form action="#" method="POST" id="userauth">
					<input type="text" size="20" name="loginusername" id="username" placeholder="Username">
					<input type="password" size="20" name="loginpassword" id="password" placeholder="Password">
					<button type="submit" name="action" value="login" id="login">Log In</button>
					<button type="submit" name="action" value="register">Register new user</button>';		
	}else{ 
		echo $_SESSION['message'].':
			<form action="#" method="POST" id="userauth">
				<button type="submit"  name="logout">Log Out</button>';
		}
						
		?>
			</form>
		</div>
		<div style="clear:both"></div>
	</header>
