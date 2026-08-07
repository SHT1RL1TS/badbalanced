# ButBalanced
это сайт для кастомного режима 'ButBalanced' в Dota 2. создан в целях введения истории изменения

## 📋 Описание проекта

Сайт для отслеживания изменений героев и их скиллов в различных патчах игры. Система позволяет хранить информацию о патчах, героях, и их скиллах с возможностью отслеживания истории изменений.

## 🗄️ Структура базы данных

### Таблицы

#### `pathes`
Хранит информацию о патчах (обновлениях игры)

```
`id` - PRIMARY KEY, автоинкремент
`name` - название патча
`description` - описание изменений
`is_major` - флаг крупного патча (BOOLEAN)
`patch_img_url` - URL изображения патча
```
#### `attribut`
Справочник атрибутов героев
```
`attribute_id` - PRIMARY KEY
`attribute_name` - название атрибута (Strength, Agility, Intelligence, Universal)
```
#### `heroes`
Информация о героях
```
`id_hero` - PRIMARY KEY
`id` - FOREIGN KEY → `pathes(id)` (ON DELETE CASCADE)
`attribute_id` - FOREIGN KEY → `attribut(attribute_id)` (ON DELETE SET NULL)
`name_hero` - имя героя
`description_hero` - описание
`stats_hero` - JSON объект со статистикой
`icon_url_hero` - URL иконки
`thumbnail_url_hero` - URL миниатюры
`created_at` - дата создания
`updated_at` - дата последнего обновления
```
#### `skills`
Информация о скиллах героев
```
`id_skill` - PRIMARY KEY
`id` - FOREIGN KEY → `pathes(id)` (ON DELETE CASCADE)
`id_hero` - FOREIGN KEY → `heroes(id_hero)` (ON DELETE CASCADE)
`name_skill` - название скилла
`stats_skill` - JSON объект со статистикой
`description_skill` - описание
`image_url_skill` - URL изображения
`created_at` - дата создания
`stats_skill` - дата последнего обновления
```
### Связи между таблицами
```
pathes (1) ── (M) heroes

attribut (1) ── (M) heroes

heroes (1) ── (M) skills
```
### 📚 API Функции

#### `GET` - Получение данных

| Функция | Описание | Возвращает |
|---------|----------|------------|
| getAllHeroes($db) | Все герои | array |
| getAllSkills($db) | Все скиллы с информацией о героях | array |
| getHeroSkills($db, $hero_id) | Скиллы конкретного героя | array |
| getPatchHeroes($db, $patch_id) | Герои конкретного патча | array |
| getPatchSkills($db, $patch_id) | Скиллы конкретного патча | array |
| getAllPatches($db) | Все патчи | array |
| getPatchById($db, $patch_id) | Патч по ID | array |
| getAttributes($db) | Все атрибуты | array |
| getHeroById($db, $hero_id) | Герой по ID с деталями | array |
| getSkillById($db, $skill_id) | Скилл по ID | array |
| getHeroChangeHistory($db, $hero_id) | История изменений героя | array |
| getSkillChangeHistory($db, $skill_id) | История изменений скилла | array |
| getRecentChanges($db, $days) | Недавние изменения (по умолчанию 7 дней) | array |

#### `CREATE` - Создание

| Функция | Описание | Параметры | Возвращает |
|---------|----------|-----------|------------|
| createPatch($db, $data) | Создать патч | `['name', 'description', 'is_major', 'patch_img_url']` | int (ID) |
| createHero($db, $data) | Создать героя | `['patch_id', 'attribute_id', 'name_hero', 'description_hero', 'stats_hero', 'icon_url_hero', 'thumbnail_url_hero']` | int (ID) |
| createSkill($db, $data) | Создать скилл | `['patch_id', 'hero_id', 'name_skill', 'description_skill', 'image_url_skill']` | int (ID) |

#### `UPDATE` - Обновление

| Функция | Описание | Параметры | Возвращает |
|---------|----------|-----------|------------|
| updateHero($db, $hero_id, $data) | Обновить героя | `['name_hero', 'description_hero', 'attribute_id', 'stats_hero', 'icon_url_hero', 'thumbnail_url_hero']` | bool |
| updateSkill($db, $skill_id, $data) | Обновить скилл | `['name_skill', 'description_skill', 'image_url_skill', 'hero_id']` | bool |
| updatePatch($db, $patch_id, $data) | Обновить патч | `['name', 'description', 'is_major', 'patch_img_url']` | bool |
#### `DELETE` - Удаление
| Функция | Описание | Возвращает |
|---------|----------|------------|
| deleteHero($db, $hero_id) | Удалить героя (каскадно удаляет скиллы) | bool |
| deleteSkill($db, $skill_id) | Удалить скилл | bool |
| deletePatch($db, $patch_id) | Удалить патч (каскадно удаляет героев и скиллы) | bool |

📄 Лицензия
MIT

👥 Авторы
ButBalanced Team