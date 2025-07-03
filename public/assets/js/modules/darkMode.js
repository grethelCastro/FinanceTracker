document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    
    // Cargar preferencias
    const storage = JSON.parse(localStorage.getItem('financeTrackerData')) || { userSettings: {} };
    const savedTheme = storage.userSettings.darkMode || false;
    
    // Aplicar tema al cargar
    if (savedTheme) {
        enableDarkMode();
    } else {
        updateIcon(false);
    }
    
    // Manejar el toggle
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            const isDark = document.body.classList.contains('dark-mode');
            isDark ? disableDarkMode() : enableDarkMode();
            
            // Guardar preferencia
            storage.userSettings.darkMode = !isDark;
            localStorage.setItem('financeTrackerData', JSON.stringify(storage));
        });
    }
    
    // Funciones de ayuda
    function enableDarkMode() {
        document.body.classList.add('dark-mode');
        updateIcon(true);
    }
    
    function disableDarkMode() {
        document.body.classList.remove('dark-mode');
        updateIcon(false);
    }
    
    function updateIcon(isDark) {
        if (darkModeIcon) {
            darkModeIcon.classList.toggle('bi-moon-fill', !isDark);
            darkModeIcon.classList.toggle('bi-sun-fill', isDark);
        }
    }
});