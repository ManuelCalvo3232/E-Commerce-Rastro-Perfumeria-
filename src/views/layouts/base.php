<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= html($title) ?></title>
    </head>
    <body>
       <?php require __DIR__ . "/../partials/navbar.php"; ?>

        <?= $content ?>

        <?php require __DIR__ . "/../partials/footer.php"; ?>
    </body>
</html>
