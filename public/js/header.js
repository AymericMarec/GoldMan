document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Header sticky avec effet de fondu
    let header = document.querySelector('.header');
    let lastScroll = 0;
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            header.style.opacity = '1';
            return;
        }
        
        if (currentScroll > lastScroll) {
            header.style.opacity = '0';
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }
        
        lastScroll = currentScroll;
    });
}); 