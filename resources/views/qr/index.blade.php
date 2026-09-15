@extends('layouts.app')

@section('title', 'QR Studio - Gerador de QR Code')

@section('content')
<div class="container py-4">
    
    {{-- Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold m-0 text-white d-flex align-items-center gap-2 font-heading">
                <i class="fa-solid fa-wand-magic-sparkles text-primary"></i> Gerador de QR Code
            </h3>
            <p class="text-secondary small m-0 mt-1">Crie códigos QR estáticos rápidos ou dinâmicos com rastreamento e destino editável.</p>
        </div>
        <div>
            <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-circle-question me-1"></i> Como Funciona?
            </a>
        </div>
    </div>

    <div class="row g-4">
        
        {{-- Coluna da Esquerda: Formulário & Customizações --}}
        <div class="col-xl-7 col-lg-6">
            <div class="card-custom overflow-hidden mb-4">
                
                {{-- Abas Estático x Dinâmico --}}
                <div class="border-bottom border-secondary border-opacity-25">
                    <ul class="nav nav-tabs nav-justified border-0" id="qrTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold d-flex align-items-center justify-content-center gap-2 border-0 rounded-0" id="static-tab" data-bs-toggle="tab" data-bs-target="#static-pane" type="button" role="tab">
                                <i class="fa-solid fa-link fa-fw text-primary"></i> QR Code Estático
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold d-flex align-items-center justify-content-center gap-2 border-0 rounded-0" id="dynamic-tab" data-bs-toggle="tab" data-bs-target="#dynamic-pane" type="button" role="tab">
                                <i class="fa-solid fa-arrows-spin fa-fw text-info"></i> QR Code Dinâmico
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="qrTabsContent">
                        
                        {{-- Painel Estático --}}
                        <div class="tab-pane fade show active" id="static-pane" role="tabpanel">
                            <div class="alert alert-primary border-0 rounded-3 mb-4 bg-primary bg-opacity-10 text-primary">
                                <div class="d-flex gap-3">
                                    <span class="fs-4">💡</span>
                                    <div>
                                        <h6 class="fw-bold mb-1">Como funciona o Estático?</h6>
                                        <p class="text-secondary small m-0" style="line-height: 1.4;">Este QR Code grava o link diretamente em seu interior. Ele <strong>nunca expira</strong>, não necessita de banco de dados e funcionará para sempre!</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="static_url" class="form-label fw-bold text-white small">Link de Destino (URL)</label>
                                <div class="input-group input-group-lg rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-body-tertiary border-secondary text-secondary"><i class="fa-solid fa-globe"></i></span>
                                    <input type="url" class="form-control bg-body border-secondary text-body fs-6" id="static_url" placeholder="https://exemplo.com/seu-link-aqui" value="https://google.com">
                                </div>
                                <div class="form-text text-secondary mt-2 small"><i class="fa-solid fa-circle-info me-1"></i> Digite qualquer URL válida que você deseja codificar.</div>
                            </div>
                        </div>

                        {{-- Painel Dinâmico --}}
                        <div class="tab-pane fade" id="dynamic-pane" role="tabpanel">
                            <div class="alert alert-warning border-0 rounded-3 mb-4 bg-warning bg-opacity-10 text-warning">
                                <div class="d-flex gap-3">
                                    <span class="fs-4">⚡</span>
                                    <div>
                                        <h6 class="fw-bold mb-1">Como funciona o Dinâmico?</h6>
                                        <p class="text-secondary small m-0" style="line-height: 1.4;">Ele aponta para um link encurtado inteligente (`/q/{slug}`). Você poderá alterar o destino a qualquer momento <strong>sem mudar o QR Code impresso</strong> e rastreia acessos!</p>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('qr.store') }}" method="POST" id="dynamicForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="dynamic_title" class="form-label fw-bold text-white small">Título do QR Code</label>
                                    <input type="text" class="form-control bg-body border-secondary text-body" id="dynamic_title" name="titulo" placeholder="Ex: Cardápio Digital / Campanha 2026" required>
                                </div>

                                <div class="mb-3">
                                    <label for="dynamic_url" class="form-label fw-bold text-white small">Link de Destino Final (URL)</label>
                                    <input type="url" class="form-control bg-body border-secondary text-body" id="dynamic_url" name="url_destino" placeholder="https://seusite.com/destino-final" required>
                                </div>

                                <div class="mb-4">
                                    <label for="dynamic_slug" class="form-label fw-bold text-white small d-flex justify-content-between">
                                        <span>Slug Curto Personalizado <span class="text-secondary fw-normal">(Opcional)</span></span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-body-tertiary border-secondary text-secondary small">{{ url('/q') }}/</span>
                                        <input type="text" class="form-control bg-body border-secondary text-body" id="dynamic_slug" name="slug" placeholder="ex: cardapio-verao" style="text-transform: lowercase;">
                                    </div>
                                    <div class="form-text text-secondary small"><i class="fa-solid fa-circle-info me-1"></i> Deixe em branco para gerarmos um código aleatório curto de 6 dígitos.</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-gradient-primary py-2.5 rounded-pill fw-bold shadow-sm">
                                        <i class="fa-solid fa-circle-plus me-2"></i> Criar QR Code Dinâmico
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr class="my-4 border-secondary border-opacity-25">

                    {{-- Personalização --}}
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-white font-heading">
                        <i class="fa-solid fa-palette text-primary"></i> Estilo e Personalização
                    </h5>

                    <div class="accordion" id="customizationAccordion">
                        
                        {{-- Cores --}}
                        <div class="accordion-item border border-secondary border-opacity-25 mb-2 rounded-3 overflow-hidden bg-body-tertiary">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold py-3 small bg-body text-body" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCores" aria-expanded="true">
                                    🎨 Cores & Estilo Visual
                                </button>
                            </h2>
                            <div id="collapseCores" class="accordion-collapse collapse show" data-bs-parent="#customizationAccordion">
                                <div class="accordion-body bg-body row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label small fw-bold text-secondary">Cor do QR Code (Frente)</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="color" class="form-control form-control-color border-0 rounded-circle" id="color_front" value="#0f172a" style="width: 40px; height: 40px; padding: 0; cursor: pointer;">
                                            <input type="text" class="form-control form-control-sm bg-body border-secondary text-body" id="color_front_text" value="#0F172A">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label small fw-bold text-secondary">Cor do Fundo</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="color" class="form-control form-control-color border-0 rounded-circle" id="color_back" value="#ffffff" style="width: 40px; height: 40px; padding: 0; cursor: pointer;">
                                            <input type="text" class="form-control form-control-sm bg-body border-secondary text-body" id="color_back_text" value="#FFFFFF">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Margens e Tamanhos --}}
                        <div class="accordion-item border border-secondary border-opacity-25 mb-2 rounded-3 overflow-hidden bg-body-tertiary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3 small bg-body text-body" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDimensoes">
                                    📐 Margem & Resolução
                                </button>
                            </h2>
                            <div id="collapseDimensoes" class="accordion-collapse collapse" data-bs-parent="#customizationAccordion">
                                <div class="accordion-body bg-body row g-3">
                                    <div class="col-sm-6">
                                        <label for="qr_margin" class="form-label small fw-bold text-secondary d-flex justify-content-between">
                                            <span>Zona de Silêncio (Margem)</span>
                                            <span class="text-primary" id="margin_val">1 bloco</span>
                                        </label>
                                        <input type="range" class="form-range" id="qr_margin" min="0" max="8" value="1">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="qr_size" class="form-label small fw-bold text-secondary d-flex justify-content-between">
                                            <span>Tamanho de Visualização</span>
                                            <span class="text-primary" id="size_val">250 px</span>
                                        </label>
                                        <input type="range" class="form-range" id="qr_size" min="150" max="400" step="10" value="250">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Logotipo Central --}}
                        <div class="accordion-item border border-secondary border-opacity-25 mb-2 rounded-3 overflow-hidden bg-body-tertiary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3 small bg-body text-body" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLogo">
                                    🛡️ Logotipo no Centro
                                </button>
                            </h2>
                            <div id="collapseLogo" class="accordion-collapse collapse" data-bs-parent="#customizationAccordion">
                                <div class="accordion-body bg-body">
                                    <label class="form-label small fw-bold text-secondary mb-2">Selecione um Ícone ou Envie seu Logo</label>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-3 logo-select active" data-logo="none">Sem Logo</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-3 logo-select" data-logo="web">🌐 Web</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-3 logo-select" data-logo="whatsapp">💬 WhatsApp</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-3 logo-select" data-logo="instagram">📱 Instagram</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-1 px-3 logo-select" data-logo="email">📧 Email</button>
                                    </div>

                                    <div class="mb-3">
                                        <label for="custom_logo" class="form-label small fw-bold text-secondary">Carregar Imagem de Logo (PNG/JPG)</label>
                                        <input class="form-control form-control-sm bg-body border-secondary text-body" type="file" id="custom_logo" accept="image/png, image/jpeg, image/jpg">
                                    </div>

                                    <div class="row g-2 align-items-center">
                                        <div class="col-8">
                                            <label for="logo_size" class="form-label small fw-bold text-secondary d-flex justify-content-between m-0">
                                                <span>Escala do Logo</span>
                                                <span class="text-primary" id="logo_size_val">20%</span>
                                            </label>
                                            <input type="range" class="form-range" id="logo_size" min="10" max="30" value="20">
                                        </div>
                                        <div class="col-4">
                                            <div class="alert alert-warning py-1 px-2 m-0 text-center" style="font-size: 0.65rem;">
                                                Logos muito grandes podem reduzir a legibilidade
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tolerância a Erros --}}
                        <div class="accordion-item border border-secondary border-opacity-25 mb-2 rounded-3 overflow-hidden bg-body-tertiary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3 small bg-body text-body" type="button" data-bs-toggle="collapse" data-bs-target="#collapseError">
                                    🛡️ Correção de Erros (ECL)
                                </button>
                            </h2>
                            <div id="collapseError" class="accordion-collapse collapse" data-bs-parent="#customizationAccordion">
                                <div class="accordion-body bg-body">
                                    <label for="error_level" class="form-label small fw-bold text-secondary">Nível de Correção de Erros</label>
                                    <select class="form-select bg-body border-secondary text-body" id="error_level">
                                        <option value="L">L - Baixo (7% recuperável)</option>
                                        <option value="M">M - Médio (15% recuperável)</option>
                                        <option value="Q">Q - Alto (25% recuperável)</option>
                                        <option value="H" selected>H - Máximo (30% recuperável) [Recomendado para Logo]</option>
                                    </select>
                                    <div class="form-text text-secondary mt-2 small">Níveis mais altos permitem que o QR Code continue sendo lido com facilidade mesmo com logotipo no centro.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Coluna da Direita: Live Preview --}}
        <div class="col-xl-5 col-lg-6">
            <div class="card-custom overflow-hidden position-sticky" style="top: 90px;">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold m-0 text-white d-flex align-items-center gap-2 font-heading">
                        <i class="fa-solid fa-eye text-primary"></i> Live Preview
                    </h5>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small px-2">Atualizado ao Vivo</span>
                </div>

                {{-- Preview Container --}}
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center bg-black bg-opacity-25 position-relative overflow-hidden" style="min-height: 380px;">
                    
                    {{-- Glow Effect --}}
                    <div class="position-absolute rounded-circle bg-primary opacity-20" style="width: 250px; height: 250px; filter: blur(60px); top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1;"></div>
                    
                    {{-- Canvas Box --}}
                    <div class="bg-white p-3 rounded-4 shadow-lg d-inline-block border position-relative" style="z-index: 2; min-width: 200px; min-height: 200px;">
                        <canvas id="qr_canvas" style="display: block; max-width: 100%; height: auto;"></canvas>
                    </div>

                    {{-- URL preview string --}}
                    <div class="text-center mt-3 position-relative" style="z-index: 2; max-width: 90%;">
                        <code class="text-truncate d-block text-secondary small p-2 rounded bg-body border border-secondary border-opacity-25" id="qr_target_preview" style="max-width: 320px;">-</code>
                    </div>
                </div>

                {{-- Export Actions --}}
                <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-4">
                    <h6 class="fw-bold text-secondary mb-3 small text-uppercase">Exportação do Código</h6>
                    
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-gradient-primary w-100 py-2.5 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2" id="download_png">
                                <i class="fa-solid fa-file-image"></i> Baixar PNG
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-outline-primary w-100 py-2.5 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2" id="download_svg">
                                <i class="fa-solid fa-code"></i> Baixar SVG
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-semibold small d-flex align-items-center justify-content-center gap-2" id="copy_link">
                                <i class="fa-solid fa-copy"></i> Copiar Link
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-outline-secondary w-100 py-2 rounded-pill fw-semibold small d-flex align-items-center justify-content-center gap-2" id="copy_image">
                                <i class="fa-solid fa-clipboard"></i> Copiar Imagem
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Seção: Meus QR Codes Dinâmicos --}}
    <div class="card-custom overflow-hidden mt-5">
        <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="fw-bold m-0 text-white d-flex align-items-center gap-2 font-heading">
                    <i class="fa-solid fa-qrcode text-warning"></i> Meus QR Codes Dinâmicos
                </h5>
                <p class="text-secondary small m-0 mt-0.5">Gerencie os links de destino e acompanhe métricas de acesso em tempo real.</p>
            </div>
            <span class="badge bg-primary rounded-pill fw-bold px-3 py-2">{{ $codigosQr->count() }} cadastrados</span>
        </div>

        <div class="card-body p-0">
            @if($codigosQr->isEmpty())
                <div class="text-center py-5 bg-body-tertiary">
                    <span class="display-4 text-secondary opacity-25">⚡</span>
                    <h5 class="fw-bold text-secondary mt-3">Nenhum QR Code dinâmico criado ainda</h5>
                    <p class="text-muted small mb-4">Crie seu primeiro QR Code Dinâmico na aba acima para começar a rastrear acessos!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0" style="font-size: 0.85rem;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-3">Título / Data</th>
                                <th class="py-3">Link Curto (/q/slug)</th>
                                <th class="py-3">Destino Atual</th>
                                <th class="py-3 text-center">Leituras</th>
                                <th class="pe-4 py-3 text-end" style="width: 220px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($codigosQr as $codigo)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-white">{{ $codigo->titulo }}</div>
                                        <div class="text-secondary small mt-0.5"><i class="fa-regular fa-calendar me-1"></i> {{ $codigo->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <code class="text-primary small fw-bold">{{ route('qr.redirect', $codigo->slug) }}</code>
                                            <button class="btn btn-sm btn-link p-0 link-secondary" onclick="copyToClipboardText('{{ route('qr.redirect', $codigo->slug) }}')" title="Copiar link curto">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-truncate text-secondary d-inline-block" style="max-width: 250px;" title="{{ $codigo->url_destino }}">
                                            <i class="fa-solid fa-arrow-turn-up text-success me-1"></i> {{ $codigo->url_destino }}
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                            📶 {{ $codigo->total_leituras }} scans
                                        </span>
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="btn-group gap-1">
                                            <button class="btn btn-sm btn-outline-primary rounded-pill px-2.5 btn-show-qr" data-titulo="{{ $codigo->titulo }}" data-url="{{ route('qr.redirect', $codigo->slug) }}" title="Visualizar QR Code">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning rounded-pill px-2.5 btn-edit-qr" data-id="{{ $codigo->id }}" data-titulo="{{ $codigo->titulo }}" data-url="{{ $codigo->url_destino }}" title="Editar Link de Destino">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info rounded-pill px-2.5 btn-stats-qr" data-id="{{ $codigo->id }}" title="Ver Estatísticas Detalhadas">
                                                <i class="fa-solid fa-chart-simple"></i>
                                            </button>
                                            <form action="{{ route('qr.destroy', $codigo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este QR Code? O histórico de acessos será excluído permanentemente.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" title="Excluir">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif
        </div>
    </div>
</div>

{{-- MODAL 1: Visualização do QR Code --}}
<div class="modal fade" id="qrViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-custom border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="qrModalTitle">Visualizar QR Code</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="bg-white p-3 rounded-4 d-inline-block shadow mb-3 border">
                    <canvas id="modal_qr_canvas" style="display: block; margin: 0 auto; max-width: 100%; height: auto;"></canvas>
                </div>
                
                <div class="mb-3">
                    <code class="d-block small p-2 rounded bg-body border border-secondary border-opacity-25" id="modalQrLink">-</code>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-gradient-primary rounded-pill py-2.5 fw-bold d-flex align-items-center justify-content-center gap-2" id="download_modal_png">
                        <i class="fa-solid fa-download"></i> Baixar Imagem PNG
                    </button>
                    <button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">Fechar Janela</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: Edição do Link do QR Code Dinâmico --}}
<div class="modal fade" id="qrEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-custom border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark py-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-pencil me-2"></i> Editar QR Code Dinâmico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form id="editQrForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label fw-bold text-white small">Título do QR Code</label>
                        <input type="text" class="form-control bg-body border-secondary text-body" id="edit_title" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_url" class="form-label fw-bold text-white small">Novo Link de Destino Final (URL)</label>
                        <input type="url" class="form-control bg-body border-secondary text-body" id="edit_url" name="url_destino" required>
                        <div class="form-text text-warning mt-2 small">
                            ⚠️ Alterar esta URL redirecionará imediatamente os próximos leitores do QR Code impresso para este novo destino. O código visual permanecerá o mesmo!
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 3: Estatísticas de Acesso (Analytics) --}}
<div class="modal fade" id="qrStatsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content card-custom border-0 shadow-lg">
            <div class="modal-header bg-info text-dark py-3">
                <h5 class="modal-title fw-bold" id="statsModalTitle"><i class="fa-solid fa-chart-simple me-2"></i> Estatísticas de Acesso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-0">
                
                {{-- Spinner de carregamento --}}
                <div id="stats_loading" class="text-center py-5">
                    <div class="spinner-border text-info" role="status"></div>
                    <p class="mt-2 text-secondary small">Carregando logs de acessos...</p>
                </div>

                {{-- Estado Vazio --}}
                <div id="stats_empty" class="text-center py-5 d-none bg-body-tertiary">
                    <span class="display-4 text-secondary opacity-25">📈</span>
                    <p class="mt-3 text-secondary fw-bold">Nenhum scan registrado ainda</p>
                    <p class="text-muted small">Compartilhe o QR Code ou faça uma leitura de teste para ver as estatísticas aparecerem aqui!</p>
                </div>

                {{-- Listagem de scans --}}
                <div id="stats_container" class="table-responsive d-none">
                    <table class="table table-hover align-middle m-0" style="font-size: 0.8rem;">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4 py-2.5">Data & Hora</th>
                                <th class="py-2.5">Endereço IP</th>
                                <th class="pe-4 py-2.5">Dispositivo / Navegador</th>
                            </tr>
                        </thead>
                        <tbody id="stats_table_body">
                            <!-- Inserido dinamicamente via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary border-opacity-25">
                <span class="me-auto text-secondary small"><i class="fa-solid fa-clock"></i> Mostrando últimos 100 scans</span>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    window.QrStudioConfig = {
        baseUrl: "{{ url('') }}"
    };
</script>
{{-- Biblioteca QRCode.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
{{-- Script principal do QR --}}
<script src="{{ asset('js/qr.js') }}"></script>
@endsection
