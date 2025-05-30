<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Başvurunuz Onay Bekliyor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4 text-center" style="max-width: 500px;">
        <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
        <h4 class="mt-3 mb-2 text-warning">Başvurunuz Onay Bekliyor</h4>
        <p class="text-muted">
            Kuaför başvurunuz henüz <strong>admin</strong> tarafından onaylanmadı.<br>
            Lütfen onay süreci tamamlanana kadar bekleyiniz.
        </p>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button class="btn btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
            </button>
        </form>
    </div>
</div>

</body>
</html>
