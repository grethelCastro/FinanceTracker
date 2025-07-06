document.addEventListener('DOMContentLoaded', () => {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    const htmlElement = document.documentElement;

    // Configuración
    const config = {
        storageKey: 'theme',
        serverEndpoint: '/perfil/preferencias'
    };

    // Función para activar el modo oscuro
    const enableDarkMode = (savePreference = true) => {
        htmlElement.setAttribute('data-bs-theme', 'dark');
        darkModeSwitch.checked = true;
        darkModeToggle.setAttribute('aria-checked', 'true');
        
        if (savePreference) {
            saveThemePreference('dark');
        }
    };

    // Función para activar el modo claro
    const enableLightMode = (savePreference = true) => {
        htmlElement.setAttribute('data-bs-theme', 'light');
        darkModeSwitch.checked = false;
        darkModeToggle.setAttribute('aria-checked', 'false');
        
        if (savePreference) {
            saveThemePreference('light');
        }
    };

    // Función para cambiar el tema
    const toggleTheme = (savePreference = true) => {
        const isDarkMode = htmlElement.getAttribute('data-bs-theme') === 'dark';
        
        // Animación
        darkModeToggle.classList.add('active');
        setTimeout(() => darkModeToggle.classList.remove('active'), 300);

        if (isDarkMode) {
            enableLightMode(savePreference);
        } else {
            enableDarkMode(savePreference);
        }
    };

    // Guardar preferencia
    const saveThemePreference = (theme) => {
        if (window.Laravel?.userId) {
            updateServerPreference(theme === 'dark');
        } else {
            localStorage.setItem(config.storageKey, theme);
        }
    };

    // Actualizar en servidor
    const updateServerPreference = (darkModeEnabled) => {
        fetch(config.serverEndpoint, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                dark_mode: darkModeEnabled,
                _method: 'PUT'
            })
        }).catch(error => {
            console.error('Error al guardar preferencia:', error);
        });
    };

    // Aplicar tema guardado
    const applySavedTheme = () => {
        const savedTheme = localStorage.getItem(config.storageKey);
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        
        if (theme === 'dark') {
            enableDarkMode(false); // No guardar preferencia al inicializar
        } else {
            enableLightMode(false);
        }
    };

    // Evento para el cambio manual
    darkModeToggle.addEventListener('click', () => {
        toggleTheme(true);
    });

    // Evento directo para el checkbox (por si acaso)
    darkModeSwitch.addEventListener('change', () => {
        toggleTheme(true);
    });

    // Escuchar cambios del sistema
    const colorSchemeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    colorSchemeMediaQuery.addEventListener('change', (e) => {
        if (!localStorage.getItem(config.storageKey)) {
            if (e.matches) {
                enableDarkMode(false);
            } else {
                enableLightMode(false);
            }
        }
    });

    // Inicializar
    applySavedTheme();

    // Limpieza para SPA (opcional)
    return () => {
        darkModeToggle.removeEventListener('click', toggleTheme);
        darkModeSwitch.removeEventListener('change', toggleTheme);
        colorSchemeMediaQuery.removeEventListener('change', handleSystemThemeChange);
    };
});     