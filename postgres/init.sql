--
-- База данных ButBalanced
--

DROP TABLE IF EXISTS heroes_stats CASCADE;
DROP TABLE IF EXISTS heroes CASCADE;
DROP TABLE IF EXISTS attribut CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS acl CASCADE;
DROP TABLE IF EXISTS ability CASCADE;
DROP TABLE IF EXISTS pathes CASCADE;
DROP TABLE IF EXISTS items CASCADE;

-- -------------------------------------------------------------
-- 1. Таблица ролей (acl)
-- -------------------------------------------------------------
CREATE TABLE acl (
    id SERIAL PRIMARY KEY,
    role VARCHAR(45) NOT NULL
);

INSERT INTO acl (id, role) VALUES
(1, 'admin'),
(2, 'moder');

-- -------------------------------------------------------------
-- 2. Таблица пользователей (users)
-- -------------------------------------------------------------
CREATE TABLE users (
    id SERIAL PRIMARY KEY REFERENCES acl(id),
    login VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    acl SMALLINT DEFAULT 0 NOT NULL
);

INSERT INTO users (id, login, password, acl) VALUES
(1, 'root', 'jdp96n', 1);

-- -------------------------------------------------------------
-- 3. Таблица атрибутов героев (attribut)
-- -------------------------------------------------------------
CREATE TABLE attribut (
    attribute_id SERIAL PRIMARY KEY,
    attribute_name VARCHAR(35) UNIQUE NOT NULL,
    attribute_icon CHAR(25)
);

INSERT INTO attribut (attribute_id, attribute_name, attribute_icon) VALUES
(1, 'Strength', 'hero_strength.png'),
(2, 'Agility', 'hero_agility.png'),
(3, 'Intelligence', 'hero_intelligence.png'),
(4, 'Universal', 'hero_universal.png');

-- -------------------------------------------------------------
-- 4. Таблица героев (heroes)
-- -------------------------------------------------------------
CREATE TABLE heroes (
    id_hero SERIAL PRIMARY KEY,
    attribute_id INTEGER NOT NULL REFERENCES attribut(attribute_id),
    name_hero VARCHAR(255) NOT NULL,
    icon_hero TEXT NOT NULL,
    complexity SMALLINT,
    crop_hero TEXT
);

