<?php
try {
    $heroes = getAllHeroes($db);
} catch (PDOException $e) {
    echo "Ошибка получения всех героев {$heroes["name_hero"]}: " .
        $e->getMessage() .
        "\n";
}
$attrIcon = [
    1 => "/cd-project/butbalanced/src/icons/hero_strength.png",
    2 => "/cd-project/butbalanced/src/icons/hero_agility.png",
    3 => "/cd-project/butbalanced/src/icons/hero_intelligence.png",
    4 => "/cd-project/butbalanced/src/icons/hero_universal.png",
];
?>
<div class="D36V-Zuy4P4h8Ogar6YWx" style="background-image: url(&quot;../src/GUI/greyfade.jpg&quot;);">
    <div class="_2e6SofGLw2c1BC_jtvEkxA aos-init aos-animate" data-aos="fade-in" data-aos-duration="1000">
        <div class="_3Rwf-tIc4zolgIsfDZnANp"></div>
        <div class="_2sbq3qLegMITf-QrO5_r2V">Выберите героя</div>
        <div class="Q3COYNMz-yGQhbg75PC1b">
            Список героев в Dota 2 огромен и безгранично разнообразен: здесь вы встретите
            и магов-тактиков, и свирепых громил, и хитроумных негодяев. Их невероятные
            способности и сокрушительные ульты непременно приведут вас к победе.
        </div>
    </div>
    <div class="_2dEVdxZ62mXK6Hscx85kfA">
        <div class="_1Mwn_qHzqFFzIkYjIkLGm4">Фильтр</div>
        <div class="_30qnjy6fkdTLNGj-OhqJZL">
            <div class="_1Y7hgUMU6SsAvmlZ_6l8Yf">Атрибут</div>
            <div class="N74aaCii0wv_Ody2YGY_w" style="background-image: url(&quot;../src/icons/filter-str-active.png&quot;);"></div>
            <div class="N74aaCii0wv_Ody2YGY_w" style="background-image: url(&quot;../src/icons/filter-agi-active.png&quot;);"></div>
            <div class="N74aaCii0wv_Ody2YGY_w" style="background-image: url(&quot;../src/icons/filter-int-active.png&quot;);"></div>
            <div class="N74aaCii0wv_Ody2YGY_w" style="background-image: url(&quot;../src/icons/filter-uni-active.png&quot;);"></div>
        </div>
        <div class="_30qnjy6fkdTLNGj-OhqJZL">
            <div class="_1Y7hgUMU6SsAvmlZ_6l8Yf">Сложность</div>
            <div class="N74aaCii0wv_Ody2YGY_w" style="background-image: url(&quot;../src/icons/filter-diamond.png&quot;);"></div>
            <div class="N74aaCii0wv_Ody2YGY_w" style="background-image: url(&quot;../src/icons/filter-diamond.png&quot;);"></div>
            <div class="N74aaCii0wv_Ody2YGY_w" style="background-image: url(&quot;../src/icons/filter-diamond.png&quot;);"></div>
        </div>
        <div class="_2P5FcSZSA8Mfr716u_X1kk">
            <div class="_2paN1PFQTqGDNSagtldD_J">
                <div class="_3TNwrPYCkAmX1yDBI0maL8" style="background-image: url(&quot;../src/icons/search.svg&quot;);"></div>
                <form>
                    <input id="search_hero" type="text" value>
                </form>
            </div>
        </div>
    </div>
    <div class="_2S5CeMYby2JgTcPBgqZsvi">Загрузка...</div>
    <div class="byzyncxBWllmkGgs_PcLL">Герои не найдены</div>
    <div class="_3LrTPTY1adWYh0ceoy0QFj">
        <?php foreach ($heroes as $hero) :
            $slug = strtolower($hero["name_hero"]);
            $slug = str_replace(" ", "", $slug);
            $heroImg = "../src/heroes/" . $hero["icon_hero"];
            ?>
            <a class="_7szOnSgHiQLEyU0_owKBB" href="/cd-project/butbalanced/client/hero/<?= $slug ?>" style="background-image: url(&quot;<?= $heroImg ?>&quot;);">
                <div class="_3ldbS9dVE5CjfD0D09bBf">
                    <img class="_12etdsZfZbhUB46YDOgrB8" src="<?= $attrIcon[
                        $hero["attribute_id"]
                    ] ?>">
                    <div class="_3N-bh9taW0W_prRSK7IMzC"><?= $hero[
                        "name_hero"
                    ] ?></div>
                </div>
                <div class="AbboqbOUC-VDkD7WOlB0X">
                    <div class="_1JDI3DcgSee71RA7XTqs2T _1NXAhOdbsQ_GmSo1oML7LS">
                        <div class="_1keusz4ZyZirGepu6hHG0D" style="background: linear-gradient(rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.733) 75%, rgb(0, 0, 0) 100%);"></div>
                    </div>
                </div>
            </a>
            <?php
        endforeach; ?>
    </div>
</div>
