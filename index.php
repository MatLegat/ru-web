<?php
    define('VIEW', true);
    include_once('ru.php');
    $ru = new RU();
    $day = $ru->getDay();
    $menu = $ru->getMenu($day);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Language" content="pt-br, en">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <title> RU UFSC Trindade </title>
        <link rel="stylesheet" type="text/css" href="/ru.css" />
        <script type="text/javascript" src="/ru.js"></script>
        <script type="text/javascript" src="https://legat.ml/_js/analytics.js"></script>
    </head>
    <body>
        <?php foreach ($menu as $tag => $group) { ?>
            <?php if ($tag == "Dia") { ?>
                <h2>
                    <?php foreach ($group as $line) { ?>
                            <?php echo $line ?><br />
                    <?php } ?>
                </h2>
            <?php } else { ?>
                <h3><?php echo $tag; ?></h3>
                <?php foreach ($group as $dish) { ?>
                    <?php if ($dish != "Dia") { ?>
                        <?php echo $dish ?><br>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
        <?php
            $backDis = "";
            $nextDis = "";
            if ($day <= 0) {
                $backDis = "disabled";
            } else if ($day >= 6) {
                $nextDis = "disabled";
            }
         ?>
        <p><sub><a href="config/">configurar</a></sub></p>
        <button class="back" <?php echo $backDis ?> onclick="window.location.href='.?day=<?php echo $day-1 ?>'">
            &lt;
        </button>
        <button class="next" <?php echo $nextDis ?> onclick="window.location.href='.?day=<?php echo $day+1 ?>'">
            &gt;
        </button>
    </body>
</html>
