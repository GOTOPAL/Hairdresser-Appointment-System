// Aktif menü için (opsiyonel)
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.sidebar nav ul li a');
    const currentURL = window.location.href;

    links.forEach(link => {
        if (currentURL.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
});


    function toggleNotifications() {
    const box = document.getElementById('notif-dropdown');
    box.classList.toggle('d-none');
}

