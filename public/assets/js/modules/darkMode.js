// darkMode.js
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
        document.getElementById('dark-theme')?.removeAttribute('disabled');
        if (darkModeSwitch) darkModeSwitch.checked = true;
        if (darkModeToggle) darkModeToggle.setAttribute('aria-checked', 'true');
        
        if (savePreference) {
            saveThemePreference('dark');
        }
    };

    // Función para activar el modo claro
    const enableLightMode = (savePreference = true) => {
        htmlElement.setAttribute('data-bs-theme', 'light');
        document.getElementById('dark-theme')?.setAttribute('disabled', 'true');
        if (darkModeSwitch) darkModeSwitch.checked = false;
        if (darkModeToggle) darkModeToggle.setAttribute('aria-checked', 'false');
        
        if (savePreference) {
            saveThemePreference('light');
        }
    };

    // Función para cambiar el tema
    const toggleTheme = (savePreference = true) => {
        const isDarkMode = htmlElement.getAttribute('data-bs-theme') === 'dark';
        
        if (darkModeToggle) {
            darkModeToggle.classList.add('active');
            setTimeout(() => darkModeToggle.classList.remove('active'), 300);
        }

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
        // Verificar preferencia del usuario desde la base de datos (vía meta tag)
        const themeMeta = document.querySelector('meta[name="color-theme"]');
        const savedTheme = themeMeta ? themeMeta.content : localStorage.getItem(config.storageKey);
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        
        if (theme === 'dark') {
            enableDarkMode(false); // No guardar preferencia al inicializar
        } else {
            enableLightMode(false);
        }
    };

    // Evento para el cambio manual
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            toggleTheme(true);
        });
    }

    // Evento directo para el checkbox
    if (darkModeSwitch) {
        darkModeSwitch.addEventListener('change', () => {
            toggleTheme(true);
        });
    }

    // Escuchar cambios del sistema
    const colorSchemeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const handleSystemThemeChange = (e) => {
        if (!localStorage.getItem(config.storageKey) && !document.querySelector('meta[name="color-theme"]')) {
            if (e.matches) {
                enableDarkMode(false);
            } else {
                enableLightMode(false);
            }
        }
    };
    
    colorSchemeMediaQuery.addEventListener('change', handleSystemThemeChange);

    // Inicializar
    applySavedTheme();
});