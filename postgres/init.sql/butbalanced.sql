--
-- PostgreSQL database dump
--

-- \restrict 2htRxzi7BbYglSqM6isWe06YL1Ft7yNQYKhlUzCqaPvsQ5PyVxLONloCz0XV5de

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

-- Started on 2026-08-17 20:07:04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- TOC entry 5072 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 226 (class 1259 OID 17330)
-- Name: ability; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.ability (
    id_ability integer NOT NULL,
    name_ability text NOT NULL,
    description_ability text NOT NULL,
    icon_ability text NOT NULL
);


ALTER TABLE public.ability OWNER TO root;

--
-- TOC entry 225 (class 1259 OID 17329)
-- Name: ability_id_ability_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.ability_id_ability_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ability_id_ability_seq OWNER TO root;

--
-- TOC entry 5073 (class 0 OID 0)
-- Dependencies: 225
-- Name: ability_id_ability_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.ability_id_ability_seq OWNED BY public.ability.id_ability;


--
-- TOC entry 220 (class 1259 OID 16431)
-- Name: acl; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.acl (
    id integer NOT NULL,
    role character varying(45) NOT NULL
);


ALTER TABLE public.acl OWNER TO root;

--
-- TOC entry 222 (class 1259 OID 17143)
-- Name: attribut; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.attribut (
    attribute_id integer NOT NULL,
    attribute_name character varying(35) NOT NULL,
    attribute_icon character(25)
);


ALTER TABLE public.attribut OWNER TO root;

--
-- TOC entry 224 (class 1259 OID 17296)
-- Name: heroes; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.heroes (
    id_hero integer CONSTRAINT hero_id_hero_not_null NOT NULL,
    attribute_id integer CONSTRAINT hero_attribute_id_not_null NOT NULL,
    name_hero character varying(255) CONSTRAINT hero_name_hero_not_null NOT NULL,
    icon_hero text CONSTRAINT hero_icon_hero_not_null NOT NULL,
    complexity smallint,
    crop_hero text
);


ALTER TABLE public.heroes OWNER TO root;

--
-- TOC entry 223 (class 1259 OID 17295)
-- Name: hero_id_hero_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.hero_id_hero_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hero_id_hero_seq OWNER TO root;

--
-- TOC entry 5074 (class 0 OID 0)
-- Dependencies: 223
-- Name: hero_id_hero_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.hero_id_hero_seq OWNED BY public.heroes.id_hero;


--
-- TOC entry 228 (class 1259 OID 17350)
-- Name: heroes_stats; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.heroes_stats (
    id integer CONSTRAINT heros_stats_id_not_null NOT NULL,
    id_hero integer CONSTRAINT heros_stats_id_hero_not_null NOT NULL,
    description_hero text CONSTRAINT heros_stats_description_hero_not_null NOT NULL,
    full_description text CONSTRAINT heros_stats_short_description_hero_not_null NOT NULL,
    roles json CONSTRAINT heros_stats_roles_not_null NOT NULL,
    stats json CONSTRAINT heros_stats_stats_not_null NOT NULL,
    attack_type character varying(255) NOT NULL
);


ALTER TABLE public.heroes_stats OWNER TO root;

--
-- TOC entry 227 (class 1259 OID 17349)
-- Name: heros_stats_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.heros_stats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.heros_stats_id_seq OWNER TO root;

--
-- TOC entry 5075 (class 0 OID 0)
-- Dependencies: 227
-- Name: heros_stats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.heros_stats_id_seq OWNED BY public.heroes_stats.id;


--
-- TOC entry 221 (class 1259 OID 17131)
-- Name: pathes; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.pathes (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    is_major boolean DEFAULT false,
    patch_img_url character varying(512)
);


ALTER TABLE public.pathes OWNER TO root;

--
-- TOC entry 219 (class 1259 OID 16408)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    login character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    acl smallint DEFAULT '0'::smallint NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 4885 (class 2604 OID 17333)
-- Name: ability id_ability; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ability ALTER COLUMN id_ability SET DEFAULT nextval('public.ability_id_ability_seq'::regclass);


--
-- TOC entry 4884 (class 2604 OID 17299)
-- Name: heroes id_hero; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.heroes ALTER COLUMN id_hero SET DEFAULT nextval('public.hero_id_hero_seq'::regclass);


