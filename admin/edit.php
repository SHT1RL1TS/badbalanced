<!-- 
    1=Strength, 2=Agility, 3=Intelligence, 4=Universal
-->
<?php
require_once __DIR__ . '\tools\db.php';
require_once __DIR__ . '\tools\functions.php';

$database = new Database();
$db = $database->getConnection();

if ($db === null) {
    die('Не удалось подключиться к базе данных.');
}

// $apiUrl = 'https://api.opendota.com/api/heroStats';
// $json = file_get_contents($apiUrl);
// if (!$json) {
//     die('Не удалось загрузить данные с API');
// }
// $heroes = json_decode($json, true);
// if (!$heroes) {
//     die('Ошибка парсинга JSON');
// }

// $attrMap = [
//     'str' => 1,
//     'agi' => 2,
//     'int' => 3,
// ];

// $inserted = 0;
// foreach ($heroes as $hero) {
//     // Пропускаем, если нет локального имени или неизвестный атрибут
//     if (empty($hero['localized_name']) || !isset($attrMap[$hero['primary_attr']])) {
//         continue;
//     }
//     echo basename($hero['icon']);
//     // Заполняем поля корректными данными из HeroStatsResponse
//     $heroData = [
//         'attribute_id'      => $attrMap[$hero['primary_attr']],
//         'name_hero'         => $hero['localized_name'],
//         'description_hero'  => '', 
//         'stats_hero'        => json_encode([
//             'strength'      => $hero['base_str'] ?? 0,      // Берем базовую силу
//             'agility'       => $hero['base_agi'] ?? 0,      // Берем базовую ловкость
//             'intelligence'  => $hero['base_int'] ?? 0,      // Берем базовый интеллект
//         ]),
//         // В API путь к иконке относительный, добавляем домен
//         'icon_hero'     => basename(parse_url($hero['icon'] ?? '', PHP_URL_PATH) ?: ''),
//     ];

//     try {
//         $id = createHero($db, $heroData);
//         echo "Добавлен герой: {$heroData['name_hero']} (ID: $id)\n";
//         $inserted++;
//     } catch (PDOException $e) {
//         echo "Ошибка при добавлении {$heroData['name_hero']}: " . $e->getMessage() . "\n";
//     }
// }

// echo "Всего добавлено/обновлено героев: $inserted\n";