document.addEventListener('DOMContentLoaded', () => {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const darkModeIcon = document.getElementById('darkModeIcon');

    // Función para cambiar el tema
    function toggleTheme() {
        const body = document.body;
        if (body.getAttribute('data-bs-theme') === 'dark') {
            body.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
            darkModeIcon.classList.replace('bi-moon', 'bi-brightness-high');
        } else {
            body.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            darkModeIcon.classList.replace('bi-brightness-high', 'bi-moon');
        }
    }

    // Evento para el botón de cambio de tema
    darkModeToggle.addEventListener('click', toggleTheme);

    // Aplicar el tema guardado en el almacenamiento local
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.body.setAttribute('data-bs-theme', savedTheme);
        if (savedTheme === 'dark') {
            darkModeIcon.classList.add('bi-moon');
        } else {
            darkModeIcon.classList.add('bi-brightness-high');
        }
    } else {
        darkModeIcon.classList.add('bi-brightness-high');
    }
});