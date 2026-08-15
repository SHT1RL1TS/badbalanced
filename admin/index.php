<?php
    session_start();
    $basePath = '/cd-project/butbalanced/';
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $temp = $basePath . "admin/";
    $route = str_replace($temp, '', $uri);
    $route = trim($route, '/');
    if (!isset($_SESSION['user_name'])) {
        if ($route === '' || $route === 'index.php' || $route === 'home') {
            $route = 'login';
            Header('Location:login');
        }
    }
    // Базовый URL для CSS и ссылок
    $baseUrl = $basePath . 'admin/';
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
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
            <script src="<?=$baseUrl . 'jquery.min.js'?>" defer></script>
            <?php include $_SERVER['DOCUMENT_ROOT'] . $baseUrl . '/tools/links.php'; ?>
        </head>
        <body style="width:100%;margin:0;" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">

            <?php include $_SERVER['DOCUMENT_ROOT'] . $baseUrl .'/tools/header.php'; ?>

            <?php
                $heroSlug = '';
                if (preg_match('/^hero\/([a-zA-Z-]+)$/', $route, $matches)) {
                    $heroSlug = $matches[1];
                    include __DIR__ . "/edit_heroes.php";
                } else {
                    switch ($route) {

                        case 'home':
                            include 'home.php';
                            break;
                        case 'login':
                            include 'login.php';
                            break;
                        case 'heroes':
                            include 'heroes.php';
                            break;
                        case 'logout':
                            include 'logout.php';
                            break;
                        case 'skills':
                            include 'skills.php';
                            break;
                        case 'items':
                            include 'items.php';
                            break;
                        case 'patches':
                            include 'patches.php';
                            break;
                        default:
                            http_response_code(404);
                            break;
                    }
                }
            ?>

            <?php include $_SERVER['DOCUMENT_ROOT'] . $baseUrl .'/tools/footer.php'; ?>
            <!-- Общие скрипты -->
            <?php
                switch ($route) {
                    case 'home':
                        echo '<script src="' . $baseUrl . 'JS/HOME_JS.js"></script>';
                        break;
                    case 'skills':
                        echo '<script src="' . $baseUrl . 'JS/SKILLS_JS.js"></script>';
                        break;
                    case 'items':
                        echo '<script src="' . $baseUrl . 'JS/ITEMS_JS.js"></script>';
                        break;
                    case 'patches':
                        echo '<script src="' . $baseUrl . 'JS/PATCHES_JS.js"></script>';
                        break;
                    case 'heroes':
                        echo '<script defer src="' . $baseUrl . 'JS/HEROES_JS.js"></script>';
                        break;
                }
            ?>

            <script src="<?=$baseUrl?>JS/HEADER_JS.js" defer></script>
            <script>
                document.addEventListener('DOMContentLoaded', INIT_AOS);

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', INIT_AOS);
                } else {
                    initDropdown();
                }
                function INIT_AOS()
                {
                    AOS.init({
                        duration: 1200,
                        once: false,
                        mirror: true,
                        offset: 120,
                        disable: false,
                        startEvent: 'DOMContentLoaded'
                    });
                }
            </script>
        </body>
    </html>
