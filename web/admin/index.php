<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Защита: если не авторизован, отправляем на страницу логина
    $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $route = rtrim($route, '/');

    if (empty($_SESSION['user_name']) && $route !== '/admin/login') {
        header('Location: /admin/login');
        exit;
    }

    // Если зашли просто на /admin — открываем главную
    if ($route === '' || $route === '/admin') {
        $route = '/admin/home';
    }

    // Базовый URL для CSS и ссылок
    $baseUrl = "/admin/";
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
            <?php include __DIR__ . '/tools/links.php'; ?>
        </head>
        <body style="width:100%;margin:0;" data-aos-easing="ease" data-aos-duration="400" data-aos-delay="0">

            <?php include __DIR__ .'/tools/header.php'; ?>

            <?php
                $heroSlug = '';
                if (preg_match('/^hero\/([a-zA-Z-]+)$/', $route, $matches)) {
                    $heroSlug = $matches[1];
                    include __DIR__ . "/edit_heroes.php";
                }
                if (preg_match('#^/admin/patch/(\d+)$#', $route, $matches)) {
                    $patchId = (int)$matches[1]; // Достаем ID патча
                    require_once __DIR__ . '/viewer_patch.php';
                    exit;
                }
                switch ($route) {

                    case '/admin/home':
                        include 'home.php';
                        break;
                    case '/admin/login':
                        include 'login.php';
                        break;
                    case '/admin/heroes':
                        include 'heroes.php';
                        break;
                    case '/admin/logout':
                        include 'logout.php';
                        break;
                    case '/admin/skills':
                        include 'skills.php';
                        break;
                    case '/admin/items':
                        include 'items.php';
                        break;
                    case '/admin/patches':
                        include 'patches.php';
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
            <!-- Общие скрипты -->
            <?php
                switch ($route) {
                    case '/admin/home':
                        echo '<script src="../JS/HOME_JS.js"></script>';
                        break;
                    case '/admin/heroes':
                        echo '<script defer src="../JS/HEROES_JS.js"></script>';
                        break;
                    // case '/admin/skills':
                    //     echo '<script defer src="../JS/SKILLS_JS.js"></script>';
                    //     break;
                    // case '/admin/items':
                    //     echo '<script defer src="../JS/ITEMS_JS.js"></script>';
                    //     break;
                    // case '/admin/patches':
                    //     echo '<script defer src="../JS/PATCHES_JS.js"></script>';
                    //     break;
                }
            ?>
            <?php include __DIR__ . "/tools/footer.php"; ?>
        </body>
    </html>
