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

	<section id="logs">

		<header>
			<h2>Event Log</h2>
		</header>

		<table id="logentries">
				<tr><th>Date/Time</th><th>Event</th></tr>

<?php 

	//Create table of Log history using query
	$results = $conn->query("SELECT UserID, DateTime, INET_NTOA(IP) as IP, EventType from AuthLogEntry ORDER BY DateTime");

 	while($row = $results->fetch_array(MYSQLI_ASSOC)){

	$results2 = $conn->query("SELECT Username from USER WHERE UserID = \"".$row["UserID"]."\" limit 1")->fetch_array(MYSQLI_ASSOC);

	?>
	<tr><td><?=$row["DateTime"]?></td>
	<td><b><?=$results2['Username']?></b> <?=$row["EventType"] == "login" ? "logged in ":($row["EventType"] == "create"? "created an account":"logged out")?> from <?=$row["IP"]?> at <?=gethostbyaddr($row["IP"])?></td></tr>
	<?php 
	}
	  ?>
		</table>

	</section>

<script>
function myFunction() {
    location.reload();
}
</script>
</body>

</html>