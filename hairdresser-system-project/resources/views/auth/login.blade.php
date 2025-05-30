<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="login-card">
    <h2><i class="bi bi-box-arrow-in-right me-1"></i> Giriş Yap</h2>

    @if(session('error'))
        <div class="error-message">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email"><i class="bi bi-envelope"></i> E-posta</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="password"><i class="bi bi-lock"></i> Şifre</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="remember">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" class="mb-0">Beni Hatırla</label>
        </div>
        <button type="submit" class="login-btn">
            <i class="bi bi-box-arrow-in-right me-1"></i> Giriş Yap
        </button>
    </form>

    <div class="form-footer">
        Hesabınız yok mu? <a href="{{ route('register') }}">Kayıt Ol</a>
    </div>
</div>

</body>
</html>
