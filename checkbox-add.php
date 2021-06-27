<?php
	include("config.inc.php");

	$stmt = $dbh->prepare("INSERT INTO checkboxes (id, task_id, name, state) VALUES (NULL, :task_id, :name, NULL)");
	$stmt->execute([':task_id' => $_POST['task_id'], ':name' => $_POST['name']]);

	
