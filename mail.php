<?php
	# chron job tego czegoś:
	# 0 8 * * * mail.php
	# o 08:00 rano każdego dnia

	include("config.inc.php");

	$stmt = $dbh->prepare("SELECT * FROM users");
	$stmt->execute();
	while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$template = '
<html>
	<body>
		<span style="font-weight: bold;">Your tasks for today:</span>
		<ul>

		';
		$stmt = $dbh->prepare("SELECT * FROM tasks WHERE (
		    (user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id))
		    AND ((YEARWEEK(begin_time) = YEARWEEK(NOW())
		            AND WEEKDAY(begin_time) = WEEKDAY(NOW()))
		        OR ((YEARWEEK(end_time) = YEARWEEK(NOW())
			            AND WEEKDAY(end_time) = WEEKDAY(NOW()))))
		) ORDER BY begin_time ASC");
		$stmt->execute([':user_id' => $user['id']]);
		while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$template = $template + '
			<li>
				<p>'.$task['name'].'</p>
					<ul>
			';

			$stmt = $dbh->prepare("SELECT * FROM checkboxes WHERE task_id = :task_id");
			$stmt->execute([':task_id' => $task['id']]);
			while ($checkbox = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$template = $template + '
						<li>'.$checkbox['name'].'</li>
				';
			}
			$template = $template + '
					</ul>
				</li>
			';
		}
		$template = $template + '
		</ul>
	</body>
</html>
		';

		mail($user['email'], 'Daily task notification', $template);
	}
?>

<!-- <html>
	<body>
		<span style="font-weight: bold;">Your tasks for today:</span>
		<ul>
		    <li>
		        <a>1st task's name</a>
		        <ul>
		            <li>checkbox a</li>
		            <li>checkbox b</li>
		            <li>checkbox c</li>
		        </ul>
		    </li>
		    <li>
		        <a>2nd task's name</a>
		        <ul>
					<li>checkbox a</li>
		            <li>checkbox b</li>
		            <li>checkbox c</li>
		        </ul>
		    </li>
		    <li>
		        <p>3rd task's name</p>
				<ul>
					<li>checkbox a</li>
		            <li>checkbox b</li>
		            <li>checkbox c</li>
		        </ul>
		    </li>
		</ul>
	</body>
</html> -->
