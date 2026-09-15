/**
 * QR Studio - Gerador de QR Code
 * Gerenciamento interativo, previews em tempo real, customização de logos e estatísticas (Analytics).
 */

function inicializarGeradorQrCode() {
    const canvas = document.getElementById('qr_canvas');
    if (!canvas) return;

    // Inputs e seletores do painel de controle
    const inputUrlEstatica = document.getElementById('static_url');
    const inputTituloDinamico = document.getElementById('dynamic_title');
    const inputUrlDinamica = document.getElementById('dynamic_url');
    const inputSlugDinamico = document.getElementById('dynamic_slug');

    const corFrente = document.getElementById('color_front');
    const textoCorFrente = document.getElementById('color_front_text');
    const corFundo = document.getElementById('color_back');
    const textoCorFundo = document.getElementById('color_back_text');

    const margemQr = document.getElementById('qr_margin');
    const valorMargem = document.getElementById('margin_val');
    const tamanhoQr = document.getElementById('qr_size');
    const valorTamanho = document.getElementById('size_val');

    const botoesLogo = document.querySelectorAll('.logo-select');
    const inputLogoCustomizado = document.getElementById('custom_logo');
    const tamanhoLogo = document.getElementById('logo_size');
    const valorTamanhoLogo = document.getElementById('logo_size_val');

    const nivelErro = document.getElementById('error_level');
    const previewDestinoQr = document.getElementById('qr_target_preview');

    // Botões de Ação
    const botaoBaixarPng = document.getElementById('download_png');
    const botaoBaixarSvg = document.getElementById('download_svg');
    const botaoCopiarLink = document.getElementById('copy_link');
    const botaoCopiarImagem = document.getElementById('copy_image');

    // Variáveis de Estado
    let abaAtiva = 'static';
    let logoSelecionado = 'none';
    let srcLogoCustomizado = null;

    // Monitoramento da aba ativa
    const botaoAbaEstatica = document.getElementById('static-tab');
    const botaoAbaDinamica = document.getElementById('dynamic-tab');

    if (botaoAbaEstatica && botaoAbaDinamica) {
        botaoAbaEstatica.addEventListener('shown.bs.tab', () => {
            abaAtiva = 'static';
            renderizarQrCode();
        });
        botaoAbaDinamica.addEventListener('shown.bs.tab', () => {
            abaAtiva = 'dynamic';
            renderizarQrCode();
        });
    }

    // Color Pickers vinculados
    if (corFrente && textoCorFrente) {
        corFrente.addEventListener('input', (evento) => {
            textoCorFrente.value = evento.target.value.toUpperCase();
            renderizarQrCode();
        });
        textoCorFrente.addEventListener('input', (evento) => {
            if (/^#[0-9A-F]{6}$/i.test(evento.target.value)) {
                corFrente.value = evento.target.value;
                renderizarQrCode();
            }
        });
    }

    if (corFundo && textoCorFundo) {
        corFundo.addEventListener('input', (evento) => {
            textoCorFundo.value = evento.target.value.toUpperCase();
            renderizarQrCode();
        });
        textoCorFundo.addEventListener('input', (evento) => {
            if (/^#[0-9A-F]{6}$/i.test(evento.target.value)) {
                corFundo.value = evento.target.value;
                renderizarQrCode();
            }
        });
    }

    // Inputs e Sliders
    if (margemQr && valorMargem) {
        margemQr.addEventListener('input', (evento) => {
            valorMargem.textContent = `${evento.target.value} ${parseInt(evento.target.value) === 1 ? 'bloco' : 'blocos'}`;
            renderizarQrCode();
        });
    }

    if (tamanhoQr && valorTamanho) {
        tamanhoQr.addEventListener('input', (evento) => {
            valorTamanho.textContent = `${evento.target.value} px`;
            renderizarQrCode();
        });
    }

    if (tamanhoLogo && valorTamanhoLogo) {
        tamanhoLogo.addEventListener('input', (evento) => {
            valorTamanhoLogo.textContent = `${evento.target.value}%`;
            renderizarQrCode();
        });
    }

    if (inputUrlEstatica) inputUrlEstatica.addEventListener('input', renderizarQrCode);
    if (inputUrlDinamica) inputUrlDinamica.addEventListener('input', renderizarQrCode);
    if (inputSlugDinamico) inputSlugDinamico.addEventListener('input', renderizarQrCode);
    if (nivelErro) nivelErro.addEventListener('change', renderizarQrCode);

    // Presets de Logo
    botoesLogo.forEach(botao => {
        botao.addEventListener('click', () => {
            botoesLogo.forEach(b => b.classList.remove('active'));
            botao.classList.add('active');
            logoSelecionado = botao.getAttribute('data-logo');

            if (logoSelecionado !== 'none') {
                if (inputLogoCustomizado) inputLogoCustomizado.value = '';
                srcLogoCustomizado = null;
            }
            renderizarQrCode();
        });
    });

    // Upload de Logo Customizado
    if (inputLogoCustomizado) {
        inputLogoCustomizado.addEventListener('change', (evento) => {
            const arquivo = evento.target.files[0];
            if (arquivo) {
                const leitor = new FileReader();
                leitor.onload = function(eventoCarregado) {
                    srcLogoCustomizado = eventoCarregado.target.result;
                    logoSelecionado = 'custom';
                    botoesLogo.forEach(b => b.classList.remove('active'));
                    renderizarQrCode();
                };
                leitor.readAsDataURL(arquivo);
            }
        });
    }

    function renderizarQrCode() {
        if (typeof QRCode === 'undefined') {
            console.error('Biblioteca QRCode.js não carregada.');
            return;
        }

        const urlBase = (window.QrStudioConfig && window.QrStudioConfig.baseUrl) ? window.QrStudioConfig.baseUrl : window.location.origin;

        let texto = 'https://google.com';
        if (abaAtiva === 'static') {
            texto = (inputUrlEstatica && inputUrlEstatica.value.trim()) ? inputUrlEstatica.value.trim() : 'https://google.com';
        } else {
            const slug = (inputSlugDinamico && inputSlugDinamico.value.trim().toLowerCase()) ? inputSlugDinamico.value.trim().toLowerCase() : 'exemplo';
            texto = `${urlBase}/q/${slug}`;
        }

        if (previewDestinoQr) {
            previewDestinoQr.textContent = texto;
        }

        const opcoes = {
            width: tamanhoQr ? parseInt(tamanhoQr.value) : 250,
            margin: margemQr ? parseInt(margemQr.value) : 1,
            color: {
                dark: corFrente ? corFrente.value : '#0f172a',
                light: corFundo ? corFundo.value : '#ffffff'
            },
            errorCorrectionLevel: nivelErro ? nivelErro.value : 'H'
        };

        QRCode.toCanvas(canvas, texto, opcoes, function (erro) {
            if (erro) {
                console.error(erro);
                return;
            }

            if (logoSelecionado !== 'none') {
                desenharLogoCentro(canvas);
            }
        });
    }

    function desenharLogoCentro(canvasAlvo) {
        const contexto = canvasAlvo.getContext('2d');
        const largura = canvasAlvo.width;
        const altura = canvasAlvo.height;
        const centroX = largura / 2;
        const centroY = altura / 2;

        const escala = (tamanhoLogo ? parseInt(tamanhoLogo.value) : 20) / 100;
        const larguraLogo = largura * escala;
        const alturaLogo = altura * escala;

        function desenharFundoLogo() {
            contexto.fillStyle = corFundo ? corFundo.value : '#ffffff';
            contexto.beginPath();
            contexto.arc(centroX, centroY, (larguraLogo / 2) * 1.25, 0, 2 * Math.PI);
            contexto.fill();
        }

        if (logoSelecionado === 'custom' && srcLogoCustomizado) {
            const img = new Image();
            img.src = srcLogoCustomizado;
            img.onload = function() {
                desenharFundoLogo();
                contexto.drawImage(img, centroX - larguraLogo / 2, centroY - alturaLogo / 2, larguraLogo, alturaLogo);
            };
        } else {
            let textoIcone = '';
            switch(logoSelecionado) {
                case 'web': textoIcone = '🌐'; break;
                case 'whatsapp': textoIcone = '💬'; break;
                case 'instagram': textoIcone = '📱'; break;
                case 'email': textoIcone = '✉️'; break;
            }

            if (textoIcone) {
                desenharFundoLogo();
                contexto.font = `${larguraLogo * 0.8}px Arial`;
                contexto.textAlign = 'center';
                contexto.textBaseline = 'middle';
                contexto.fillText(textoIcone, centroX, centroY);
            }
        }
    }

    if (botaoBaixarPng) {
        botaoBaixarPng.addEventListener('click', () => {
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = `qrcode-${abaAtiva}.png`;
            link.href = dataUrl;
            link.click();
            exibirToast('QR Code PNG baixado com sucesso!');
        });
    }

    if (botaoBaixarSvg) {
        botaoBaixarSvg.addEventListener('click', () => {
            const urlBase = (window.QrStudioConfig && window.QrStudioConfig.baseUrl) ? window.QrStudioConfig.baseUrl : window.location.origin;
            let texto = 'https://google.com';
            if (abaAtiva === 'static') {
                texto = (inputUrlEstatica && inputUrlEstatica.value.trim()) ? inputUrlEstatica.value.trim() : 'https://google.com';
            } else {
                const slug = (inputSlugDinamico && inputSlugDinamico.value.trim().toLowerCase()) ? inputSlugDinamico.value.trim().toLowerCase() : 'exemplo';
                texto = `${urlBase}/q/${slug}`;
            }

            const opcoes = {
                width: tamanhoQr ? parseInt(tamanhoQr.value) : 250,
                margin: margemQr ? parseInt(margemQr.value) : 1,
                color: {
                    dark: corFrente ? corFrente.value : '#0f172a',
                    light: corFundo ? corFundo.value : '#ffffff'
                },
                type: 'svg',
                errorCorrectionLevel: nivelErro ? nivelErro.value : 'H'
            };

            QRCode.toString(texto, opcoes, function (erro, stringSvg) {
                if (erro) {
                    console.error(erro);
                    return;
                }
                const blob = new Blob([stringSvg], { type: 'image/svg+xml' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.download = `qrcode-${abaAtiva}.svg`;
                link.href = url;
                link.click();
                URL.revokeObjectURL(url);
                exibirToast('QR Code SVG baixado com sucesso!');
            });
        });
    }

    if (botaoCopiarLink) {
        botaoCopiarLink.addEventListener('click', () => {
            const urlBase = (window.QrStudioConfig && window.QrStudioConfig.baseUrl) ? window.QrStudioConfig.baseUrl : window.location.origin;
            let texto = 'https://google.com';
            if (abaAtiva === 'static') {
                texto = (inputUrlEstatica && inputUrlEstatica.value.trim()) ? inputUrlEstatica.value.trim() : 'https://google.com';
            } else {
                const slug = (inputSlugDinamico && inputSlugDinamico.value.trim().toLowerCase()) ? inputSlugDinamico.value.trim().toLowerCase() : 'exemplo';
                texto = `${urlBase}/q/${slug}`;
            }

            navigator.clipboard.writeText(texto).then(() => {
                exibirToast('Link copiado para a área de transferência!');
            });
        });
    }

    if (botaoCopiarImagem) {
        botaoCopiarImagem.addEventListener('click', () => {
            canvas.toBlob(function(blob) {
                if (!blob) {
                    console.error('Falha ao gerar blob do canvas');
                    return;
                }
                try {
                    navigator.clipboard.write([
                        new ClipboardItem({ 'image/png': blob })
                    ]).then(() => {
                        exibirToast('Imagem copiada para a área de transferência!');
                    }).catch(erro => {
                        console.error('Erro ao copiar imagem:', erro);
                        exibirToast('Seu navegador bloqueou a cópia direta de imagens.', 'bg-danger');
                    });
                } catch (excecao) {
                    console.error(excecao);
                    exibirToast('Cópia de imagem não suportada neste navegador.', 'bg-danger');
                }
            }, 'image/png');
        });
    }

    renderizarQrCode();
}

function escapeHtml(unsafe) {
    if (typeof unsafe !== 'string') return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function exibirToast(mensagem, classeFundo = 'bg-success') {
    const container = document.querySelector('.toast-container');
    if (!container) return;

    const toastDiv = document.createElement('div');
    toastDiv.className = `toast align-items-center text-white border-0 shadow-lg ${classeFundo}`;
    toastDiv.setAttribute('role', 'alert');
    toastDiv.innerHTML = `
        <div class="d-flex">
            <div class="toast-body fw-semibold">
                <i class="fa-solid fa-circle-check me-2"></i> ${escapeHtml(mensagem)}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    container.appendChild(toastDiv);
    const toast = new bootstrap.Toast(toastDiv, { delay: 3500 });
    toast.show();

    toastDiv.addEventListener('hidden.bs.toast', () => {
        toastDiv.remove();
    });
}

window.copiarTextoClipboard = function(texto) {
    navigator.clipboard.writeText(texto).then(() => {
        exibirToast('Link copiado com sucesso!');
    });
};
window.copyToClipboardText = window.copiarTextoClipboard;

window.exibirModalQr = function(titulo, url) {
    const elementoModal = document.getElementById('qrViewModal');
    const elementoTitulo = document.getElementById('qrModalTitle');
    const elementoLink = document.getElementById('modalQrLink');
    const canvas = document.getElementById('modal_qr_canvas');
    const botaoBaixar = document.getElementById('download_modal_png');

    if (elementoModal && canvas) {
        elementoTitulo.textContent = titulo;
        elementoLink.textContent = url;

        if (typeof QRCode !== 'undefined') {
            QRCode.toCanvas(canvas, url, {
                width: 250,
                margin: 1,
                errorCorrectionLevel: 'H'
            }, function(erro) {
                if (erro) console.error(erro);
            });
        }

        botaoBaixar.onclick = function() {
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = `qrcode-${titulo.replace(/[^a-z0-9]/gi, '_').toLowerCase()}.png`;
            link.href = dataUrl;
            link.click();
        };

        const modal = new bootstrap.Modal(elementoModal);
        modal.show();
    }
};
window.showQrModal = window.exibirModalQr;

window.exibirModalEditar = function(id, titulo, url) {
    const elementoModal = document.getElementById('qrEditModal');
    const formulario = document.getElementById('editQrForm');
    const inputTitulo = document.getElementById('edit_title');
    const inputUrl = document.getElementById('edit_url');
    const urlBase = (window.QrStudioConfig && window.QrStudioConfig.baseUrl) ? window.QrStudioConfig.baseUrl : window.location.origin;

    if (elementoModal && formulario) {
        formulario.action = `${urlBase}/qr-codes/${id}`;
        inputTitulo.value = titulo;
        inputUrl.value = url;

        const modal = new bootstrap.Modal(elementoModal);
        modal.show();
    }
};
window.showEditModal = window.exibirModalEditar;

window.exibirModalEstatisticas = function(id) {
    const elementoModal = document.getElementById('qrStatsModal');
    const elementoTitulo = document.getElementById('statsModalTitle');
    const elementoCarregando = document.getElementById('stats_loading');
    const elementoVazio = document.getElementById('stats_empty');
    const elementoContainer = document.getElementById('stats_container');
    const corpoTabela = document.getElementById('stats_table_body');
    const urlBase = (window.QrStudioConfig && window.QrStudioConfig.baseUrl) ? window.QrStudioConfig.baseUrl : window.location.origin;

    if (elementoModal) {
        elementoTitulo.innerHTML = `<i class="fa-solid fa-chart-simple me-2"></i> Carregando estatísticas...`;
        elementoCarregando.classList.remove('d-none');
        elementoVazio.classList.add('d-none');
        elementoContainer.classList.add('d-none');
        corpoTabela.innerHTML = '';

        const modal = new bootstrap.Modal(elementoModal);
        modal.show();

        fetch(`${urlBase}/qr-codes/${id}/stats`)
            .then(resposta => resposta.json())
            .then(dados => {
                elementoCarregando.classList.add('d-none');

                if (dados.error || dados.erro) {
                    elementoTitulo.textContent = 'Erro de Acesso';
                    corpoTabela.innerHTML = `<tr><td colspan="3" class="text-center text-danger">${escapeHtml(dados.error || dados.erro)}</td></tr>`;
                    elementoContainer.classList.remove('d-none');
                    return;
                }

                const tituloExibicao = dados.titulo || dados.title || '';
                const slugExibicao = dados.slug || '';
                const listaLeituras = dados.leituras || dados.scans || [];

                elementoTitulo.innerHTML = `<i class="fa-solid fa-chart-simple me-2"></i> Estatísticas: ${escapeHtml(tituloExibicao)} (/q/${escapeHtml(slugExibicao)})`;

                if (listaLeituras.length === 0) {
                    elementoVazio.classList.remove('d-none');
                } else {
                    listaLeituras.forEach(leitura => {
                        const tr = document.createElement('tr');

                        let agente = leitura.agente_usuario || leitura.user_agent || 'Desconhecido';
                        let dispositivo = 'Computador';
                        if (/mobi|android|iphone|ipad/i.test(agente)) {
                            dispositivo = 'Celular/Tablet';
                        }

                        let navegador = 'Navegador';
                        if (/chrome/i.test(agente)) navegador = 'Chrome';
                        else if (/safari/i.test(agente) && !/chrome/i.test(agente)) navegador = 'Safari';
                        else if (/firefox/i.test(agente)) navegador = 'Firefox';
                        else if (/edge/i.test(agente)) navegador = 'Edge';

                        const dataFormatada = escapeHtml(leitura.data || leitura.date || '');
                        const ipFormatado = escapeHtml(leitura.ip || '');
                        const agenteFormatado = escapeHtml(agente);
                        const dispositivoFormatado = escapeHtml(dispositivo);
                        const navegadorFormatado = escapeHtml(navegador);

                        tr.innerHTML = `
                            <td class="ps-4 py-2.5 fw-bold text-white">${dataFormatada}</td>
                            <td class="py-2.5"><code class="text-secondary">${ipFormatado}</code></td>
                            <td class="pe-4 py-2.5 text-secondary text-truncate" style="max-width: 320px;" title="${agenteFormatado}">
                                <span class="badge bg-secondary-subtle text-secondary me-1">${dispositivoFormatado}</span>
                                <span class="badge bg-dark border border-secondary text-light me-2">${navegadorFormatado}</span>
                                <span class="opacity-50 small">${agenteFormatado}</span>
                            </td>
                        `;
                        corpoTabela.appendChild(tr);
                    });
                    elementoContainer.classList.remove('d-none');
                }
            })
            .catch(erro => {
                console.error(erro);
                elementoCarregando.classList.add('d-none');
                elementoTitulo.textContent = 'Falha de Comunicação';
                corpoTabela.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Não foi possível carregar as estatísticas. Tente novamente mais tarde.</td></tr>`;
                elementoContainer.classList.remove('d-none');
            });
    }
};
window.showStatsModal = window.exibirModalEstatisticas;

// Delegação de eventos segura para os botões de ação da tabela
document.addEventListener('click', function (evento) {
    const botaoVisualizar = evento.target.closest('.btn-show-qr');
    if (botaoVisualizar) {
        window.exibirModalQr(botaoVisualizar.dataset.titulo || botaoVisualizar.dataset.title || '', botaoVisualizar.dataset.url || '');
        return;
    }
    const botaoEditar = evento.target.closest('.btn-edit-qr');
    if (botaoEditar) {
        window.exibirModalEditar(botaoEditar.dataset.id || '', botaoEditar.dataset.titulo || botaoEditar.dataset.title || '', botaoEditar.dataset.url || '');
        return;
    }
    const botaoEstatisticas = evento.target.closest('.btn-stats-qr');
    if (botaoEstatisticas) {
        window.exibirModalEstatisticas(botaoEstatisticas.dataset.id || '');
        return;
    }
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarGeradorQrCode);
} else {
    inicializarGeradorQrCode();
}

