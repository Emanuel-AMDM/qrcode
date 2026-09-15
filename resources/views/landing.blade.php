@extends('layouts.app')

@section('title', 'QR Studio - Gerador de QR Codes Inteligentes & Dinâmicos')

@section('content')
<div class="container py-5">
    <!-- Seção de Destaque (Hero) -->
    <div class="row align-items-center py-5">
        <div class="col-lg-7 text-center text-lg-start mb-5 mb-lg-0">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-primary bg-opacity-10 border border-primary border-opacity-25 mb-3">
                <i class="fa-solid fa-bolt text-primary"></i>
                <span class="text-primary fw-semibold small">Plataforma Profissional de QR Codes</span>
            </div>
            <h1 class="display-4 fw-extrabold mb-3 text-white">
                Crie, Personalize e Monitore Seus <span class="text-primary">QR Codes</span>
            </h1>
            <p class="lead text-secondary mb-4" style="max-width: 600px;">
                Gere QR Codes estáticos rápidos ou crie QR Codes dinâmicos com links encurtados inteligentes que permitem alterar a URL de destino a qualquer momento e rastrear leituras em tempo real.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                <a href="{{ route('qr.index') }}" class="btn btn-gradient-primary btn-lg px-4 py-3 rounded-pill d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-qrcode fs-5"></i>
                    <span>Acessar Gerador de QR Code</span>
                    <i class="fa-solid fa-arrow-right small ms-1"></i>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4 py-3 rounded-pill d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Criar Conta Gratuita</span>
                    </a>
                @endguest
            </div>
        </div>

        <div class="col-lg-5 text-center">
            <div class="position-relative d-inline-block">
                <div class="card-custom p-4 shadow-lg position-relative" style="max-width: 380px; margin: auto;">
                    <div class="bg-white p-3 rounded-3 mb-3 d-inline-block shadow">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=https://github.com&color=0d6efd" alt="Demo QR Code" class="img-fluid rounded" style="width: 220px; height: 220px;">
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25">
                        <div class="text-start">
                            <span class="badge bg-success-subtle text-success border border-success-subtle mb-1">Dinâmico Ativo</span>
                            <div class="fw-bold text-white small">Campanha Principal</div>
                        </div>
                        <div class="text-end">
                            <div class="text-primary fw-bold fs-5">1.428</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">Leituras</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Como Funciona / Recursos -->
    <div class="py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white mb-2">Como Funciona o Sistema?</h2>
            <p class="text-secondary">Conheça as duas formas práticas de gerar e utilizar seus códigos</p>
        </div>

        <div class="row g-4">
            <!-- Card 1: Estático -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100 border-primary border-opacity-25">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary fs-3">
                            <i class="fa-solid fa-bolt-lightning"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-1">1. QR Code Estático</h4>
                            <span class="badge bg-secondary">Geração Imediata no Navegador</span>
                        </div>
                    </div>
                    <p class="text-secondary">
                        A URL ou texto é gravado diretamente no próprio código visual. Não requer banco de dados, não expira e é gerado instantaneamente no seu navegador.
                    </p>
                    <ul class="list-unstyled text-secondary small mb-4">
                        <li class="mb-2"><i class="fa-solid fa-check text-primary me-2"></i> Customização total de cor primária e de fundo</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-primary me-2"></i> Upload de logo centralizado com ajuste automático</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-primary me-2"></i> Download em alta resolução nos formatos PNG e SVG</li>
                    </ul>
                </div>
            </div>

            <!-- Card 2: Dinâmico -->
            <div class="col-md-6">
                <div class="card-custom p-4 h-100 border-info border-opacity-25">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info fs-3">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-1">2. QR Code Dinâmico</h4>
                            <span class="badge bg-info-subtle text-info border border-info-subtle">Rastreamento & Edição</span>
                        </div>
                    </div>
                    <p class="text-secondary">
                        O código impresso aponta para um link encurtado inteligente (ex: <code class="text-info">/q/meu-link</code>). Você pode alterar a URL de destino a qualquer momento sem precisar reimprimir o material!
                    </p>
                    <ul class="list-unstyled text-secondary small mb-4">
                        <li class="mb-2"><i class="fa-solid fa-check text-info me-2"></i> Alteração de destino em tempo real sem reimpressão</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-info me-2"></i> Contador de acessos e histórico com IP, navegador e data</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-info me-2"></i> Slugs personalizados para identificação clara da campanha</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner de Chamada para Ação (CTA) -->
    <div class="card-custom p-5 text-center mt-5 position-relative overflow-hidden border-primary border-opacity-50" style="background: radial-gradient(circle at top, rgba(13,110,253,0.15), rgba(22,25,34,0.9));">
        <h2 class="display-6 fw-bold text-white mb-3">Pronto para gerar seus códigos?</h2>
        <p class="text-secondary mb-4" style="max-width: 550px; margin: auto;">
            Acesse o gerador agora mesmo, crie QR Codes com o logotipo da sua marca e acompanhe todas as métricas em um painel intuitivo.
        </p>
        <a href="{{ route('qr.index') }}" class="btn btn-gradient-primary btn-lg px-5 py-3 rounded-pill fw-bold">
            <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Ir Para a Tela de Gerar QR Code
        </a>
    </div>
</div>
@endsection
