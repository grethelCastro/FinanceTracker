document.addEventListener('DOMContentLoaded', () => {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const darkModeCheckbox = document.getElementById('dark_mode');

    // Función para cambiar el tema
    function toggleTheme() {
        const body = document.body;
        const isDarkMode = body.getAttribute('data-bs-theme') === 'dark';
        
        // Cambiar tema visual
        if (isDarkMode) {
            body.setAttribute('data-bs-theme', 'light');
            darkModeIcon.classList.replace('bi-moon', 'bi-brightness-high');
        } else {
            body.setAttribute('data-bs-theme', 'dark');
            darkModeIcon.classList.replace('bi-brightness-high', 'bi-moon');
        }

        // Sincronizar con el checkbox si existe
        if (darkModeCheckbox) {
            darkModeCheckbox.checked = !isDarkMode;
        }

        // Guardar preferencia en el servidor si está autenticado
        if (window.Laravel && window.Laravel.userId) {
            fetch('/perfil/preferencias', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    dark_mode: !isDarkMode,
                    _method: 'PUT'
                })
            });
        } else {
            // Guardar en localStorage para usuarios no autenticados
            localStorage.setItem('theme', isDarkMode ? 'light' : 'dark');
        }
    }

    // Evento para el botón de cambio de tema
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleTheme);
    }

    // Aplicar el tema guardado
    function applySavedTheme() {
        const savedTheme = localStorage.getItem('theme');
        const userPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = savedTheme || (userPrefersDark ? 'dark' : 'light');

        document.body.setAttribute('data-bs-theme', theme);
        
        if (darkModeIcon) {
            if (theme === 'dark') {
                darkModeIcon.classList.add('bi-moon');
            } else {
                darkModeIcon.classList.add('bi-brightness-high');
            }
        }

        if (darkModeCheckbox) {
            darkModeCheckbox.checked = theme === 'dark';
        }
    }

    applySavedTheme();

    // Escuchar cambios en las preferencias del sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('theme')) {
            applySavedTheme();
        }
    });
});