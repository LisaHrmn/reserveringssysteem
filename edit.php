<?php
require_once "config.php";

//Check if Post isset, else do nothing
if (isset($_POST['submit'])) {
	$id = mysqli_escape_string($con, $_POST['id']);
	//Update the record in the database
	$stmt = $con->prepare("UPDATE appointments
            SET user_id = ?, a_date = ?, begin_time = ?, end_time = ?, subjects = ?
            WHERE id = ?");
	$stmt->bind_param("issssi", $user_id, $a_date, $begin_time, $end_time, $subjects, $id);
	$user_id = 1; //mysqli_escape_string($con, $_POST['user_id']);
	$a_date = mysqli_escape_string($con, $_POST['a_date']);
	$begin_time = mysqli_escape_string($con, $_POST['begin_time']);
	$end_time = mysqli_escape_string($con, $_POST['end_time']);
	$subjects = mysqli_escape_string($con, $_POST['subjects']);

//Check if data is valid & generate error if not so
	$errors = [];
	if ($user_id == "") {
		$errors['user_id'] = 'id mag niet leeg zijn';
	}
	if ($a_date == "") {
		$errors['a_date'] = 'datum mag niet leeg zijn';
	}
	if ($begin_time == "") {
		$errors['begin_time'] = 'begin tijd mag niet leeg zijn';
	}
	if ($end_time == "") {
		$errors['end_time'] = 'eid tijd mag niet leeg zijn';
	}
	if ($subjects == "") {
		$errors['subjects'] = 'vakken mag niet leeg zijn';
	}

	if (empty($errors))
	{
		$stmt->execute();
		header('Location: overview.php');
		Exit();
	}
}

else if(isset($_GET['id'])) {
	//Retrieve the GET parameter from the 'Super global'
	$appointmentId = $_GET['id'];
	//Get the record from the database result
	$query = "SELECT * FROM appointments WHERE id = " . mysqli_escape_string($con, $_GET['id']);
	$result = mysqli_query($con, $query) or die ('Error: ' . $query );
	if(mysqli_num_rows($result) == 1)
	{
		$appointment = mysqli_fetch_assoc($result);
	}
	else {
		// redirect when db returns no result
		header('Location: overview.php');
		exit;
	}
} else {
	header('Location: overview.php');
	exit;
}

if (isset($_POST['but_logout'])){
	session_destroy();
	header('Location: login.php');
}

//Close connection
mysqli_close($con);
?>

<!doctype html>
<html lang="en">
<head>
	<title>edit reservering</title>
	<meta charset="utf-8"/>
</head>
<body>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<div class="date">
				<label for="a_date">Datum:</label>
				<span class="errors"><?= isset($errors['a_date']) ? $errors['a_date'] : '' ?></span>
				<input type="date" name="a_date" id="a_date" value="<?= htmlspecialchars($appointment['a_date'])?>">
				<script>
                    var today = new Date().toISOString().split('T')[0];
                    document.getElementsByName("a_date")[0].setAttribute('min', today);
				</script>
			</div>

			<div class="time">
				<label for="begin_time">Begin tijd:</label>
				<span class="errors"><?= isset($errors['begin_time']) ? $errors['begin_time'] : '' ?></span>
				<input type="time" name="begin_time" id="begin_time" value="<?= htmlspecialchars($appointment['begin_time']) ?>">
			</div>

			<div class="time">
				<label for="end_time">Eind tijd:</label>
				<span class="errors"><?= isset($errors['end_time']) ? $errors['end_time'] : '' ?></span>
				<input type="time" name="end_time" id="end_time" value="<?= htmlspecialchars($appointment['end_time']) ?>">
			</div>

			<div class="text">
				<label for="subjects">Vakken:</label>
				<span class="errors"><?= isset($errors['subjects']) ? $errors['subjects'] : '' ?></span>
				<input type="text" name="subjects" id="subjects" value="<?= htmlspecialchars($appointment['subjects']) ?>">
			</div>

			<div class="edit">
				<input type="hidden" name="id" value="<?= htmlspecialchars($appointmentId)?>"/>
				<input type="submit" name="submit" value="Edit">
			</div>
		</form>

		<div class="back">
			<form method='post' action="">
				<input type="submit" value="uitloggen" name="but_logout">
			</form>
		</div>
	<br>
	<div>
		<a href="overview.php">Terug naar overzicht</a>
	</div>
</body>
</html>