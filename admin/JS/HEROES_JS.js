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

const searchinput = document.getElementById('search_hero');
const heroCards = document.querySelectorAll('._7szOnSgHiQLEyU0_owKBB');
function filterCard() {
  const active_attr = document.querySelector('.attr-btns._1os0-FT4f9zs_Otn_JXkGc')
  const active_complexity = document.querySelector('.complexity-btns._1os0-FT4f9zs_Otn_JXkGc')

  const attrValue = active_attr ? active_attr.dataset.attrId : null;
  const complexityValue = active_complexity ? active_complexity.dataset.complexity : null;

  const searchText = searchinput.value.toLowerCase().trim();

  heroCards.forEach(card => {
    let show = true
    if (attrValue && card.dataset.heroAttrId !== attrValue) {
      show = false;
    }
    if (complexityValue && card.dataset.complexity !== complexityValue) {
      show = false;
    }
    if (searchText) {
      const cardName = card.dataset.name.toLowerCase();
      if (!cardName.includes(searchText)) {
        show = false;
      }
    }
    if (show) {
      card.classList.remove('hidden');
    } else {
      card.classList.add('hidden');
    }
  });
}

// N74aaCii0wv_Ody2YGY_w
function btn_group(selector) {
  const btns = document.querySelectorAll(selector);
  btns.forEach(btn => {
    btn.addEventListener('click', function () {
      if (this.classList.contains('_1os0-FT4f9zs_Otn_JXkGc'))
      {
        this.classList.remove('_1os0-FT4f9zs_Otn_JXkGc');
      }
      else
      {
        btns.forEach(b => b.classList.remove('_1os0-FT4f9zs_Otn_JXkGc'));
        this.classList.add('_1os0-FT4f9zs_Otn_JXkGc')
      }
      filterCard();
    });
  });
}

btn_group('.attr-btns');
btn_group('.complexity-btns');

searchinput.addEventListener('input', filterCard);

filterCard();

async function getAllHeroes() {
  try {
    const response = await fetch('/cd-project/butbalanced/api/db.php', {
      method: 'POST',
      headers: {
        'Content-type': 'application/json'
      },
      body: JSON.stringify({action: 'getAllHeroes'})
    });

    const data = await response.json();
    console.log(data);
    return data
  }
  catch (error) {
    console.warn("ERROR:", error);
    return null;
  }
}
