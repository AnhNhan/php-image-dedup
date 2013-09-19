<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/root.php';


$images = [
    'src/beach_1.jpg',
    'src/beach_2.jpg',
    'small/beach_1.jpg',
    'small/beach_2.jpg',
    'src/cn_food.jpg',
    'src/frapport_checkin.jpg',
];

ob_start();
// -------------------------
//      CONTENT START
// -------------------------

foreach ($images as $imgPath) {
    ?>
    <tr>
        <td><?= $imgPath ?></td>
        <td>
            <img src="/orig.php?path=<?= urlencode($imgPath) ?>" />
        </td>
        <td>
            <img src="/hasher.php?path=<?= urlencode($imgPath) ?>" />
        </td>
    </tr>
    <?php
}

// -------------------------
//      CONTENT END
// -------------------------
$finalContent = ob_get_clean();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>%s</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    </head>

    <body>
        <table>
            <tr>
                <th>Path</th>
                <th>Orig</th>
                <th>Hash</th>
            </tr>
            <?= $finalContent ?>
        </table>
    </body>
</html>
