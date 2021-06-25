<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <!-- nasz własny CSS -->
    <link href="style.css" rel="stylesheet">

    <meta name="theme-color" content="#7952b3">

    <title>Kalendarz ToDo</title>

</head>
<body>



<!-- miejsce na PHP -->




<footer class="footer" role="contentinfo">
    Some footer Content
</footer>

<!-- nasz własny Javascript -->
<script>

    for (const element of document.getElementsByClassName('list-group-item-lepszy-description')) {
        const content = element.getElementsByClassName("list-group-item-lepszy-description-zawartosc")[0]
        element.onclick = () => {
            content.style.display = content.style.display === 'none' ? 'block' : 'none'
        }
    }

</script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>