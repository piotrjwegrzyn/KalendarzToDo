<?php
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }
?>

<body class="body-sign">

<main class="form-sign">

  <div class="div-sign">



    <form action='/signin' method='POST'>
      <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

      <div class="form-floating">
        <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
      </div>

      <div class="form-floating">
        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>

      <input class="w-100 btn btn-lg btn-primary button-sign" type="submit" value="Sign in">
    </form>
    <a href="signup"><button class="w-100 btn btn-lg btn-primary button-sign" type="button">Sign up</button></a>

  </div>


  <?php
  #--------------------LOGOWANIE--------------------
  # s119.labagh.pl/
  if (isset($_POST['email'], $_POST['password']) && $_POST['email'] && $_POST['password']) {
      $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->execute([':email' => $_POST['email']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($user) {
          if (password_verify($_POST['password'], $user['password'])) {
              $_SESSION['id'] = $user['id'];
              $_SESSION['email'] = $user['email'];
              header("Refresh:0");
          } else {
              print '<span style="font-weight: bold; color: red;">Niepoprawne dane!</span>';
          }
      }
  }
  ?>
</main>
