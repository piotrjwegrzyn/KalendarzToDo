<!-- plik main -->
<?php
# s119.labagh.pl/
if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

#--------------------USUWANIE-SIEBIE-JAKO-GUEST--------------------
if (isset($_GET['unlink']) && $_GET['unlink']) {
    $stmt = $dbh->prepare("DELETE FROM links WHERE task_id = :task_id AND guest_email = :user_email");
    $stmt->execute([':task_id' => $_GET['unlink'], ':user_email' => $_SESSION['email']]);
}

$day_index = (date("w") -1) % 8;
if ($day_index < 0)
{
    $day_index += 7;
}

if (!isset($_GET['week_offset'])) {
    $_GET['week_offset'] = 0;
    $_GET['day_index'] = $day_index;
} elseif (!isset($_GET['day_index'])) {
    $_GET['day_index'] = 0;
}

$today = date('Y-m-d');

for ($i = 0; $i < 7; $i++){
    $stmt = $dbh->prepare("SELECT id FROM tasks WHERE ((user_email = :user_email
           OR id = ANY (SELECT task_id FROM links WHERE guest_email = :user_email))
       AND ((((YEARWEEK(begin_time,1) = YEARWEEK(NOW(),1) + :week_offset)
                    AND (WEEKDAY(begin_time) <= :day_index))
                OR (YEARWEEK(begin_time,1) < YEARWEEK(NOW(),1) + :week_offset))
    	   AND (((YEARWEEK(end_time,1) = YEARWEEK(NOW(),1) + :week_offset)
                    AND (WEEKDAY(end_time) >= :day_index))
               OR (YEARWEEK(end_time,1) > YEARWEEK(NOW(),1) + :week_offset)
    )))");
    $stmt->execute([':user_email' => $_SESSION['email'], ':week_offset' => $_GET['week_offset'], ':day_index' => $i]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $busy_day[$i] = 1;
    } else {
        $busy_day[$i] = 0;
    }

    $date[$i] = date('Y-m-d', strtotime($_GET['week_offset'].' week '.((-1)*$day_index+$i).' days'));

    if ($date[$i] == $today)
        $date[$i] = $date[$i]." (TODAY)";


    $row[$i] = "This day is ";

    if ($busy_day[$i])
        $row[$i] = $row[$i]."<b>NAUKA</b>";
    else
        $row[$i] = $row[$i]."HARNAŚ";

    $row[$i] = $row[$i]." day";
}
?>

<body>

<!-- Pasek u góry -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">To Do Calendar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/create">Add new event</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Settings</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link"><?php print $_SESSION['email']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Główny element -->
<main class="container-fluid">

    <div class="row">


        <!-- tu się zaczyna nav z lewej strony -->
        <div class="col-md-4">

            <!-- lista elementów nava -->
            <div class="list-group list-group-flush scrollarea nav-nav">

                <!-- nagłówek nava -->
                <div class="list-group-item lh-tight header-nav">
                    <div class="row" id="list-group">
                        <a href="/week/<?php print $_GET['week_offset'] - 1; ?>" class="btn col-2 button-nav button-show">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-arrow-left">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                            </svg>
                        </a>

                        <a href="/" class="link-dark text-xs-center text-decoration-none col-8 fs-3 fw-semibold center-class white-font">
                            WEEK <?php if ($_GET['week_offset']) { print ": "; if ($_GET['week_offset'] > 0) { print "+"; } print $_GET['week_offset']; } ?>
                        </a>

                        <a href="/week/<?php print $_GET['week_offset'] + 1; ?>" class= "btn col-2 button-nav button-show">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-arrow-right">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"></path>
                            </svg>
                        </a>
                    </div>
                </div> <!-- koniec nagłówka nava -->

                <a href="/week/<?php print $_GET['week_offset']; ?>/day/0" class="list-group-item py-3 lh-tight element-nav" aria-current="true">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1"><?php print $date[0]; ?></strong>
                        <small><?php if($_GET['day_index'] == 0) print "<b>MONDAY</b>"; else print "MONDAY"; ?></small>
                    </div>
                    <div class="col-10 mb-1 small"><?php print $row[0]; ?></div>
                </a>

                <a href="/week/<?php print $_GET['week_offset']; ?>/day/1" class="list-group-item py-3 lh-tight element-nav" aria-current="true">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1"><?php print $date[1]; ?></strong>
                        <small><?php if($_GET['day_index'] == 1) print "<b>TUESDAY</b>"; else print "TUESDAY"; ?></small>
                    </div>
                    <div class="col-10 mb-1 small"><?php print $row[1]; ?></div>
                </a>

                <a href="/week/<?php print $_GET['week_offset']; ?>/day/2" class="list-group-item py-3 lh-tight element-nav" aria-current="true">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1"><?php print $date[2]; ?></strong>
                        <small><?php if($_GET['day_index'] == 2) print "<b>WEDNSDAY</b>"; else print "WEDNSDAY"; ?></small>
                    </div>
                    <div class="col-10 mb-1 small"><?php print $row[2]; ?></div>
                </a>

                <a href="/week/<?php print $_GET['week_offset']; ?>/day/3" class="list-group-item py-3 lh-tight element-nav" aria-current="true">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1"><?php print $date[3]; ?></strong>
                        <small><?php if($_GET['day_index'] == 3) print "<b>THURSDAY</b>"; else print "THURSDAY"; ?></small>
                    </div>
                    <div class="col-10 mb-1 small"><?php print $row[3]; ?></div>
                </a>

                <a href="/week/<?php print $_GET['week_offset']; ?>/day/4" class="list-group-item py-3 lh-tight element-nav" aria-current="true">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1"><?php print $date[4]; ?></strong>
                        <small><?php if($_GET['day_index'] == 4) print "<b>FRIDAY</b>"; else print "FRIDAY"; ?></small>
                    </div>
                    <div class="col-10 mb-1 small"><?php print $row[4]; ?></div>
                </a>

                <a href="/week/<?php print $_GET['week_offset']; ?>/day/5" class="list-group-item py-3 lh-tight element-nav" aria-current="true">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1"><?php print $date[5]; ?></strong>
                        <small> <?php if($_GET['day_index'] == 5) print "<b>SATURDAY</b>"; else print "SATURDAY"; ?></small>
                    </div>
                    <div class="col-10 mb-1 small"><?php print $row[5]; ?></div>
                </a>

                <a href="/week/<?php print $_GET['week_offset']; ?>/day/6" class="list-group-item py-3 lh-tight element-nav" aria-current="true">
                    <div class="d-flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1"><?php print $date[6]; ?></strong>
                        <small><?php if($_GET['day_index'] == 6) print "<b>SUNDAY</b>"; else print "SUNDAY"; ?></small>
                    </div>
                    <div class="col-10 mb-1 small"><?php print $row[6]; ?></div>
                </a>

            </div> <!-- to kończy się lista elementów nava -->

        </div> <!-- tu się zamyka nav z lewej strony -->


        <!-- tu zaczyna się ta szeroka lista z prawej strony -->

        <div class="col-md-8" style="z-index: 1;">

            <div class="list-group list-group-flush scrollarea nav-nav">
                <?php
                    $allowed_pages = ['edit', 'create', 'show'];

                    if (isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)) {
                        include($_GET['page'] . '.php');
                    } else {
                        include('show.php');
                    }
                ?>
            </div>
        </div>
    </div>
</main>
<!-- koniec pliku main -->