INSERT INTO heroes (id_hero, attribute_id, name_hero, icon_hero, complexity, crop_hero) VALUES
(1, 4, 'Abaddon', 'abaddon.png', 1, 'Abaddon.png'),
(2, 1, 'Alchemist', 'alchemist.png', 1, 'alchemist.png'),
(3, 3, 'Ancient Apparition', 'ancient_apparition.png', 2, 'Ancient_Apparition.png'),
(4, 2, 'Anti-Mage', 'antimage.png', 1, 'antimage.png'),
(5, 4, 'Arc Warden', 'arc_warden.png', 3, 'arc_warden.png'),
(6, 1, 'Axe', 'axe.png', 1, 'axe.png'),
(7, 4, 'Bane', 'bane.png', 2, 'bane.png'),
(8, 4, 'Batrider', 'batrider.png', 2, 'batrider.png'),
(9, 4, 'Beastmaster', 'beastmaster.png', 2, 'beastmaster.png'),
(10, 2, 'Bloodseeker', 'bloodseeker.png', 1, 'bloodseeker.png'),
(11, 2, 'Bounty Hunter', 'bounty_hunter.png', 1, 'bounty_hunter.png'),
(12, 4, 'Brewmaster', 'brewmaster.png', 3, 'brewmaster.png'),
(13, 1, 'Bristleback', 'bristleback.png', 1, 'bristleback.png'),
(14, 2, 'Broodmother', 'broodmother.png', 2, 'broodmother.png'),
(15, 1, 'Centaur Warrunner', 'centaur.png', 1, 'centaur.png'),
(16, 1, 'Chaos Knight', 'chaos_knight.png', 1, 'chaos_knight.png'),
(17, 3, 'Chen', 'chen.png', 3, 'chen.png'),
(18, 2, 'Clinkz', 'clinkz.png', 2, 'clinkz.png'),
(19, 1, 'Clockwerk', 'rattletrap.png', 2, 'rattletrap.png'),
(20, 3, 'Crystal Maiden', 'crystal_maiden.png', 1, 'crystal_maiden.png'),
(21, 3, 'Dark Seer', 'dark_seer.png', 1, 'dark_seer.png'),
(22, 3, 'Dark Willow', 'dark_willow.png', 2, 'dark_willow.png'),
(23, 1, 'Dawnbreaker', 'dawnbreaker.png', 1, 'dawnbreaker.png'),
(24, 4, 'Dazzle', 'dazzle.png', 1, 'dazzle.png'),
(25, 4, 'Death Prophet', 'death_prophet.png', 1, 'death_prophet.png'),
(26, 3, 'Disruptor', 'disruptor.png', 2, 'disruptor.png'),
(27, 1, 'Doom', 'doom_bringer.png', 2, 'doom_bringer.png'),
(28, 1, 'Dragon Knight', 'dragon_knight.png', 1, 'dragon_knight.png'),
(29, 2, 'Drow Ranger', 'drow_ranger.png', 1, 'drow_ranger.png'),
(30, 1, 'Earth Spirit', 'earth_spirit.png', 3, 'earth_spirit.png'),
(31, 1, 'Earthshaker', 'earthshaker.png', 2, 'earthshaker.png'),
(32, 1, 'Elder Titan', 'elder_titan.png', 2, 'elder_titan.png'),
(33, 2, 'Ember Spirit', 'ember_spirit.png', 2, 'ember_spirit.png'),
(34, 3, 'Enchantress', 'enchantress.png', 2, 'enchantress.png'),
(35, 4, 'Enigma', 'enigma.png', 2, 'enigma.png'),
(36, 2, 'Faceless Void', 'faceless_void.png', 2, 'faceless_void.png'),
(37, 3, 'Grimstroke', 'grimstroke.png', 2, 'grimstroke.png'),
(38, 2, 'Gyrocopter', 'gyrocopter.png', 1, 'gyrocopter.png'),
(39, 2, 'Hoodwink', 'hoodwink.png', 2, 'hoodwink.png'),
(40, 1, 'Huskar', 'huskar.png', 1, 'huskar.png'),
(41, 3, 'Invoker', 'invoker.png', 3, 'invoker.png'),
(42, 4, 'Io', 'wisp.png', 3, 'wisp.png'),
(43, 3, 'Jakiro', 'jakiro.png', 1, 'jakiro.png'),
(44, 2, 'Juggernaut', 'juggernaut.png', 1, 'juggernaut.png'),
(45, 3, 'Keeper of the Light', 'keeper_of_the_light.png', 2, 'keeper_of_the_light.png'),
(46, 2, 'Kez', 'kez.png', 3, 'kez.png'),
(47, 1, 'Kunkka', 'kunkka.png', 2, 'kunkka.png'),
(48, 1, 'Largo', 'largo.png', 2, 'largo.png'),
(49, 1, 'Legion Commander', 'legion_commander.png', 1, 'legion_commander.png'),
(50, 3, 'Leshrac', 'leshrac.png', 1, 'leshrac.png'),
(51, 3, 'Lich', 'lich.png', 1, 'lich.png'),
(52, 2, 'Mirana', 'mirana.png', 2, 'mirana.png'),
(53, 2, 'Morphling', 'morphling.png', 3, 'morphling.png'),
(54, 2, 'Shadow Fiend', 'nevermore.png', 2, 'nevermore.png'),
(55, 2, 'Phantom Lancer', 'phantom_lancer.png', 2, 'phantom_lancer.png'),
(56, 3, 'Puck', 'puck.png', 2, 'puck.png'),
(57, 1, 'Pudge', 'pudge.png', 2, 'pudge.png'),
(58, 2, 'Razor', 'razor.png', 1, 'razor.png'),
(59, 3, 'Storm Spirit', 'storm_spirit.png', 2, 'storm_spirit.png'),
(60, 1, 'Sven', 'sven.png', 1, 'sven.png'),
(61, 1, 'Tiny', 'tiny.png', 2, 'tiny.png'),
(62, 2, 'Vengeful Spirit', 'vengefulspirit.png', 1, 'vengefulspirit.png'),
(63, 3, 'Zeus', 'zuus.png', 1, 'zuus.png'),
(64, 3, 'Lina', 'lina.png', 1, 'lina.png'),
(65, 3, 'Lion', 'lion.png', 1, 'lion.png'),
(66, 3, 'Shadow Shaman', 'shadow_shaman.png', 1, 'shadow_shaman.png'),
(67, 1, 'Slardar', 'slardar.png', 1, 'slardar.png'),
(68, 1, 'Tidehunter', 'tidehunter.png', 1, 'tidehunter.png'),
(69, 3, 'Witch Doctor', 'witch_doctor.png', 1, 'witch_doctor.png'),
(70, 2, 'Riki', 'riki.png', 1, 'riki.png'),
(71, 3, 'Tinker', 'tinker.png', 2, 'tinker.png'),
(72, 2, 'Sniper', 'sniper.png', 1, 'sniper.png'),
(73, 3, 'Necrophos', 'necrolyte.png', 1, 'necrolyte.png'),
(74, 3, 'Warlock', 'warlock.png', 1, 'warlock.png'),
(75, 3, 'Queen of Pain', 'queenofpain.png', 2, 'queenofpain.png'),
(76, 2, 'Phantom Assassin', 'phantom_assassin.png', 1, 'phantom_assassin.png'),
(77, 1, 'Wraith King', 'skeleton_king.png', 1, 'skeleton_king.png'),
(78, 3, 'Pugna', 'pugna.png', 2, 'pugna.png'),
(79, 2, 'Templar Assassin', 'templar_assassin.png', 2, 'templar_assassin.png'),
(80, 2, 'Viper', 'viper.png', 1, 'viper.png'),
(81, 2, 'Luna', 'luna.png', 1, 'luna.png'),
(82, 1, 'Lifestealer', 'life_stealer.png', 2, 'life_stealer.png'),
(83, 1, 'Omniknight', 'omniknight.png', 1, 'omniknight.png'),
(84, 1, 'Night Stalker', 'night_stalker.png', 1, 'night_stalker.png'),
(85, 2, 'Weaver', 'weaver.png', 2, 'weaver.png'),
(86, 2, 'Spectre', 'spectre.png', 2, 'spectre.png'),
(87, 2, 'Ursa', 'ursa.png', 1, 'ursa.png'),
(88, 1, 'Spirit Breaker', 'spirit_breaker.png', 1, 'spirit_breaker.png'),
(89, 3, 'Silencer', 'silencer.png', 2, 'silencer.png'),
(90, 3, 'Outworld Devourer', 'obsidian_destroyer.png', 2, 'obsidian_destroyer.png'),
(91, 1, 'Lycan', 'lycan.png', 2, 'lycan.png'),
(92, 3, 'Shadow Demon', 'shadow_demon.png', 2, 'shadow_demon.png'),
(93, 2, 'Lone Druid', 'lone_druid.png', 3, 'lone_druid.png'),
(94, 2, 'Meepo', 'meepo.png', 3, 'meepo.png'),
(95, 1, 'Treant Protector', 'treant.png', 2, 'treant.png'),
(96, 1, 'Ogre Magi', 'ogre_magi.png', 1, 'ogre_magi.png'),
(97, 1, 'Undying', 'undying.png', 1, 'undying.png'),
(98, 3, 'Rubick', 'rubick.png', 3, 'rubick.png'),
(99, 2, 'Naga Siren', 'naga_siren.png', 2, 'naga_siren.png'),
(100, 2, 'Slark', 'slark.png', 2, 'slark.png'),
(101, 2, 'Medusa', 'medusa.png', 1, 'medusa.png'),
(102, 2, 'Troll Warlord', 'troll_warlord.png', 2, 'troll_warlord.png'),
(103, 1, 'Timbersaw', 'shredder.png', 2, 'shredder.png'),
(104, 1, 'Tusk', 'tusk.png', 1, 'tusk.png'),
(105, 3, 'Skywrath Mage', 'skywrath_mage.png', 1, 'skywrath_mage.png'),
(106, 1, 'Underlord', 'abyssal_underlord.png', 2, 'abyssal_underlord.png'),
(107, 2, 'Terrorblade', 'terrorblade.png', 2, 'terrorblade.png'),
(108, 1, 'Phoenix', 'phoenix.png', 2, 'phoenix.png'),
(109, 3, 'Oracle', 'oracle.png', 3, 'oracle.png'),
(110, 3, 'Winter Wyvern', 'winter_wyvern.png', 2, 'winter_wyvern.png'),
(111, 2, 'Monkey King', 'monkey_king.png', 2, 'monkey_king.png'),
(112, 1, 'Mars', 'mars.png', 1, 'mars.png'),
(113, 3, 'Ring Master', 'ringmaster.png', 2, 'ringmaster.png'),
(114, 1, 'Primal Beast', 'primal_beast.png', 1, 'primal_beast.png'),
(115, 3, 'Muerta', 'muerta.png', 1, 'muerta.png'),
(116, 4, 'Magnus', 'magnataur.png', 2, 'magnataur.png'),
(117, 4, 'Marci', 'marci.png', 2, 'marci.png'),
(118, 4, 'Nature''s Prophet', 'furion.png', 2, 'furion.png'),
(119, 4, 'Nyx Assassin', 'nyx_assassin.png', 2, 'nyx_assassin.png'),
(120, 4, 'Pangolier', 'pangolier.png', 2, 'pangolier.png'),
(121, 4, 'Sand King', 'sand_king.png', 2, 'sand_king.png'),
(122, 4, 'Snapfire', 'snapfire.png', 1, 'snapfire.png'),
(123, 4, 'Techies', 'techies.png', 2, 'techies.png'),
(124, 4, 'Venomancer', 'venomancer.png', 1, 'venomancer.png'),
(125, 4, 'Visage', 'visage.png', 3, 'visage.png'),
(126, 4, 'Void Spirit', 'void_spirit.png', 2, 'void_spirit.png'),
(127, 4, 'Windranger', 'windrunner.png', 2, 'windrunner.png');

