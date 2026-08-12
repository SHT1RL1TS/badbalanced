<?php
session_start();
$basePath = "/cd-project/butbalanced/";
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$temp = $basePath . "client/";
$route = str_replace($temp, "", $uri);
$route = trim($route, "/");

if ($route === "" || $route === "index.php") {
    $route = "home";
}

// Базовый URL для CSS и ссылок
$baseUrl = $basePath . "client/";
$link_cus = "https://steamcommunity.com/sharedfiles/filedetails/?id=3699416108";

if (!isset($src)) {
    $src = "/cd-project/butbalanced/src/";
}
if (!isset($css)) {
    $css = "/cd-project/butbalanced/CSS/";
}
if (!isset($api)) {
    $api = [
        "/cd-project/butbalanced/api/db.php",
        "/cd-project/butbalanced/api/functions.php"
    ];
}
foreach ((array)$api as $file) {
    require_once $_SERVER['DOCUMENT_ROOT'] . $file;
}
/** @var PDO $db */
$db = getDb();
?>

<!DOCTYPE html>
    <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
            <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <?php include __DIR__ . "/tools/links.php"; ?>
        </head>
        <body style="width:100%;margin:0;" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">

            <?php include __DIR__ . "/tools/header.php"; ?>

            <?php
                if (preg_match('/^hero\/([a-zA-Z-]+)$/', $route, $matches)) {
                    $heroSlug = $matches[1];
                    include __DIR__ . "/hero_detail.php";
                } else {
                    switch ($route) {
                        case "home":
                            include __DIR__ . "/home.php";
                            break;
                        case "news":
                            include __DIR__ . "/news.php";
                            break;
                        case "heros":
                            include __DIR__ . "/heroes.php";
                            break;
                        case "custom":
                            include __DIR__ . "/custom.php";
                            break;
                        default:
                            http_response_code(404);
                            // include __DIR__ . "/404.php";
                            break;
                    }
                }
            ?>
            <!-- Общие скрипты -->

            <?php switch ($route) {
                case "news":
                    echo '<script src="' .
                        $baseUrl .
                        'JS/NEWS_JS.js"></script>';
                    break;
                case "heros":
                    echo '<script src="' .
                        $baseUrl .
                        'JS/HEROES_JS.js"></script>';
                    break;
                case "custom":
                    echo '<script src="' .
                        $baseUrl .
                        'JS/CUSTOM_JS.js"></script>';
                    break;
                case "home":
                    echo '<script src="' .
                        $baseUrl .
                        'JS/HOME_JS.js"></script>';
                    break;
                default:
                    // Если это страница героя, подключаем её скрипт
                    if (preg_match("/^hero\//", $route)) {
                        echo '<script src="' . $baseUrl . 'JS/HERO_DETAIL_JS.js"></script>';
                    }
                    break;
            } ?>
            <?php include __DIR__ . "/tools/footer.php"; ?>
        </body>
    </html>
