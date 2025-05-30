<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuaför Rezervasyon Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">KuaförRandevu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-2">
                <li class="nav-item"><a class="nav-link" href="#home">Ana Sayfa</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Özellikler</a></li>
                <li class="nav-item"><a class="nav-link" href="#hairdressers">Kuaförler</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Hizmetler</a></li>
                <li class="nav-item"><a class="btn btn-outline-primary btn-sm" href="/login">Giriş Yap</a></li>
                <li class="nav-item"><a class="btn btn-primary btn-sm ms-1" href="/register">Kayıt Ol</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <h1>Kuaförünü Bul, Randevunu Al!</h1>
        <p class="mt-3">Profesyonel kuaförlerle tanış, dakikalar içinde randevunu oluştur.</p>
        <a href="/register" class="btn btn-primary btn-lg mt-4">Hemen Başla</a>
    </div>
</section>

<!-- Özellikler -->
<section id="features" class="py-5">
    <div class="container">
        <div class="row text-center">
            <h2 class="mb-5"> Neler Sunuyoruz?</h2>
            <div class="col-md-4">
                <div class="feature-icon mb-3"><i class="bi bi-calendar-check-fill"></i></div>
                <h5>Kolay Randevu</h5>
                <p>İstediğin kuaförden anında randevu alabilirsin.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3"><i class="bi bi-person-badge-fill"></i></div>
                <h5>Kuaför Profilleri</h5>
                <p>Kuaförün hizmetleri, yorumları ve biyografisine göz at.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon mb-3"><i class="bi bi-chat-dots-fill"></i></div>
                <h5>Yorum ve Değerlendirme</h5>
                <p>Hizmet sonrası yorum yap, diğer kullanıcılara yol göster.</p>
            </div>
        </div>
    </div>
</section>

<!-- Kuaförler -->
<section id="hairdressers" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">✂️ Öne Çıkan Kuaförler</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="Kuaför">
                    <div class="card-body">
                        <h5 class="card-title">Göktuğ Hair Studio</h5>
                        <p class="card-text">Modern kesimler, saç bakımı ve stil danışmanlığı.</p>
                    </div>
                </div>
            </div>
            <!-- Daha fazla kuaför örneği eklenebilir -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="Kuaför">
                    <div class="card-body">
                        <h5 class="card-title">Elif Beauty Salon</h5>
                        <p class="card-text">Kadın kuaför hizmetleri, saç boyama ve bakım.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hizmetler -->
<section id="services" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">💇 Popüler Hizmetler</h2>
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="p-3 border rounded shadow-sm">
                    <i class="bi bi-scissors fs-2 text-primary"></i>
                    <h6 class="mt-2">Saç Kesimi</h6>
                    <p class="text-muted small">100₺</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 border rounded shadow-sm">
                    <i class="bi bi-brush fs-2 text-primary"></i>
                    <h6 class="mt-2">Saç Boyama</h6>
                    <p class="text-muted small">200₺</p>
                </div>
            </div>
            <!-- Diğer hizmetler -->
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer mt-5">
    <div class="container">
        <p>&copy; 2025 KuaförRandevu. Tüm hakları saklıdır.</p>
        <div>
            <a href="#" class="text-decoration-none me-2"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-decoration-none me-2"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-decoration-none"><i class="bi bi-facebook"></i></a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