--
-- TOC entry 4886 (class 2604 OID 17353)
-- Name: heroes_stats id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.heroes_stats ALTER COLUMN id SET DEFAULT nextval('public.heros_stats_id_seq'::regclass);


--
-- TOC entry 5064 (class 0 OID 17330)
-- Dependencies: 226
-- Data for Name: ability; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.ability (id_ability, name_ability, description_ability, icon_ability) FROM stdin;
\.


--
-- TOC entry 5058 (class 0 OID 16431)
-- Dependencies: 220
-- Data for Name: acl; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.acl (id, role) FROM stdin;
1	admin
2	moder
\.


--
-- TOC entry 5060 (class 0 OID 17143)
-- Dependencies: 222
-- Data for Name: attribut; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.attribut (attribute_id, attribute_name, attribute_icon) FROM stdin;
1	Strength	hero_strength.png
4	Universal	hero_universal.png
3	Intelligence	hero_intelligence.png
2	Agility	hero_agility.png
\.


--
-- TOC entry 5062 (class 0 OID 17296)
-- Dependencies: 224
-- Data for Name: heroes; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.heroes (id_hero, attribute_id, name_hero, icon_hero, complexity, crop_hero) FROM stdin;
1	4	Abaddon	abaddon.png	1	Abaddon.png
2	1	Alchemist	alchemist.png	1	alchemist.png
38	2	Gyrocopter	gyrocopter.png	1	gyrocopter.png
40	1	Huskar	huskar.png	1	huskar.png
4	2	Anti-Mage	antimage.png	1	antimage.png
8	4	Batrider	batrider.png	2	batrider.png
9	4	Beastmaster	beastmaster.png	2	beastmaster.png
11	2	Bounty Hunter	bounty_hunter.png	1	bounty_hunter.png
10	2	Bloodseeker	bloodseeker.png	1	bloodseeker.png
12	4	Brewmaster	brewmaster.png	3	brewmaster.png
13	1	Bristleback	bristleback.png	1	bristleback.png
14	2	Broodmother	broodmother.png	2	broodmother.png
15	1	Centaur Warrunner	centaur.png	1	centaur.png
16	1	Chaos Knight	chaos_knight.png	1	chaos_knight.png
17	3	Chen	chen.png	3	chen.png
18	2	Clinkz	clinkz.png	2	clinkz.png
19	1	Clockwerk	rattletrap.png	2	rattletrap.png
21	3	Dark Seer	dark_seer.png	1	dark_seer.png
20	3	Crystal Maiden	crystal_maiden.png	1	crystal_maiden.png
22	3	Dark Willow	dark_willow.png	2	dark_willow.png
23	1	Dawnbreaker	dawnbreaker.png	1	dawnbreaker.png
24	4	Dazzle	dazzle.png	1	dazzle.png
25	4	Death Prophet	death_prophet.png	1	death_prophet.png
26	3	Disruptor	disruptor.png	2	disruptor.png
29	2	Drow Ranger	drow_ranger.png	1	drow_ranger.png
27	1	Doom	doom_bringer.png	2	doom_bringer.png
30	1	Earth Spirit	earth_spirit.png	3	earth_spirit.png
31	1	Earthshaker	earthshaker.png	2	earthshaker.png
32	1	Elder Titan	elder_titan.png	2	elder_titan.png
33	2	Ember Spirit	ember_spirit.png	2	ember_spirit.png
34	3	Enchantress	enchantress.png	2	enchantress.png
35	4	Enigma	enigma.png	2	enigma.png
36	2	Faceless Void	faceless_void.png	2	faceless_void.png
37	3	Grimstroke	grimstroke.png	2	grimstroke.png
39	2	Hoodwink	hoodwink.png	2	hoodwink.png
92	3	Shadow Demon	shadow_demon.png	2	shadow_demon.png
95	1	Treant Protector	treant.png	2	treant.png
99	2	Naga Siren	naga_siren.png	2	naga_siren.png
100	2	Slark	slark.png	2	slark.png
102	2	Troll Warlord	troll_warlord.png	2	troll_warlord.png
103	1	Timbersaw	shredder.png	2	shredder.png
106	1	Underlord	abyssal_underlord.png	2	abyssal_underlord.png
107	2	Terrorblade	terrorblade.png	2	terrorblade.png
108	1	Phoenix	phoenix.png	2	phoenix.png
110	3	Winter Wyvern	winter_wyvern.png	2	winter_wyvern.png
111	2	Monkey King	monkey_king.png	2	monkey_king.png
113	3	Ring Master	ringmaster.png	2	ringmaster.png
116	4	Magnus	magnataur.png	2	magnataur.png
5	4	Arc Warden	arc_warden.png	3	arc_warden.png
6	1	Axe	axe.png	1	axe.png
7	4	Bane	bane.png	2	bane.png
3	3	Ancient Apparition	ancient_apparition.png	2	Ancient_Apparition.png
28	1	Dragon Knight	dragon_knight.png	1	dragon_knight.png
41	3	Invoker	invoker.png	3	invoker.png
45	3	Keeper of the Light	keeper_of_the_light.png	2	keeper_of_the_light.png
47	1	Kunkka	kunkka.png	2	kunkka.png
48	1	Largo	largo.png	2	largo.png
52	2	Mirana	mirana.png	2	mirana.png
54	2	Shadow Fiend	nevermore.png	2	nevermore.png
55	2	Phantom Lancer	phantom_lancer.png	2	phantom_lancer.png
56	3	Puck	puck.png	2	puck.png
57	1	Pudge	pudge.png	2	pudge.png
59	3	Storm Spirit	storm_spirit.png	2	storm_spirit.png
61	1	Tiny	tiny.png	2	tiny.png
71	3	Tinker	tinker.png	2	tinker.png
75	3	Queen of Pain	queenofpain.png	2	queenofpain.png
78	3	Pugna	pugna.png	2	pugna.png
79	2	Templar Assassin	templar_assassin.png	2	templar_assassin.png
82	1	Lifestealer	life_stealer.png	2	life_stealer.png
85	2	Weaver	weaver.png	2	weaver.png
86	2	Spectre	spectre.png	2	spectre.png
89	3	Silencer	silencer.png	2	silencer.png
90	3	Outworld Devourer	obsidian_destroyer.png	2	obsidian_destroyer.png
91	1	Lycan	lycan.png	2	lycan.png
117	4	Marci	marci.png	2	marci.png
118	4	Nature's Prophet	furion.png	2	furion.png
119	4	Nyx Assassin	nyx_assassin.png	2	nyx_assassin.png
120	4	Pangolier	pangolier.png	2	pangolier.png
121	4	Sand King	sand_king.png	2	sand_king.png
123	4	Techies	techies.png	2	techies.png
126	4	Void Spirit	void_spirit.png	2	void_spirit.png
127	4	Windranger	windrunner.png	2	windrunner.png
43	3	Jakiro	jakiro.png	1	jakiro.png
44	2	Juggernaut	juggernaut.png	1	juggernaut.png
49	1	Legion Commander	legion_commander.png	1	legion_commander.png
50	3	Leshrac	leshrac.png	1	leshrac.png
51	3	Lich	lich.png	1	lich.png
58	2	Razor	razor.png	1	razor.png
60	1	Sven	sven.png	1	sven.png
62	2	Vengeful Spirit	vengefulspirit.png	1	vengefulspirit.png
63	3	Zeus	zuus.png	1	zuus.png
64	3	Lina	lina.png	1	lina.png
65	3	Lion	lion.png	1	lion.png
66	3	Shadow Shaman	shadow_shaman.png	1	shadow_shaman.png
67	1	Slardar	slardar.png	1	slardar.png
68	1	Tidehunter	tidehunter.png	1	tidehunter.png
69	3	Witch Doctor	witch_doctor.png	1	witch_doctor.png
70	2	Riki	riki.png	1	riki.png
72	2	Sniper	sniper.png	1	sniper.png
73	3	Necrophos	necrolyte.png	1	necrolyte.png
74	3	Warlock	warlock.png	1	warlock.png
76	2	Phantom Assassin	phantom_assassin.png	1	phantom_assassin.png
77	1	Wraith King	skeleton_king.png	1	skeleton_king.png
80	2	Viper	viper.png	1	viper.png
81	2	Luna	luna.png	1	luna.png
83	1	Omniknight	omniknight.png	1	omniknight.png
84	1	Night Stalker	night_stalker.png	1	night_stalker.png
87	2	Ursa	ursa.png	1	ursa.png
88	1	Spirit Breaker	spirit_breaker.png	1	spirit_breaker.png
96	1	Ogre Magi	ogre_magi.png	1	ogre_magi.png
97	1	Undying	undying.png	1	undying.png
101	2	Medusa	medusa.png	1	medusa.png
104	1	Tusk	tusk.png	1	tusk.png
105	3	Skywrath Mage	skywrath_mage.png	1	skywrath_mage.png
112	1	Mars	mars.png	1	mars.png
114	1	Primal Beast	primal_beast.png	1	primal_beast.png
115	3	Muerta	muerta.png	1	muerta.png
122	4	Snapfire	snapfire.png	1	snapfire.png
124	4	Venomancer	venomancer.png	1	venomancer.png
42	4	Io	wisp.png	3	wisp.png
46	2	Kez	kez.png	3	kez.png
53	2	Morphling	morphling.png	3	morphling.png
93	2	Lone Druid	lone_druid.png	3	lone_druid.png
94	2	Meepo	meepo.png	3	meepo.png
98	3	Rubick	rubick.png	3	rubick.png
109	3	Oracle	oracle.png	3	oracle.png
125	4	Visage	visage.png	3	visage.png
\.


