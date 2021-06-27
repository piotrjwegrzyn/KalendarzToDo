<?php
	# chron job tego czegoś:
	# 0 8 * * * mail.php
	# o 08:00 rano każdego dnia

	include("config.inc.php");
	include('mail_password.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;
	require 'PHPMailer/Exception.php';
	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/SMTP.php';

	$stmt = $dbh->prepare("SELECT * FROM users");
	$stmt->execute();
	while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$anytask = 0;

		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '465';
		$mail->isHTML();
		$mail->Username = 'todo.calendars@gmail.com';
		$mail->Password = $mail_password;
		$mail->SetFrom('todo.calendars@gmail.com');
		$mail->Subject = 'Daily task notification';
		$mail->AddAddress($user['email']);

		$template = '
<html>
	<body>
		<span style="font-weight: bold;">Your tasks for today:</span>
		<ul>

		';
		$stmt_task = $dbh->prepare("SELECT * FROM tasks WHERE (
		    (user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id))
		    AND ((YEARWEEK(begin_time) = YEARWEEK(NOW())
		            AND WEEKDAY(begin_time) = WEEKDAY(NOW()))
		        OR ((YEARWEEK(end_time) = YEARWEEK(NOW())
			        AND WEEKDAY(end_time) = WEEKDAY(NOW()))))
		) ORDER BY begin_time ASC");
		$stmt_task->execute([':user_id' => $user['id']]);
		while ($task = $stmt_task->fetch(PDO::FETCH_ASSOC)) {
			$anytask = 1;
			$template = $template . '
			<li>
				<p>'.$task['name'].'</p>
					<ul>
			';

			$stmt_checkbox = $dbh->prepare("SELECT * FROM checkboxes WHERE task_id = :task_id");
			$stmt_checkbox->execute([':task_id' => $task['id']]);
			while ($checkbox = $stmt_checkbox->fetch(PDO::FETCH_ASSOC)) {
				$template = $template . '
						<li>'.$checkbox['name'].'</li>
				';
			}
			$template = $template . '
					</ul>
				</li>
			';
		}
		$template = $template . '
		</ul>
	</body>
</html>
		';
		if ($anytask) {
			$mail->Body = $template;
			$mail->Send();
			print 'sent</br>';
		}
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