-- -------------------------------------------------------------
-- 5. Таблица характеристик героев (heroes_stats)
-- -------------------------------------------------------------
CREATE TABLE heroes_stats (
    id SERIAL PRIMARY KEY,
    id_hero INTEGER UNIQUE NOT NULL REFERENCES heroes(id_hero),
    description_hero TEXT NOT NULL,
    full_description TEXT NOT NULL,
    roles JSON NOT NULL,
    stats JSON NOT NULL,
    attack_type VARCHAR(255) NOT NULL
);

INSERT INTO heroes_stats (id, id_hero, description_hero, full_description, roles, stats, attack_type) VALUES
(4, 1,
$$Abaddon, способный лечиться за счёт вражеских атак, может пережить почти любое нападение. Он всегда готов вклиниться в битву, закрывая союзников щитом и запуская обоюдоострые витки мглы, которыми он увечит врагов и исцеляет товарищей.$$,
$$Род Аверно питает купель — разлом в земной тверди, который испускает загадочную энергию на протяжении поколений. Каждого новорождённого семьи окунают в этот тёмный туман, даруя тем самым связь с их землёй и её загадочной силой. Дети растут с непреклонной верой в защиту семейных ценностей и традиций земли, но на самом деле они охраняют саму купель, истинные намерения которой неизвестны.

Когда новорождённый Abaddon проходил обряд крещения, что-то пошло не так. В глазах малыша сверкнула искра разума, испугавшая всех присутствовавших и заставившая жрецов шептаться. Его растили, дабы он пошёл по пути всех отпрысков рода: война и защита родины во главе армии. Но сам Abaddon уделял этому не так много внимания. Пока другие тренировались в обращении с оружием, он медитировал у купели. Он глубоко вдыхал тёмный туман, учась быть единым с той силой, что протекала глубоко под землёй его дома. В конечном счёте он стал порождением чёрного тумана.

Род Аверно неодобрительно отнёсся к такому решению, обвиняя его в пренебрежении обязанностями. Но все эти обвинения прекратились, когда Abaddon вступил в свою первую битву и показал ту обретённую власть над жизнью и смертью, о которой другие члены рода не могли и мечтать.$$,
'{"core":1,"support":2,"burst":0,"control":0,"jungle":0,"tank":2,"escape":0,"siege":0,"initiation":0}',
'{"damage":"49-59","attack_interval":1.5,"range":150,"projectile_speed":900,"armor":2.7,"magic_resist":25,"move_speed":325,"turn_rate":0,"vision":"1800/800"}',
'0'),

