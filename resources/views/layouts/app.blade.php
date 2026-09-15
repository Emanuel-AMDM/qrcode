<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QR Code Studio - Gerador de QR Codes Inteligentes')</title>
    
    <!-- Ícone da Aba (Favicon) -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    
    <!-- Fontes do Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos Bootstrap 5 e FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-heading: 'Outfit', sans-serif;
            --primary-glow: rgba(13, 110, 253, 0.25);
            --card-bg: #161922;
            --card-border: #232733;
        }

        body {
            font-family: var(--font-main);
            background-color: #0d0f17;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: var(--font-heading);
            letter-spacing: -0.02em;
        }

        .navbar-custom {
            background-color: rgba(13, 15, 23, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
        }

        .brand-logo-icon {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.6rem;
        }

        .brand-title {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.35rem;
            background: linear-gradient(135deg, #ffffff 40%, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-custom {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0052cc 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #1a75ff 0%, #005ce6 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.5);
            color: white;
        }

        .btn-gradient-success {
            background: linear-gradient(135deg, #198754 0%, #116b41 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        }

        .btn-gradient-success:hover {
            background: linear-gradient(135deg, #1ea063 0%, #147c4c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 135, 84, 0.5);
            color: white;
        }

        /* Notificações Toast */
        .toast-container {
            z-index: 1080;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
                <img src="{{ asset('favicon.png') }}" alt="QR Studio Logo" class="d-inline-block" style="width: 28px; height: 28px; object-fit: contain;">
                <span class="brand-title">QR Studio</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1" style="font-size: 0.7rem;">PRO</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <i class="fa-solid fa-bars text-body"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0 gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('landing') ? 'active fw-bold text-white' : '' }}" href="{{ route('landing') }}">
                            <i class="fa-solid fa-house me-1 text-secondary"></i> Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('qr.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('qr.index') }}">
                            <i class="fa-solid fa-wand-magic-sparkles me-1 text-primary"></i> Gerador & Painel
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-2" type="button" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->nome).'&background=0D6EFD&color=fff&bold=true' }}" 
                                     class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;" alt="{{ auth()->user()->nome }}">
                                <span class="fw-semibold small">{{ auth()->user()->nome }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-secondary">
                                <li class="px-3 py-2 border-bottom border-secondary text-secondary small">
                                    Conectado como<br><strong class="text-white">{{ auth()->user()->email }}</strong>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('qr.index') }}">
                                        <i class="fa-solid fa-qrcode me-2 text-primary"></i> Meus QR Codes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Sair da Conta
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill fw-semibold">
                            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Entrar
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-gradient-primary btn-sm px-3 rounded-pill">
                            <i class="fa-solid fa-user-plus me-1"></i> Criar Conta Grátis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Notificações Flutuantes (Toasts) -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @if(session('success_toast') || session('success'))
            <div class="toast align-items-center text-bg-success border-0 show shadow-lg" role="alert">
                <div class="d-flex">
                    <div class="toast-body fw-semibold">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success_toast') ?? session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0 show shadow-lg" role="alert">
                <div class="d-flex">
                    <div class="toast-body fw-semibold">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Conteúdo Principal -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Rodapé -->
    <footer class="border-top border-secondary border-opacity-25 py-4 mt-5 text-center text-secondary small">
        <div class="container">
            <p class="mb-1 fw-medium">&copy; {{ date('Y') }} QR Studio. Todos os direitos reservados.</p>
            <p class="mb-0 text-secondary opacity-75">Crie, personalize e acompanhe métricas de QR Codes com máxima precisão.</p>
        </div>
    </footer>

    <!-- Scripts Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Exibição automática dos alertas toast
        document.querySelectorAll('.toast').forEach(toastEl => {
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();
        });
    </script>
    @yield('scripts')
</body>
</html>
