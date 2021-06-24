<?php
# s119.labagh.pl/
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

#--------------------USUWANIE-SIEBIE-JAKO-GUEST--------------------
if (isset($_GET['unlink']) && $_GET['unlink']) {
    $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id AND guest_id = :user_id");
    $stmt->execute([':task_id' => $_GET['unlink'], ':user_id' => $_SESSION['id']]);
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <style>

        main {
            height: 100%;
        }

        .float-container {
            border: 3px solid #fff;
            padding: 20px;
        }

        .float-child {
            width: 50%;
            float: left;
            padding: 20px;
            border: 2px solid red;
        }

        .list-group-item-lepszy {
            background-color: #0dcaf0;
            display: grid;
            grid-template-areas: "name name" "begin_time edit_icon_button" "end_time delete_icon_button" "description description";
            grid-template-columns: 1fr 24px;
        }

        .list-group-item-lepszy-description h6 {
            user-select: none;

        }

        .list-group-item-lepszy-description {
            grid-area: description;
        }

    </style>

    <title>Kalendarz ToDo</title>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="create">Add new task</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Settings</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="logout">Log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<main class="float-container">


    <div>
        <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white float-child" style="width: 380px;">
            <a href="/" class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">
                <svg class="bi me-2" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
                <span class="fs-5 fw-semibold">List group</span>
            </a>
            <div class="list-group list-group-flush border-bottom scrollarea">
<?php
    $stmt = $dbh->prepare("SELECT * FROM tasks WHERE user_id = :user_id OR id = (SELECT task_id FROM links WHERE guest_id = :user_id) ORDER BY begin_time ASC");
    $stmt->execute([':user_id' => $_SESSION['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print
        '<a href="/show/'.$row['id'].'" class="list-group-item list-group-item-action active py-3 lh-tight" aria-current="true">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <strong class="mb-1">'.$row['id'].'</strong>
                <small>Wed</small>
            </div>
            <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
        </a>';
    }
?>


            </div>
        </div>

        <div class="container mb-5 float-child">
            <ul class="list-group">
                <li class="list-group-item">
<?php
    $allowed_pages = ['edit', 'create', 'show'];

    if (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
        include($_GET['page'] . '.php');
    }
?>
                </li>
            </ul>
        </div>
    </div>
</main>

<!-- Optional JavaScript; choose one of the two! -->
<script>

for (const element of document.getElementsByClassName('list-group-item-lepszy-description')) {
    const content = element.getElementsByClassName("list-group-item-lepszy-description-zawartosc")[0]
    element.onclick = () => {
        content.style.display = content.style.display === 'none' ? 'block' : 'none'
    }
}

</script>


<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
-->
</body>
</html>
