<?php
require_once "config.php";

$query = "SELECT * FROM appointments";
$result = mysqli_query($con, $query);
$appointments = [];
while ($row = mysqli_fetch_assoc($result)) {
	$appointments[] = $row;
}
mysqli_close($con);



?>

<!doctype html>
<html lang="en">
<head>
	<title>overzicht</title>
	<meta charset="utf-8"/>
</head>
<body>
<a href="login.php">terug</a>
	<h1>afspraken:</h1>
	<table>
		<thead>
		<tr>
			<th>#</th>
			<th>user</th>
			<th>datum</th>
			<th>begin tijd</th>
			<th>eind tijd</th>
			<th>vakken</th>
			<th>suggesties</th>
			<th>edit</th>
			<th>delete</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($appointments as $appointment) { ?> <tr>
            <td><?= htmlspecialchars($appointment['id']) ?></td>
            <td><?= htmlspecialchars($appointment['user_id']) ?></td>
			<td><?= htmlspecialchars($appointment['a_date']) ?></td>
			<td><?= htmlspecialchars($appointment['begin_time']) ?></td>
			<td><?= htmlspecialchars($appointment['end_time']) ?></td>
			<td><?= htmlspecialchars($appointment['subjects']) ?></td>
			<td><?= htmlspecialchars($appointment['suggestions']) ?></td>
			<td><a href="edit.php?id=<?= htmlspecialchars($appointment['id']) ?>">Edit</a></td>
			<td><a href="delete.php?id=<?= htmlspecialchars($appointment['id']) ?>">Delete</a></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>
</body>
</html>
