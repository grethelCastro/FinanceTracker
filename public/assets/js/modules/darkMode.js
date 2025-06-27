document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const darkTheme = document.getElementById('dark-theme');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const storage = JSON.parse(localStorage.getItem('financeTrackerData')) || { userSettings: {} };
    
    // Inicializar tema sin parpadeo
    if (storage.userSettings.darkMode) {
        enableDarkMode();
    } else {
        updateIcon(false);
    }
    
    // Manejar el botón de toggle
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            isDark ? disableDarkMode() : enableDarkMode();
            
            // Guardar preferencia
            storage.userSettings.darkMode = !isDark;
            localStorage.setItem('financeTrackerData', JSON.stringify(storage));
        });
    }
    
    function enableDarkMode() {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        if (darkTheme) darkTheme.disabled = false;
        updateIcon(true);
    }
    
    function disableDarkMode() {
        document.documentElement.setAttribute('data-bs-theme', 'light');
        if (darkTheme) darkTheme.disabled = true;
        updateIcon(false);
    }
    
    function updateIcon(isDark) {
        if (darkModeIcon) {
            darkModeIcon.classList.toggle('bi-moon-fill', !isDark);
            darkModeIcon.classList.toggle('bi-sun-fill', isDark);
        }
    }
});