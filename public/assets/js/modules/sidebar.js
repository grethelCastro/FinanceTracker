document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.app-sidebar');
    const sidebarClose = document.querySelector('.sidebar-close');
    
    // Mostrar/ocultar sidebar en móvil
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            document.body.classList.toggle('sidebar-open');
        });
    }
    
    // Cerrar sidebar
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function(e) {
            e.stopPropagation();
            closeSidebar();
        });
    }
    
    // Cerrar al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992 && sidebar.classList.contains('show') && 
            !sidebar.contains(e.target) && !e.target.closest('.sidebar-toggle')) {
            closeSidebar();
        }
    });
    
    // Ajustar al cambiar tamaño
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });
    
    function closeSidebar() {
        sidebar.classList.remove('show');
        document.body.classList.remove('sidebar-open');
    }
});