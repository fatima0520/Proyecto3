<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Banditore - Portal de noticias y comentarios colaborativo">
    <title>@yield('title', 'Banditore · Blog de Noticias')</title>

    <!-- Google Fonts: Playfair Display & Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Blog CSS -->
    <link href="{{ asset('css/blog.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: "Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #333333;
            background-color: #ffffff;
        }

        h1, h2, h3, h4, h5, h6, .blog-header-logo {
            font-family: "Playfair Display", Georgia, "Times New Roman", serif;
        }

        .blog-header {
            border-bottom: 1px solid #e5e5e5;
            line-height: 1;
        }

        .blog-header-logo {
            font-size: 2.25rem;
            color: #212529;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .blog-header-logo:hover {
            color: #0d6efd;
            text-decoration: none;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .nav-scroller .nav-link {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: .95rem;
            color: #495057;
            font-weight: 500;
        }

        .nav-scroller .nav-link:hover,
        .nav-scroller .nav-link.active {
            color: #0d6efd;
        }

        .blog-post {
            margin-bottom: 3.5rem;
        }

        .blog-post-title {
            margin-bottom: .35rem;
            font-size: 2.25rem;
            font-weight: 700;
        }

        .blog-post-title a {
            color: #212529;
            text-decoration: none;
        }

        .blog-post-title a:hover {
            color: #0d6efd;
        }

        .blog-post-meta {
            margin-bottom: 1.25rem;
            color: #6c757d;
            font-size: .9rem;
        }

        .blog-footer {
            padding: 2.5rem 0;
            color: #6c757d;
            text-align: center;
            background-color: #f8f9fa;
            border-top: 1px solid #e5e5e5;
            margin-top: 4rem;
        }

        .blog-footer p:last-child {
            margin-bottom: 0;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="container">
    <!-- Top Header -->
    <header class="blog-header lh-1 py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 pt-1">
                <span class="text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}
                </span>
            </div>

            <div class="col-4 text-center">
                <a class="blog-header-logo text-dark fw-bold" href="{{ route('noticia.index') }}">
                    Banditore
                </a>
            </div>

            <div class="col-4 d-flex justify-content-end align-items-center">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('noticia.create') }}">
                    <i class="bi bi-pencil-square me-1"></i> Publicar Noticia
                </a>
            </div>
        </div>
    </header>

    <!-- Nav Scroller -->
    <div class="nav-scroller py-1 mb-3 border-bottom">
        <nav class="nav d-flex justify-content-start gap-3">
            <a class="p-2 link-secondary text-decoration-none {{ request()->routeIs('noticia.index') ? 'text-primary fw-bold' : '' }}" href="{{ route('noticia.index') }}">
                <i class="bi bi-house-door me-1"></i> Portada
            </a>

            <a class="p-2 link-secondary text-decoration-none {{ request()->routeIs('noticia.create') ? 'text-primary fw-bold' : '' }}" href="{{ route('noticia.create') }}">
                <i class="bi bi-plus-circle me-1"></i> Nueva Noticia
            </a>
        </nav>
    </div>

    <!-- Global Alerts -->
    <div class="my-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->has('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                <div>{{ $errors->first('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
    </div>

    @yield('hero')
    @yield('featured')
</div>

<!-- Main Content -->
<main class="container">
    @yield('content')
</main>

<!-- Footer -->
<footer class="blog-footer">
    <div class="container">
        <p class="mb-1">
            <strong>Banditore</strong> &mdash; Periódico digital y plataforma comunitaria de noticias.
        </p>

        <p class="text-muted small mb-2">
            Desarrollado con Laravel {{ app()->version() }} y Bootstrap v5.2.
        </p>

        <p>
            <a href="#" class="text-decoration-none">
                <i class="bi bi-arrow-up-circle me-1"></i> Volver arriba
            </a>
        </p>
    </div>
</footer>

<!-- Bootstrap 5.2 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<!-- Confirm Dialog Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form.confirm-dialog').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                const message = form.getAttribute('data-message') || '¿Estás seguro de que deseas eliminar este elemento?';

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

@stack('scripts')

</body>
</html>