(6, 2,
$$Преданность священной алхимии была традицией рода Темноваров, но никто ещё никогда не показывал столько изобретательности, амбиций и безрассудства, сколько проявил юный Раззил. Повзрослев, он оставил семейное дело и решил попробовать себя в производстве золота.

В присущей ему манере он объявил, что обратит в золото целую гору. Спустя два десятилетия исследований, вложений и подготовок он с треском провалился, попав за решётку за множественные разрушения, причинённые экспериментом. Однако Раззил был не из робкого десятка и тщательно обдумывал варианты побега, чтобы продолжить свои исследования.

Когда его новым сокамерником оказался свирепый великан-людоед, алхимик увидел в нем столь желанную возможность для побега. Уговорив гиганта не съедать его, Раззил начал тщательно составлять настойку из плесени и мха, найденных во время исправительных работ. Через неделю она созрела. Когда великан выпил зелье, он впал в ослепительную ярость, разорвал железные прутья, разнёс стены и перебил всю стражу.

Скоро они затерялись где-то в лесу, окружавшем город, оставив за собой следы разрушений и никаких признаков погони. Когда действие тоника отошло, людоед чувствовал себя вполне хорошо и выглядел счастливым и вполне энергичным. Решив работать вместе, с тех пор парочка собирает материалы, необходимые Раззилу, чтобы в очередной раз попытать удачу.
Закрыть историю
Alchemist, синтезирующий дополнительные средства за каждое убийство, с лёгкостью получает необходимое вооружение. Он сражается во имя своей жадности, используя едкую кислоту и запас нестабильных химикатов.$$,
$$Преданность священной алхимии была традицией рода Темноваров, но никто ещё никогда не показывал столько изобретательности, амбиций и безрассудства, сколько проявил юный Раззил. Повзрослев, он оставил семейное дело и решил попробовать себя в производстве золота.

В присущей ему манере он объявил, что обратит в золото целую гору. Спустя два десятилетия исследований, вложений и подготовок он с треском провалился, попав за решётку за множественные разрушения, причинённые экспериментом. Однако Раззил был не из робкого десятка и тщательно обдумывал варианты побега, чтобы продолжить свои исследования.

Когда его новым сокамерником оказался свирепый великан-людоед, алхимик увидел в нем столь желанную возможность для побега. Уговорив гиганта не съедать его, Раззил начал тщательно составлять настойку из плесени и мха, найденных во время исправительных работ. Через неделю она созрела. Когда великан выпил зелье, он впал в ослепительную ярость, разорвал железные прутья, разнёс стены и перебил всю стражу.

Скоро они затерялись где-то в лесу, окружавшем город, оставив за собой следы разрушений и никаких признаков погони. Когда действие тоника отошло, людоед чувствовал себя вполне хорошо и выглядел счастливым и вполне энергичным. Решив работать вместе, с тех пор парочка собирает материалы, необходимые Раззилу, чтобы в очередной раз попытать удачу.$$,
'{"core":2,"support":1,"burst":1,"control":1,"jungle":0,"tank":2,"escape":0,"siege":0,"initiation":1}',
'{"damage":"50-56","attack_interval":1.7,"range":150,"projectile_speed":900,"armor":3.2,"magic_resist":25,"move_speed":290,"turn_rate":0,"vision":"1800/800"}',
'0'),

