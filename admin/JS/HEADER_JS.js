(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', initDropdown);
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDropdown);
    } else {
        initDropdown();
    }
    
    function initDropdown() {
        const dropdown = document.getElementById('patchesDropdown');
        const content = document.getElementById('patchesDropdownContent');
        
        if (!dropdown || !content) {
            return;
        }
        
        const btn = dropdown.querySelector('.patches-btn');
        if (!btn) return;
        
        let hideTimeout = null;
        const HOVER_CLASS = '_3ulNR3VlHLYvZ3PQlOXxdm';

        function showMenu() {
            if (hideTimeout) {
                clearTimeout(hideTimeout);
                hideTimeout = null;
            }
            content.style.display = 'block';
            btn.classList.add(HOVER_CLASS);
        }

        function hideMenu() {
            hideTimeout = setTimeout(() => {
                content.style.display = 'none';
                btn.classList.remove(HOVER_CLASS);
            }, 200);
        }

        function hideMenuImmediately() {
            if (hideTimeout) {
                clearTimeout(hideTimeout);
                hideTimeout = null;
            }
            content.style.display = 'none';
            btn.classList.remove(HOVER_CLASS);
        }

        // Привязываем события
        dropdown.addEventListener('mouseenter', showMenu);
        dropdown.addEventListener('mouseleave', hideMenu);

        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                hideMenuImmediately();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideMenuImmediately();
            }
        });

        const menuLinks = content.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(hideMenuImmediately, 100);
            });
        });
    }
})();