<?php
    define('VIEW', true);
    include_once('../ru.php');
    $ru = new RU();
    $day = $ru->getDay();
    $tags = $ru->getTags();
    $display = $ru->getDisplayOptions();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Language" content="pt-br, en">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <title> RU UFSC Trindade </title>
        <link rel="stylesheet" type="text/css" href="/_css/ru.css" />
        <script type="text/javascript" src="/_js/ru.js"></script>
        <script type="text/javascript" src="/_js/analytics.js"></script>
    </head>
    <body>
        <?php foreach ($tags as $tag) {
            $selected = "";
            if ($display[$tag])
                $selected = "checked";
            ?>
            <p><label><input name="pop" type="checkbox" <?php echo $selected ?> id="<?php echo $tag ?>"><?php echo $tag ?></label></p>
        <?php } ?>
        <button onclick="minus()">&#x2212;</button>
        <button onclick="save()">Salvar</button>
        <button onclick="plus()">+</button>
        <br><a onclick="reset()" href=".."><sub>reset</sub></a>
    </body>
</html>
