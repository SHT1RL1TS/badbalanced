<!-- Общие стили -->
<link rel="stylesheet" type="text/css" href="<?= $css ?>MAIN_CSS.css">
<link rel="stylesheet" type="text/css" href="<?= $css ?>HEADDER_CSS.css">

<!-- CSS для конкретных страниц -->
<?php
switch ($route) {
    case 'news':
        echo '<link rel="stylesheet" type="text/css" href="' . $css . 'NEWS_CSS.css">';
        break;
    case 'heros':
        echo '<link rel="stylesheet" type="text/css" href="' . $css . 'HEROES_CSS.css">';
        break;
    case 'custom':
        echo '<link rel="stylesheet" type="text/css" href="' . $css . 'CUSTOM_CSS.css">';
        break;
    case 'home':
        echo '<link rel="stylesheet" type="text/css" href="' . $css . 'HOME_CSS.css">';
        echo '<link rel="stylesheet" type="text/css" href="' . $css . 'HERO_DETAIL_CSS.css">';
        break;
}
?>

<link type="image/x-icon" href="/web/src/icon.jpg" rel="icon">
<title>ButBalanced</title>

<style type="text/css">
    @font-face {
        font-family: 'Radiance';
        src: url('src/fonts/radiance-semibold.woff') format('woff');
        font-weight: 600;
        font-style: sans-serif;
    }
    @font-face {
        font-family: 'Radiance';
        src: url('src/fonts/radiance.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    }
    @font-face {
        font-family: 'Reaver';
        src: url('src/fonts/Reaver-Bold.woff') format('woff');
        font-weight: bold;
        font-style: normal;
    }
    @font-face {
        font-family: 'Reaver';
        src: url('src/fonts/Reaver-Semibold.woff') format('woff');
        font-weight: 600;
        font-style: normal;
    }
    .indiana-scroll-container {
    overflow: auto; }
    .indiana-scroll-container--dragging {
        scroll-behavior: auto !important; }
    .indiana-scroll-container--dragging > * {
        pointer-events: none;
        cursor: -webkit-grab;
        cursor: grab; }
    .indiana-scroll-container--hide-scrollbars {
        overflow: hidden;
        overflow: -moz-scrollbars-none;
        -ms-overflow-style: none;
        scrollbar-width: none; }
        .indiana-scroll-container--hide-scrollbars::-webkit-scrollbar {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        background: transparent !important;
        -webkit-appearance: none !important; }
    .indiana-scroll-container--native-scroll {
        overflow: auto; }

    .indiana-dragging {
    cursor: -webkit-grab;
    cursor: grab; }
</style>