(13, 4,
$$Если Anti-Mage наберёт полную силу, мало кто сможет его остановить. Он способен забирать у врагов ману каждым ударом или телепортироваться на небольшие расстояния, что не позволяет врагам загнать его в угол.$$,
$$Монахи Турстаркури наблюдали за неровными долинами, раскинувшимися под их горным монастырем, в то время, как вторженцы, волна за волной, набегали на стоявшие у подножья королевства. Аскетичные, прагматичные, они пребывали в медитации, не знавшей никаких богов, засев в своем отрешенном от суетного мира высокогорном гнезде. Потом грянул легион Мертвого бога — крестоносцы, уничтожающие все местные культы и заменяющие их своей верой, родом из земель, известных лишь безжалостностью и тысячелетними войнами. Легионы мертвецов осадили Турстаркури. Две недели монастырь едва сдерживал натиск врагов, а те немногие монахи, что решили разузнать, в чем дело, восприняли нападение как попытку бесовских иллюзий отвлечь их от медитации. Они были убиты прямо на своих шелковых подстилках. Выжил лишь один молодой послушник — пилигрим, пришедший в поисках мудрости, но еще не принятый в монастырь. С ужасом он смотрел за тем, как монахи, которым он еще недавно подавал чаи и травы, гибли на своих местах, а потом присоединялись к рядам служителей Мертвого бога. Схватив охапку ценнейших священных писаний, он бежал в более безопасное место, поклявшись не только искоренить армию колдунов Мертвого бога, но и положить конец любой, какой бы то ни было, магии.$$,
'{"core":3,"support":0,"burst":1,"control":0,"jungle":0,"tank":0,"escape":3,"siege":0,"initiation":0}',
'{"damage":"54-58","attack_interval":1.4,"range":150,"projectile_speed":0,"armor":6.2,"magic_resist":25,"move_speed":315,"turn_rate":0,"vision":"1800/800"}',
'0'),

