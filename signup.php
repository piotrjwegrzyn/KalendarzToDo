<?php
  if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

  # s119.labagh.pl?page=register
  function register_user($post, $config, $server, $dbh) {
  	if !($_POST['floatingInput'] && $_POST['floatingPassword'] && $_POST['floatingRetype'])
  		return '<span style="font-weight: bold; color: red;">Uzupełnij wszystkie pola!</span>';

  	if (!preg_match('/^[a-zA-Z0-9\-\_\.]+\@[a-zA-Z0-9\-\_\.]+\.[a-zA-Z]{2,5}$/D', $post['floatingInput']))
  		return '<span style="font-weight: bold; color: red;">Podaj poprawny adres email!</span>';

  	if ($post['floatingPassword'] != $post['floatingRetype'])
  		return '<span style="font-weight: bold; color: red;">Podane hasła się różnią!</span>';

  	try {
  		$hash = password_hash($post['password'], PASSWORD_DEFAULT);
  		$stmt = $dbh->prepare('INSERT INTO users (id, email, password, created) VALUES (null, :email, :password, NOW())');
  		$stmt->execute([':email' => $post['email'], ':password' => $hash]);

  		return '<span style="font-weight: bold; color: green;">Zarejestrowano użytkownika '.$post['email'].' :))</span>';
  	} catch (PDOException $e) {
  		return '<span style="font-weight: bold; color: red;">Podany email jest zajęty!</span>';
  	}
  }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>Sign up KalendarzToDo</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <meta name="theme-color" content="#7952b3">
  <!-- Custom styles for this template -->
  <link href="signin.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin">
  <form>
    <h1 class="h3 mb-3 fw-normal">Please sign up</h1>

    <div class="form-floating">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>

    <div class="form-floating">
      <input type="password" class="form-control first-password" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <div class="form-floating">
      <input type="password" class="form-control" id="floatingRetype" placeholder="RetypePassword">
      <label for="floatingRetype">Retype Password</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="button">Sign up</button>
  </form>

<?php
  if (isset($_POST['floatingInput'], $_POST['floatingPassword'], $_POST['floatingRetype'])) {
  	print register_user($_POST, $config, $_SERVER, $dbh);
  }
?>
</main>

</body>
</html>
