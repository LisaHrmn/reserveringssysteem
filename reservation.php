<?php
require_once "config.php";
require_once "session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $con->prepare("INSERT INTO `appointments`(`user_id`, `a_date`, `begin_time`, `end_time`, `subjects`, `suggestions`)
            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $a_date, $begin_time, $end_time, $subjects, $suggestions);
    $user_id = 1; //mysqli_escape_string($con, $_POST['user_id']);
    $a_date = mysqli_escape_string($con, $_POST['a_date']);
    $begin_time = mysqli_escape_string($con, $_POST['begin_time']);
    $end_time = mysqli_escape_string($con, $_POST['end_time']);
    $subjects = mysqli_escape_string($con, $_POST['subjects']);
    $suggestions= mysqli_escape_string($con, $_POST['suggestions']);

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

	if (empty($errors)) {
		$stmt->execute();
		header('Location: succes.php');
		exit;
	} else {
		$errors[] = 'Something went wrong in your database query: ' . mysqli_error($con);
	}
}

if (isset($_POST['but_logout'])){
    session_start();
	session_destroy();
	header('Location: login.php');
}

?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Reservation</title>
		<link rel="stylesheet" href="css/style.css">
	</head>

	<body>
        <header id="header">
            <p>Adinda Bijles</p>
        </header>
        <div class="container">
		<h1>Bijles Reserveren</h1>
            <span class="errors"><?= isset($errors['user_id']) ? $errors['user_id'] : '' ?></span>

            <div class="reservation">
			    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				    <div class="date">
					    <label for="a_date">Datum:</label>
                        <span class="errors"><?= isset($errors['a_date']) ? $errors['a_date'] : '' ?></span>
					    <input type="date" name="a_date" id="a_date" <?= isset($a_date) ? $a_date : '' ?>>
					    <script>
					    	var today = new Date().toISOString().split('T')[0];
                            document.getElementsByName("a_date")[0].setAttribute('min', today);
                        </script>
				    </div>

				    <div class="time">
					    <label for="begin_time">Begin tijd:</label>
                        <span class="errors"><?= isset($errors['begin_time']) ? $errors['begin_time'] : '' ?></span>
					    <input type="time" name="begin_time" id="begin_time" value="<?= isset($begin_time) ? $begin_time : '' ?>">
				    </div>

				    <div class="time">
					    <label for="end_time">Eind tijd:</label>
                        <span class="errors"><?= isset($errors['end_time']) ? $errors['end_time'] : '' ?></span>
					    <input type="time" name="end_time" id="end_time" value="<?= isset($end_time) ? $end_time : '' ?>">
				    </div>

				    <div class="text">
					    <label for="subjects">Vakken:</label>
                        <span class="errors"><?= isset($errors['subjects']) ? $errors['subjects'] : '' ?></span>
					    <input type="text" name="subjects" id="subjects">
				    </div>

				    <div class="text">
					    <label for="suggestions">Suggesties/opmerkingen:</label>
                        <textarea name="suggestions" id="suggestions" rows="5"></textarea>
				    </div>
            </div>

				<div class="submit reserveer">
					<input type="submit" name="submit" value="Reserveer!">
				</div>
			    </form>

            <a href="overview.php">Bekijk de al bestaande afspraken</a>

            <div class="back">
                <form method='post' action="">
                    <input type="submit" value="uitloggen" name="but_logout">
                </form>
            </div>
		</div>
	</body>

</html>