(14, 5,
$$Зет, Arc Warden — разбитый осколок той древней мощи, что породила самих Древних, и он намерен прекратить схватку Света и Тьмы. Сковывайте одиноких врагов потоками энергии или искажайте пространство, создавая защитное поле для союзников. Призывайте призрачные искры, нападающие на приблизившихся противников, или же полную копию самого себя со всеми предметами и способностями, способную сокрушить неприятеля.$$,
$$До начала всего в абсолютной пустоте обитала единая сущность — первородный разум. Бесконечный, невероятный, он следовал лишь своим загадочным целям. Когда раздался гром, ознаменовавший создание вселенной, первородный разум раскололся. Два самых крупных из его осколков, которые много времени спустя прозовут Светом и Тьмой, открыли друг в друге злейших врагов и начали искажать всё сущее, лишь бы истребить соперника.

Война стала угрожать существованию едва зародившегося космоса, и тогда третий осколок изъявил желание вмешаться. Чистый разум, назвавший себя Зет, хотел прекратить этот хаос и восстановить единство бытия. Поражённый непримиримостью братьев, он собрал все свои силы и одной яркой вспышкой одолел обе враждующие стороны. Два противника были сжаты воедино, пока не образовали космическое тело, отправленное кружить вокруг неизвестной планеты в далёком краю вселенной. Зет почти полностью лишился сил, но во вселенной воцарился порядок. Обратив свой взор на созданную темницу, Зет направил остатки сил на её охрану. Многие тысячелетия страж оставался непоколебимым.

Планета расцветала, полная жизни и сует, не ведая об опасности, таящейся под поверхностью ночного светила, и стараниях Зета её сдержать. Но внутренняя схватка дошла до такой силы, что поверхность спутника начала трещать по швам, и истощённых сил Зета оказалось недостаточно. Луна разорвалась на кусочки и выпустила на волю древних узников, жаждущих битвы.

Взрыв отбросил Зета на далёкий край галактики, а несовместимые силы бывших узников преобразили его сущность. Он разбился на множество частей, лишённых единой формы и мысли, удерживаемых лишь энергией парящего разума. Стараясь подавить чувство собственной разрозненности, Зет устремился в сторону возобновившейся битвы братьев. Многочисленные осколки его воли были едины в одной мысли: чтобы положить конец вечной войне, призраков первородного разума нужно либо объединить, либо уничтожить...$$,
'{"core":3,"support":0,"burst":1,"control":0,"jungle":0,"tank":0,"escape":3,"siege":0,"initiation":0}',
'{"damage":"52-58","attack_interval":1.7,"range":625,"projectile_speed":900,"armor":3.7,"magic_resist":25,"move_speed":300,"turn_rate":0,"vision":"1800/800"}',
'1'),

(15, 6,
$$Axe рубит одного врага за другим, неизменно ступая впереди своей команды. Он вынуждает противников вступить в бой, а затем отвечает на их удары смертоносными взмахами топора. Нещадно круша ослабленных врагов, он всегда несётся вперёд.$$,
$$Ещё будучи рядовым бугаем в армии Красного тумана, Могул-хан положил глаз на генеральский титул. Битву за битвой он самыми кровавыми способами доказывал собственное превосходство. Облегчало подъём в чинах и то, что без тени сомнения он мог обезглавить старшего по званию. В семилетней кампании на Тысячеболотье Могул-хан отличился в кровопролитных бойнях, и звезда его славы засияла еще ярче, но число соратников неизменно сокращалось. В ночь безоговорочной победы он провозгласил себя генералом Красного тумана, присвоив себе заодно и титул верховного военачальника. Однако теперь в его отряде не значилось ни одного воина. Множество бойцов было повержено врагом, но и от его топора погибло достаточно. Стоит ли говорить, что большинство солдат теперь ни за что не переманить под его знамена? Но Могул-хана это совсем не смущает, ведь он знает: один в поле воин.$$,
'{"core":1,"support":0,"burst":0,"control":2,"jungle":0,"tank":3,"escape":0,"siege":0,"initiation":3}',
'{"damage":"56-60","attack_interval":1.7,"range":150,"projectile_speed":900,"armor":3,"magic_resist":25,"move_speed":315,"turn_rate":0.6,"vision":"1800/800"}',
'0'),

