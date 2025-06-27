document.addEventListener('DOMContentLoaded', function() {
    // Prevenir parpadeo del tema
    const savedTheme = localStorage.getItem('financeTrackerData') 
        ? JSON.parse(localStorage.getItem('financeTrackerData')).userSettings?.darkMode 
        : false;
    
    if (savedTheme) {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        document.getElementById('dark-theme').disabled = false;
    }
    
    // Manejar transiciones entre páginas
    const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([data-bs-toggle])');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.href && this.href.includes(window.location.hostname) && 
                !this.href.includes('logout') && !this.href.includes('javascript:')) {
                e.preventDefault();
                
                // Mostrar loader
                const loader = document.querySelector('.page-loader');
                if (loader) loader.classList.add('active');
                
                // Ocultar contenido
                const content = document.querySelector('.content-wrapper');
                if (content) content.classList.add('fade-out');
                
                // Navegar después de la transición
                setTimeout(() => {
                    window.location.href = this.href;
                }, 300);
            }
        });
    });
    
    // Ocultar loader cuando la página carga
    window.addEventListener('load', function() {
        const loader = document.querySelector('.page-loader');
        if (loader) loader.classList.remove('active');
        
        const content = document.querySelector('.content-wrapper');
        if (content) content.classList.remove('fade-out');
    });
});