--
-- TOC entry 5066 (class 0 OID 17350)
-- Dependencies: 228
-- Data for Name: heroes_stats; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.heroes_stats (id, id_hero, description_hero, full_description, roles, stats, attack_type) FROM stdin;
4	1	Abaddon, способный лечиться за счёт вражеских атак, может пережить почти любое нападение. Он всегда готов вклиниться в битву, закрывая союзников щитом и запуская обоюдоострые витки мглы, которыми он увечит врагов и исцеляет товарищей.	Род Аверно питает купель — разлом в земной тверди, который испускает загадочную энергию на протяжении поколений. Каждого новорождённого семьи окунают в этот тёмный туман, даруя тем самым связь с их землёй и её загадочной силой. Дети растут с непреклонной верой в защиту семейных ценностей и традиций земли, но на самом деле они охраняют саму купель, истинные намерения которой неизвестны.\r\n\r\nКогда новорождённый Abaddon проходил обряд крещения, что-то пошло не так. В глазах малыша сверкнула искра разума, испугавшая всех присутствовавших и заставившая жрецов шептаться. Его растили, дабы он пошёл по пути всех отпрысков рода: война и защита родины во главе армии. Но сам Abaddon уделял этому не так много внимания. Пока другие тренировались в обращении с оружием, он медитировал у купели. Он глубоко вдыхал тёмный туман, учась быть единым с той силой, что протекала глубоко под землёй его дома. В конечном счёте он стал порождением чёрного тумана.\r\n\r\nРод Аверно неодобрительно отнёсся к такому решению, обвиняя его в пренебрежении обязанностями. Но все эти обвинения прекратились, когда Abaddon вступил в свою первую битву и показал ту обретённую власть над жизнью и смертью, о которой другие члены рода не могли и мечтать.	{"core":1,"support":2,"burst":0,"control":0,"jungle":0,"tank":2,"escape":0,"siege":0,"initiation":0}	{"damage":"49-59","attack_interval":1.5,"range":150,"projectile_speed":900,"armor":2.7,"magic_resist":25,"move_speed":325,"turn_rate":0,"vision":"1800\\/800"}	0
6	2	Преданность священной алхимии была традицией рода Темноваров, но никто ещё никогда не показывал столько изобретательности, амбиций и безрассудства, сколько проявил юный Раззил. Повзрослев, он оставил семейное дело и решил попробовать себя в производстве золота.\r\n\r\nВ присущей ему манере он объявил, что обратит в золото целую гору. Спустя два десятилетия исследований, вложений и подготовок он с треском провалился, попав за решётку за множественные разрушения, причинённые экспериментом. Однако Раззил был не из робкого десятка и тщательно обдумывал варианты побега, чтобы продолжить свои исследования.\r\n\r\nКогда его новым сокамерником оказался свирепый великан-людоед, алхимик увидел в нем столь желанную возможность для побега. Уговорив гиганта не съедать его, Раззил начал тщательно составлять настойку из плесени и мха, найденных во время исправительных работ. Через неделю она созрела. Когда великан выпил зелье, он впал в ослепительную ярость, разорвал железные прутья, разнёс стены и перебил всю стражу.\r\n\r\nСкоро они затерялись где-то в лесу, окружавшем город, оставив за собой следы разрушений и никаких признаков погони. Когда действие тоника отошло, людоед чувствовал себя вполне хорошо и выглядел счастливым и вполне энергичным. Решив работать вместе, с тех пор парочка собирает материалы, необходимые Раззилу, чтобы в очередной раз попытать удачу.\r\nЗакрыть историю\r\nAlchemist, синтезирующий дополнительные средства за каждое убийство, с лёгкостью получает необходимое вооружение. Он сражается во имя своей жадности, используя едкую кислоту и запас нестабильных химикатов.	Преданность священной алхимии была традицией рода Темноваров, но никто ещё никогда не показывал столько изобретательности, амбиций и безрассудства, сколько проявил юный Раззил. Повзрослев, он оставил семейное дело и решил попробовать себя в производстве золота.\r\n\r\nВ присущей ему манере он объявил, что обратит в золото целую гору. Спустя два десятилетия исследований, вложений и подготовок он с треском провалился, попав за решётку за множественные разрушения, причинённые экспериментом. Однако Раззил был не из робкого десятка и тщательно обдумывал варианты побега, чтобы продолжить свои исследования.\r\n\r\nКогда его новым сокамерником оказался свирепый великан-людоед, алхимик увидел в нем столь желанную возможность для побега. Уговорив гиганта не съедать его, Раззил начал тщательно составлять настойку из плесени и мха, найденных во время исправительных работ. Через неделю она созрела. Когда великан выпил зелье, он впал в ослепительную ярость, разорвал железные прутья, разнёс стены и перебил всю стражу.\r\n\r\nСкоро они затерялись где-то в лесу, окружавшем город, оставив за собой следы разрушений и никаких признаков погони. Когда действие тоника отошло, людоед чувствовал себя вполне хорошо и выглядел счастливым и вполне энергичным. Решив работать вместе, с тех пор парочка собирает материалы, необходимые Раззилу, чтобы в очередной раз попытать удачу.	{"core":2,"support":1,"burst":1,"control":1,"jungle":0,"tank":2,"escape":0,"siege":0,"initiation":1}	{"damage":"50-56","attack_interval":1.7,"range":150,"projectile_speed":900,"armor":3.2,"magic_resist":25,"move_speed":290,"turn_rate":0,"vision":"1800\\/800"}	0
13	4	Если Anti-Mage наберёт полную силу, мало кто сможет его остановить. Он способен забирать у врагов ману каждым ударом или телепортироваться на небольшие расстояния, что не позволяет врагам загнать его в угол.	Монахи Турстаркури наблюдали за неровными долинами, раскинувшимися под их горным монастырем, в то время, как вторженцы, волна за волной, набегали на стоявшие у подножья королевства. Аскетичные, прагматичные, они пребывали в медитации, не знавшей никаких богов, засев в своем отрешенном от суетного мира высокогорном гнезде. Потом грянул легион Мертвого бога — крестоносцы, уничтожающие все местные культы и заменяющие их своей верой, родом из земель, известных лишь безжалостностью и тысячелетними войнами. Легионы мертвецов осадили Турстаркури. Две недели монастырь едва сдерживал натиск врагов, а те немногие монахи, что решили разузнать, в чем дело, восприняли нападение как попытку бесовских иллюзий отвлечь их от медитации. Они были убиты прямо на своих шелковых подстилках. Выжил лишь один молодой послушник — пилигрим, пришедший в поисках мудрости, но еще не принятый в монастырь. С ужасом он смотрел за тем, как монахи, которым он еще недавно подавал чаи и травы, гибли на своих местах, а потом присоединялись к рядам служителей Мертвого бога. Схватив охапку ценнейших священных писаний, он бежал в более безопасное место, поклявшись не только искоренить армию колдунов Мертвого бога, но и положить конец любой, какой бы то ни было, магии.	{"core":3,"support":0,"burst":1,"control":0,"jungle":0,"tank":0,"escape":3,"siege":0,"initiation":0}	{"damage":"54-58","attack_interval":1.4,"range":150,"projectile_speed":0,"armor":6.2,"magic_resist":25,"move_speed":315,"turn_rate":0,"vision":"1800\\/800"}	0
14	5	До начала всего в абсолютной пустоте обитала единая сущность — первородный разум. Бесконечный, невероятный, он следовал лишь своим загадочным целям. Когда раздался гром, ознаменовавший создание вселенной, первородный разум раскололся. Два самых крупных из его осколков, которые много времени спустя прозовут Светом и Тьмой, открыли друг в друге злейших врагов и начали искажать всё сущее, лишь бы истребить соперника.\r\n\r\nВойна стала угрожать существованию едва зародившегося космоса, и тогда третий осколок изъявил желание вмешаться. Чистый разум, назвавший себя Зет, хотел прекратить этот хаос и восстановить единство бытия. Поражённый непримиримостью братьев, он собрал все свои силы и одной яркой вспышкой одолел обе враждующие стороны. Два противника были сжаты воедино, пока не образовали космическое тело, отправленное кружить вокруг неизвестной планеты в далёком краю вселенной. Зет почти полностью лишился сил, но во вселенной воцарился порядок. Обратив свой взор на созданную темницу, Зет направил остатки сил на её охрану. Многие тысячелетия страж оставался непоколебимым.\r\n\r\nПланета расцветала, полная жизни и сует, не ведая об опасности, таящейся под поверхностью ночного светила, и стараниях Зета её сдержать. Но внутренняя схватка дошла до такой силы, что поверхность спутника начала трещать по швам, и истощённых сил Зета оказалось недостаточно. Луна разорвалась на кусочки и выпустила на волю древних узников, жаждущих битвы.\r\n\r\nВзрыв отбросил Зета на далёкий край галактики, а несовместимые силы бывших узников преобразили его сущность. Он разбился на множество частей, лишённых единой формы и мысли, удерживаемых лишь энергией парящего разума. Стараясь подавить чувство собственной разрозненности, Зет устремился в сторону возобновившейся битвы братьев. Многочисленные осколки его воли были едины в одной мысли: чтобы положить конец вечной войне, призраков первородного разума нужно либо объединить, либо уничтожить...\r\nЗакрыть историю\r\nЗет, Arc Warden — разбитый осколок той древней мощи, что породила самих Древних, и он намерен прекратить схватку Света и Тьмы. Сковывайте одиноких врагов потоками энергии или искажайте пространство, создавая защитное поле для союзников. Призывайте призрачные искры, нападающие на приблизившихся противников, или же полную копию самого себя со всеми предметами и способностями, способную сокрушить неприятеля.	До начала всего в абсолютной пустоте обитала единая сущность — первородный разум. Бесконечный, невероятный, он следовал лишь своим загадочным целям. Когда раздался гром, ознаменовавший создание вселенной, первородный разум раскололся. Два самых крупных из его осколков, которые много времени спустя прозовут Светом и Тьмой, открыли друг в друге злейших врагов и начали искажать всё сущее, лишь бы истребить соперника.\r\n\r\nВойна стала угрожать существованию едва зародившегося космоса, и тогда третий осколок изъявил желание вмешаться. Чистый разум, назвавший себя Зет, хотел прекратить этот хаос и восстановить единство бытия. Поражённый непримиримостью братьев, он собрал все свои силы и одной яркой вспышкой одолел обе враждующие стороны. Два противника были сжаты воедино, пока не образовали космическое тело, отправленное кружить вокруг неизвестной планеты в далёком краю вселенной. Зет почти полностью лишился сил, но во вселенной воцарился порядок. Обратив свой взор на созданную темницу, Зет направил остатки сил на её охрану. Многие тысячелетия страж оставался непоколебимым.\r\n\r\nПланета расцветала, полная жизни и сует, не ведая об опасности, таящейся под поверхностью ночного светила, и стараниях Зета её сдержать. Но внутренняя схватка дошла до такой силы, что поверхность спутника начала трещать по швам, и истощённых сил Зета оказалось недостаточно. Луна разорвалась на кусочки и выпустила на волю древних узников, жаждущих битвы.\r\n\r\nВзрыв отбросил Зета на далёкий край галактики, а несовместимые силы бывших узников преобразили его сущность. Он разбился на множество частей, лишённых единой формы и мысли, удерживаемых лишь энергией парящего разума. Стараясь подавить чувство собственной разрозненности, Зет устремился в сторону возобновившейся битвы братьев. Многочисленные осколки его воли были едины в одной мысли: чтобы положить конец вечной войне, призраков первородного разума нужно либо объединить, либо уничтожить...	{"core":3,"support":0,"burst":1,"control":0,"jungle":0,"tank":0,"escape":3,"siege":0,"initiation":0}	{"damage":"52-58","attack_interval":1.7,"range":625,"projectile_speed":900,"armor":3.7,"magic_resist":25,"move_speed":300,"turn_rate":0,"vision":"1800\\/800"}	1
15	6	Ещё будучи рядовым бугаем в армии Красного тумана, Могул-хан положил глаз на генеральский титул. Битву за битвой он самыми кровавыми способами доказывал собственное превосходство. Облегчало подъём в чинах и то, что без тени сомнения он мог обезглавить старшего по званию. В семилетней кампании на Тысячеболотье Могул-хан отличился в кровопролитных бойнях, и звезда его славы засияла еще ярче, но число соратников неизменно сокращалось. В ночь безоговорочной победы он провозгласил себя генералом Красного тумана, присвоив себе заодно и титул верховного военачальника. Однако теперь в его отряде не значилось ни одного воина. Множество бойцов было повержено врагом, но и от его топора погибло достаточно. Стоит ли говорить, что большинство солдат теперь ни за что не переманить под его знамена? Но Могул-хана это совсем не смущает, ведь он знает: один в поле воин.\r\nЗакрыть историю\r\nAxe рубит одного врага за другим, неизменно ступая впереди своей команды. Он вынуждает противников вступить в бой, а затем отвечает на их удары смертоносными взмахами топора. Нещадно круша ослабленных врагов, он всегда несётся вперёд.	Ещё будучи рядовым бугаем в армии Красного тумана, Могул-хан положил глаз на генеральский титул. Битву за битвой он самыми кровавыми способами доказывал собственное превосходство. Облегчало подъём в чинах и то, что без тени сомнения он мог обезглавить старшего по званию. В семилетней кампании на Тысячеболотье Могул-хан отличился в кровопролитных бойнях, и звезда его славы засияла еще ярче, но число соратников неизменно сокращалось. В ночь безоговорочной победы он провозгласил себя генералом Красного тумана, присвоив себе заодно и титул верховного военачальника. Однако теперь в его отряде не значилось ни одного воина. Множество бойцов было повержено врагом, но и от его топора погибло достаточно. Стоит ли говорить, что большинство солдат теперь ни за что не переманить под его знамена? Но Могул-хана это совсем не смущает, ведь он знает: один в поле воин.	{"core":1,"support":0,"burst":0,"control":2,"jungle":0,"tank":3,"escape":0,"siege":0,"initiation":3}	{"damage":"56-60","attack_interval":1.7,"range":150,"projectile_speed":900,"armor":3,"magic_resist":25,"move_speed":315,"turn_rate":0.6,"vision":"1800\\/800"}	0
18	7			{"core":1,"support":0,"burst":0,"control":0,"jungle":0,"tank":0,"escape":0,"siege":0,"initiation":0}	{"damage":"45-50","attack_interval":1.7,"range":150,"projectile_speed":900,"armor":2.5,"magic_resist":25,"move_speed":100,"turn_rate":0.6,"vision":"1800\\/800"}	0
7	3	Ancient Apparition, способный запустить мощный заряд льда через всё поле битвы, может заморозить раненых врагов до смерти, где бы те ни находились. Он держит врагов в напряжении, замедляя их и помогая своим союзникам.	Древний дух Калдр — образ, скрытый за пределами времени. Он возник из холодной, бесконечной пустоты, что предшествует вселенной и ждет её конца. Калдр был, Калдр есть, Калдр будет... И та его мощь, что мы видим в нашем мире, — лишь слабое увядшее эхо настоящего, вечного Калдра. Говорят, что чем древнее космос и чем ближе его конец, тем страшнее будет могущество Калдра, и иссякающая вечность принесёт духу молодость и силу. И тогда его ледяная хватка остановит всё сущее, и образ его начнёт источать ужасающее сияние; и он больше не будет всего лишь духом.	{"core":0,"support":2,"burst":1,"control":1,"jungle":0,"tank":0,"escape":0,"siege":0,"initiation":0}	{"damage":"44-54","attack_interval":1.7,"range":675,"projectile_speed":1250,"armor":2.3,"magic_resist":25,"move_speed":285,"turn_rate":0,"vision":"1800\\/800"}	1
\.


