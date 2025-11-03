// Mobile burger menu toggle
document.addEventListener('DOMContentLoaded', () => {
    // Burger menu toggle
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    
    if ($navbarBurgers.length > 0) {
        $navbarBurgers.forEach(el => {
            el.addEventListener('click', () => {
                const target = el.dataset.target;
                const $target = document.getElementById(target);
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    }
    
    // Mobile dropdown toggle with smooth animation
    const dropdowns = document.querySelectorAll('.navbar-item.has-dropdown');
    
    dropdowns.forEach(dropdown => {
        const link = dropdown.querySelector('.navbar-link');
        const menu = dropdown.querySelector('.navbar-dropdown');
        
        if (link && menu) {
            // Prevent default link behavior
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Close other dropdowns on mobile
                if (window.innerWidth < 1024) {
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove('is-active');
                            const otherMenu = otherDropdown.querySelector('.navbar-dropdown');
                            if (otherMenu) {
                                otherMenu.style.maxHeight = '0px';
                            }
                        }
                    });
                }
                
                // Toggle current dropdown
                dropdown.classList.toggle('is-active');
                
                // Smooth height animation
                if (dropdown.classList.contains('is-active')) {
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                } else {
                    menu.style.maxHeight = '0px';
                }
            });
        }
    });
    
    // Reset dropdowns on window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth >= 1024) {
                // Desktop mode - reset mobile states
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('is-active');
                    const menu = dropdown.querySelector('.navbar-dropdown');
                    if (menu) {
                        menu.style.maxHeight = '';
                    }
                });
            }
        }, 250);
    });
});
