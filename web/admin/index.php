<?php
    if(session_status() !== PHP_SESSION_ACTIVE)
    {
        session_start();
    }
    $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $route = trim($uri, "/");

    // Убираем префикс admin/ или admin/index.php/
    $route = preg_replace('#^admin(/index\.php)?/#', '', $route);

    if ($route === "" || $route === "index.php") {
        $route = "home";
    }

    // Базовый URL для CSS и ссылок
    $baseUrl = "/";
    if (!isset($src)) {
        $src = "/src/";
    }
    if (!isset($css)) {
        $css = "/CSS/";
    }
    if (!isset($api)) {
        $api = [
            "/api/db.php",
            "/api/functions.php"
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
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <?php include __DIR__ . '/tools/links.php'; ?>
        </head>
        <body style="width:100%;margin:0;" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">

            <?php include __DIR__ .'/tools/header.php'; ?>

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
                    }
                }
            ?>

            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <!-- Общие скрипты -->
            <?php
                switch ($route) {
                    case 'home':
                        echo '<script src="' . $baseUrl . 'JS/HOME_JS.js"></script>';
                        break;
                    case 'skills':
                        echo '<script defer src="' . $baseUrl . 'JS/SKILLS_JS.js"></script>';
                        break;
                    case 'items':
                        echo '<script defer src="' . $baseUrl . 'JS/ITEMS_JS.js"></script>';
                        break;
                    case 'patches':
                        echo '<script defer src="' . $baseUrl . 'JS/PATCHES_JS.js"></script>';
                        break;
                    case 'heroes':
                        echo '<script defer src="' . $baseUrl . 'JS/HEROES_JS.js"></script>';
                        break;
                }
            ?>
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
            <?php include __DIR__ . "/tools/footer.php"; ?>
        </body>
    </html>
