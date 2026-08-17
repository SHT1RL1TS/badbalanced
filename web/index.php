<?php
// phpinfo();
echo "✅ Сайт работает!<br><br>";

// Проверяем подключение к БД
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'butbalanced';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'jdp96n';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    echo "✅ Подключение к PostgreSQL успешно!<br><br>";

    // Показываем героев
    $stmt = $pdo->query("SELECT name_hero, attribute_id FROM heroes LIMIT 10");
    $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Герои Dota 2:</h2>";
    echo "<ul>";
    foreach ($heroes as $hero) {
        echo "<li>" . htmlspecialchars($hero['name_hero']) . " (Attribute ID: " . $hero['attribute_id'] . ")</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "❌ Ошибка БД: " . $e->getMessage();
}
echo __DIR__;