--
-- TOC entry 5059 (class 0 OID 17131)
-- Dependencies: 221
-- Data for Name: pathes; Type: TABLE DATA; Schema: public; Owner: root
--

COPY public.pathes (id, name, description, is_major, patch_img_url) FROM stdin;
\.


--
-- TOC entry 5057 (class 0 OID 16408)
-- Dependencies: 219
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, login, password, acl) FROM stdin;
1	root	jdp96n	1
\.


--
-- TOC entry 5076 (class 0 OID 0)
-- Dependencies: 225
-- Name: ability_id_ability_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.ability_id_ability_seq', 1, false);


--
-- TOC entry 5077 (class 0 OID 0)
-- Dependencies: 223
-- Name: hero_id_hero_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.hero_id_hero_seq', 967, true);


--
-- TOC entry 5078 (class 0 OID 0)
-- Dependencies: 227
-- Name: heros_stats_id_seq; Type: SEQUENCE SET; Schema: public; Owner: root
--

SELECT pg_catalog.setval('public.heros_stats_id_seq', 20, true);


--
-- TOC entry 4902 (class 2606 OID 17341)
-- Name: ability ability_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ability
    ADD CONSTRAINT ability_pkey PRIMARY KEY (id_ability);


--
-- TOC entry 4892 (class 2606 OID 16470)
-- Name: acl acl_id; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.acl
    ADD CONSTRAINT acl_id UNIQUE (id);


