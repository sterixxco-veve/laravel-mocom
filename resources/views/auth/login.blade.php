<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mocom Admin Panel - Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-5 shadow-sm border-0" style="width: 450px; border-radius: 16px;">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color: #4361EE;">MOCOM MANAGEMENT</h3>
                <p class="text-muted text-sm">Masuk ke Ruang Kerja Web Dashboard Kontrol Pusat</p>
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger py-2 text-sm" style="border-radius: 8px;">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-sm fw-bold">Email atau Username</label>
                    <input type="text" name="email_or_username" class="form-control" placeholder="Masukkan email atau username" required style="border-radius: 8px; padding: 10px;">
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-sm fw-bold">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required style="border-radius: 8px; padding: 10px;">
                </div>
                
                <button type="submit" class="btn text-white w-100 fw-bold py-2" style="background-color: #4361EE; border-radius: 10px; height: 48px;">Masuk ke Panel</button>
            </form>
        </div>
    </div>
</body>
</html>