<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>

<div class="register-card">
    <h2><i class="bi bi-person-plus me-2"></i> Kayıt Ol</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name"><i class="bi bi-person"></i> Ad Soyad</label>
            <input type="text" id="name" name="name" required value="{{ old('name') }}">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email"><i class="bi bi-envelope"></i> E-posta</label>
            <input type="email" id="email" name="email" required value="{{ old('email') }}">
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone_number"><i class="bi bi-telephone"></i> Telefon No</label>
            <input type="text" id="phone_number" name="phone_number" required value="{{ old('phone_number') }}">
            @error('phone_number')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password"><i class="bi bi-lock"></i> Şifre</label>
            <input type="password" id="password" name="password" required>
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation"><i class="bi bi-lock-fill"></i> Şifre Tekrar</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="role"><i class="bi bi-person-badge"></i> Rol</label>
            <select name="role" id="role" required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Seçiniz</option>
                <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Müşteri</option>
                <option value="hairdresser" {{ old('role') === 'hairdresser' ? 'selected' : '' }}>Kuaför</option>
            </select>
            @error('role')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="register-btn">
            <i class="bi bi-check-circle me-1"></i> Kayıt Ol
        </button>
    </form>

    <div class="form-footer">
        Zaten hesabınız var mı? <a href="{{ route('login') }}">Giriş Yap</a>
    </div>
</div>

</body>
</html>
