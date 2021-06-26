<?php
	include("config.inc.php");

	$stmt = $dbh->prepare("UPDATE checkboxes SET state = :state WHERE id = :checkbox_id");
	$stmt->execute([':state' => $_POST['state'], ':checkbox_id' => $_POST['checkbox_id']]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($result) {
		echo 1;
	} else {
		exit();
	}
