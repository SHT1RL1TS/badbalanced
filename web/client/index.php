<?php
if(session_status() !== PHP_SESSION_ACTIVE)
{
    session_start();
}
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$route = trim($uri, "/");

// Убираем префикс client/ или client/index.php/
$route = preg_replace('#^client(/index\.php)?/#', '', $route);

if ($route === "" || $route === "index.php") {
    $route = "home";
}

// Базовый URL для CSS и ссылок
$baseUrl = "/";
$link_cus = "https://steamcommunity.com/sharedfiles/filedetails/?id=3699416108";

if (!isset($src)) {
    $src = "../src/";
}
if (!isset($css)) {
    $css = "/CSS/";
}
if (!isset($api)) {
    $api = [
        "../api/db.php",
        "../api/functions.php"
    ];
}
foreach ((array)$api as $file) {
    require_once $file;
}
/** @var PDO $db */
$db = getDb();
?>

<!DOCTYPE html>
    <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
            <!--<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>-->
            <link rel="stylesheet" type="text/css" href="<?= $css ?>aos.css">
            <?php include __DIR__ . "/tools/links.php"; ?>
        </head>
        <body style="width:100%;margin:0;" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">

            <?php include __DIR__ . "/tools/header.php"; ?>

            <?php
                // if (preg_match('/^hero\/([a-zA-Z-]+)$/', $route, $matches)) {
                //     $heroSlug = $matches[1];
                //     include __DIR__ . "/hero_detail.php";
                // } else {
                // }
                switch ($route) {
                    case "home":
                        include "home.php";
                        break;
                    case "news":
                        include "news.php";
                        break;
                    case "heros":
                        include "heroes.php";
                        break;
                    case "custom":
                        include "custom.php";
                        break;
                    default:
                        break;
                }
            ?>
            <script src="<?= $baseUrl ?>JS/aos.js"></script>
            <!-- Общие скрипты -->
            <?php switch ($route) {
                case "news":
                    echo '<script src="' .
                        '../JS/NEWS_JS.js"></script>';
                    break;
                case "heros":
                    echo '<script src="' .
                        '../JS/HEROES_JS.js"></script>';
                    break;
                case "custom":
                    echo '<script src="' .
                        '../JS/CUSTOM_JS.js"></script>';
                    break;
                case "home":
                    echo '<script src="' .
                        '../JS/HOME_JS.js"></script>';
                    break;
                // default:
                //     // Если это страница героя, подключаем её скрипт
                //     if (preg_match("/^hero\//", $route)) {
                //         echo '<script src="' . $baseUrl . 'JS/HERO_DETAIL_JS.js"></script>';
                //     }
                //     break;
            } ?>
            <?php include __DIR__ . "/tools/footer.php"; ?>
        </body>
    </html>
