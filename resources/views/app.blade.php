<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Santri App')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

    <nav class="mb-4">
        <a href="/">Beranda</a> |
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="/admin/dashboard">Dashboard Admin</a> |
                <a href="{{ route('santri.index') }}">Kelola Santri</a> |
            @elseif(Auth::user()->role === 'santri')
                <a href="/santri/dashboard">Dashboard Santri</a> |
            @endif
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-link">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>

</body>
</html>