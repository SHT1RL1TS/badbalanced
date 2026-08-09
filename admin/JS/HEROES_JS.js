// JS/HEROS_JS.js

document.addEventListener('DOMContentLoaded', function() {
    const grid = document.querySelector('._3LrTPTY1adWYh0ceoy0QFj');
    const back = document.querySelector('.D36V-Zuy4P4h8Ogar6YWx');
    if (!grid) return;

    const CARD_HEIGHT = 127;
    const GAP = 15;
    const PADDING = 20;
    const TOTAL_HEROES = 127;

    function updateGridHeight() {
        const width = window.innerWidth;
        let cols = 5;

        if (width <= 1000) cols = 3;
        else if (width <= 1300) cols = 4;
        else cols = 5;

        // Расчет рядов
        const rows = Math.ceil(TOTAL_HEROES / cols);

        // Расчет высоты
        const height = rows * (CARD_HEIGHT + GAP) + PADDING;
        grid.style.height = height + 'px';
        if (cols < 4)
        {
            back.style.height = height + 750 + 'px';
        }
        else
        {
            back.style.height = height + 500 + 'px';
        }

        // Обновляем CSS для grid
        grid.style.gridTemplateColumns = `repeat(${cols}, 225px)`;

        // Для мобильных делаем адаптивную ширину
        if (cols <= 2) {
            grid.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
            grid.style.height = 'auto';
            grid.style.minHeight = '500px';
        } else {
            grid.style.gridTemplateColumns = `repeat(${cols}, 225px)`;
            grid.style.height = height + 'px';
            grid.style.minHeight = 'auto';
        }
    }

    // Вызываем при загрузке
    updateGridHeight();

    // При изменении размера
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(updateGridHeight, 200);
    });
});