--
-- TOC entry 4896 (class 2606 OID 17152)
-- Name: attribut attribut_attribute_name_key; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.attribut
    ADD CONSTRAINT attribut_attribute_name_key UNIQUE (attribute_name);


--
-- TOC entry 4898 (class 2606 OID 17150)
-- Name: attribut attribut_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.attribut
    ADD CONSTRAINT attribut_pkey PRIMARY KEY (attribute_id);


--
-- TOC entry 4900 (class 2606 OID 17302)
-- Name: heroes hero_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.heroes
    ADD CONSTRAINT hero_pkey PRIMARY KEY (id_hero);


--
-- TOC entry 4904 (class 2606 OID 17362)
-- Name: heroes_stats heros_stats_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.heroes_stats
    ADD CONSTRAINT heros_stats_pkey PRIMARY KEY (id);


--
-- TOC entry 4894 (class 2606 OID 17141)
-- Name: pathes pathes_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.pathes
    ADD CONSTRAINT pathes_pkey PRIMARY KEY (id);


--
-- TOC entry 4906 (class 2606 OID 17412)
-- Name: heroes_stats unique_hero_stats; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.heroes_stats
    ADD CONSTRAINT unique_hero_stats UNIQUE (id_hero);


--
-- TOC entry 4888 (class 2606 OID 16421)
-- Name: users users_login_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_login_key UNIQUE (login);


--
-- TOC entry 4890 (class 2606 OID 16419)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4908 (class 2606 OID 17308)
-- Name: heroes hero_attribute_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.heroes
    ADD CONSTRAINT hero_attribute_id_fkey FOREIGN KEY (attribute_id) REFERENCES public.attribut(attribute_id);


--
-- TOC entry 4909 (class 2606 OID 17413)
-- Name: heroes_stats heroes_stats_id_hero_fkey; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.heroes_stats
    ADD CONSTRAINT heroes_stats_id_hero_fkey FOREIGN KEY (id_hero) REFERENCES public.heroes(id_hero);


--
-- TOC entry 4907 (class 2606 OID 16473)
-- Name: users users_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_fkey FOREIGN KEY (id) REFERENCES public.acl(id);


-- Completed on 2026-08-17 20:07:04

--
-- PostgreSQL database dump complete
--

-- \unrestrict 2htRxzi7BbYglSqM6isWe06YL1Ft7yNQYKhlUzCqaPvsQ5PyVxLONloCz0XV5de