(18, 7,
'',
'',
'{"core":1,"support":0,"burst":0,"control":0,"jungle":0,"tank":0,"escape":0,"siege":0,"initiation":0}',
'{"damage":"45-50","attack_interval":1.7,"range":150,"projectile_speed":900,"armor":2.5,"magic_resist":25,"move_speed":100,"turn_rate":0.6,"vision":"1800/800"}',
'0'),

(7, 3,
$$Ancient Apparition, способный запустить мощный заряд льда через всё поле битвы, может заморозить раненых врагов до смерти, где бы те ни находились. Он держит врагов в напряжении, замедляя их и помогая своим союзникам.$$,
$$Древний дух Калдр — образ, скрытый за пределами времени. Он возник из холодной, бесконечной пустоты, что предшествует вселенной и ждет её конца. Калдр был, Калдр есть, Калдр будет... И та его мощь, что мы видим в нашем мире, — лишь слабое увядшее эхо настоящего, вечного Калдра. Говорят, что чем древнее космос и чем ближе его конец, тем страшнее будет могущество Калдра, и иссякающая вечность принесёт духу молодость и силу. И тогда его ледяная хватка остановит всё сущее, и образ его начнёт источать ужасающее сияние; и он больше не будет всего лишь духом.$$,
'{"core":0,"support":2,"burst":1,"control":1,"jungle":0,"tank":0,"escape":0,"siege":0,"initiation":0}',
'{"damage":"44-54","attack_interval":1.7,"range":675,"projectile_speed":1250,"armor":2.3,"magic_resist":25,"move_speed":285,"turn_rate":0,"vision":"1800/800"}',
'1');

-- -------------------------------------------------------------
-- 6. Таблица способностей (ability)
-- -------------------------------------------------------------
CREATE TABLE ability (
    id_ability SERIAL PRIMARY KEY,
    name_ability TEXT NOT NULL,
    description_ability TEXT NOT NULL,
    icon_ability TEXT NOT NULL
);

-- -------------------------------------------------------------
-- 7. Таблица патчей (pathes)
-- -------------------------------------------------------------
CREATE TABLE pathes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    is_major BOOLEAN DEFAULT FALSE,
    patch_img_url VARCHAR(512)
);

-- -------------------------------------------------------------
-- 8. Таблица предметов (items)
-- -------------------------------------------------------------
CREATE TABLE items (
    id SERIAL PRIMARY KEY,
    internal_name VARCHAR(128) UNIQUE NOT NULL,
    display_name VARCHAR(128),
    is_neutral BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO items (internal_name, display_name, is_neutral) VALUES
('#DOTA_Patch_7_33_Neutral_Tokens_Title', '#Dota Patch 7 33 Neutral Tokens Title', TRUE),
('#DOTA_Patch_7_33b_Neutral_Tokens_Title', '#Dota Patch 7 33B Neutral Tokens Title', TRUE),
('#DOTA_Patch_7_33c_Item_Lifesteal_Title', '#Dota Patch 7 33C Item Lifesteal Title', FALSE),
('#DOTA_Patch_7_38_Crafting_Title', '#Dota Patch 7 38 Crafting Title', TRUE),
('#DOTA_Patch_7_38_Item_Tiers_Title', '#Dota Patch 7 38 Item Tiers Title', TRUE);

-- -------------------------------------------------------------
-- 9. Синхронизация последовательностей (автоинкрементов)
-- -------------------------------------------------------------
SELECT setval('acl_id_seq', COALESCE((SELECT MAX(id) FROM acl), 1));
SELECT setval('users_id_seq', COALESCE((SELECT MAX(id) FROM users), 1));
SELECT setval('attribut_attribute_id_seq', COALESCE((SELECT MAX(attribute_id) FROM attribut), 1));
SELECT setval('heroes_id_hero_seq', COALESCE((SELECT MAX(id_hero) FROM heroes), 1));
SELECT setval('heroes_stats_id_seq', COALESCE((SELECT MAX(id) FROM heroes_stats), 1));
SELECT setval('items_id_seq', COALESCE((SELECT MAX(id) FROM items), 1));
