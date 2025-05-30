// public/js/notifications.js

document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('notifToggle');
    const notifBox = document.getElementById('notifBox');

    // Toggle butonuna tıklayınca aç/kapat
    toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        notifBox.classList.toggle('d-none');
    });

    // Sayfanın başka yerine tıklanırsa bildirimi kapat
    document.addEventListener('click', function (e) {
        if (!notifBox.contains(e.target) && !toggleBtn.contains(e.target)) {
            notifBox.classList.add('d-none');
        }
    });

    // Bildirim kutusuna tıklayınca kapanmasın
    notifBox.addEventListener('click', function (e) {
        e.stopPropagation();
    });
});
