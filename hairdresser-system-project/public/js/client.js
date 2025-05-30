document.addEventListener('DOMContentLoaded', () => {
    // Aktif link işaretleme
    const links = document.querySelectorAll('.sidebar a');
    const currentUrl = window.location.href;

    links.forEach(link => {
        if (currentUrl.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
});

