<?php
require_once "config.php";
if (isset($_POST['submit'])) {
	$query = "DELETE FROM appointments WHERE id = " . mysqli_escape_string($con, $_POST['id']);
	mysqli_query($con, $query)
	or die ('Error: '.mysqli_error($con));
	//Close connection
	mysqli_close($con);
	//Redirect to homepage after deletion & exit script
	header("Location: overview.php");
	exit;
} else if(isset($_GET['id'])) {
	$appointmentId = $_GET['id'];
	$query = "SELECT * FROM appointments WHERE id = " . mysqli_escape_string($con, $appointmentId);
	$result = mysqli_query($con, $query) or die ('Error: ' . $query );
	if(mysqli_num_rows($result) == 1)
	{
		$appointments = mysqli_fetch_assoc($result);
	}
	else {
		header('Location: overview.php');
		exit;
	}
} else {
	header('Location: overview.php');
	exit;
}
?><!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
	<h2>Verwijder de reservering: <?=
        htmlspecialchars($appointments['id'])
        ?></h2>
	<form action="" method="post">
		<p>
			Weet je zeker dat je deze reservering wil verwijderen?
		</p>
		<input type="hidden" name="id" value="<?= htmlspecialchars($appointments['id']) ?>"/>
		<input type="submit" name="submit" value="Verwijderen"/>
		<br>
		<br>
		<a href="overview.php">Terug naar de aanvragen</a>
	</form>
</body>
</html>