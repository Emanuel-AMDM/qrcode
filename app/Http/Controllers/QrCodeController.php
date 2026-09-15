<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\QrCodeScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    /**
     * Tela principal do gerador de QR Code.
     */
    public function index()
    {
        $codigosQr = QrCode::where('usuario_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('qr.index', ['codigosQr' => $codigosQr]);
    }

    /**
     * Cria um novo QR Code dinâmico.
     */
    public function store(Request $requisicao)
    {
        $requisicao->validate([
            'titulo'      => 'required|string|max:255',
            'url_destino' => 'required|url:http,https|max:2048',
            'slug'        => 'nullable|alpha_dash|max:50|unique:qr_codes,slug',
        ], [
            'titulo.required'      => 'O título é obrigatório.',
            'url_destino.required' => 'A URL de destino é obrigatória.',
            'url_destino.url'      => 'Informe uma URL válida começando com http:// ou https://.',
            'slug.alpha_dash'      => 'O slug personalizado deve conter apenas letras, números e hífens.',
            'slug.unique'          => 'Este slug já está em uso por outro QR Code.',
            'slug.max'             => 'O slug personalizado não pode passar de 50 caracteres.',
        ]);

        $slug = $requisicao->slug;

        if (empty($slug)) {
            do {
                $slug = strtolower(Str::random(6));
            } while (QrCode::where('slug', $slug)->exists());
        } else {
            $slug = strtolower($slug);
        }

        QrCode::create([
            'usuario_id'     => Auth::id(),
            'titulo'         => strip_tags($requisicao->titulo),
            'slug'           => $slug,
            'url_destino'    => $requisicao->url_destino,
            'total_leituras' => 0,
        ]);

        return redirect()->route('qr.index')->with('success_toast', 'QR Code dinâmico criado com sucesso!');
    }

    /**
     * Atualiza o título e/ou a URL de destino de um QR Code dinâmico existente.
     */
    public function update(Request $requisicao, QrCode $codigoQr)
    {
        if ($codigoQr->usuario_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $requisicao->validate([
            'titulo'      => 'required|string|max:255',
            'url_destino' => 'required|url:http,https|max:2048',
        ], [
            'titulo.required'      => 'O título é obrigatório.',
            'url_destino.required' => 'A URL de destino é obrigatória.',
            'url_destino.url'      => 'Informe uma URL válida começando com http:// ou https://.',
        ]);

        $codigoQr->update([
            'titulo'      => strip_tags($requisicao->titulo),
            'url_destino' => $requisicao->url_destino,
        ]);

        return redirect()->route('qr.index')->with('success_toast', 'URL de destino atualizada com sucesso!');
    }

    /**
     * Exclui um QR Code dinâmico.
     */
    public function destroy(QrCode $codigoQr)
    {
        if ($codigoQr->usuario_id !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $codigoQr->delete();

        return redirect()->route('qr.index')->with('success_toast', 'QR Code dinâmico excluído com sucesso!');
    }

    /**
     * Rota pública de redirecionamento do QR Code dinâmico.
     */
    public function redirect(Request $requisicao, $slug)
    {
        $codigoQr = QrCode::where('slug', strtolower($slug))->firstOrFail();

        // Sanitiza e limita o tamanho do User-Agent para proteção contra Stored XSS e buffer overflow
        $agenteUsuarioLimpo = strip_tags(substr($requisicao->userAgent() ?? 'Desconhecido', 0, 500));
        $ipLimpo = strip_tags(substr($requisicao->ip() ?? 'Desconhecido', 0, 45));

        // Registra a leitura
        QrCodeScan::create([
            'qr_code_id'     => $codigoQr->id,
            'endereco_ip'    => $ipLimpo,
            'agente_usuario' => $agenteUsuarioLimpo,
        ]);

        // Incrementa o contador
        $codigoQr->increment('total_leituras');

        return redirect()->away($codigoQr->url_destino, 302);
    }

    /**
     * Devolve as estatísticas de acesso de um QR Code dinâmico em JSON.
     */
    public function stats(QrCode $codigoQr)
    {
        if ($codigoQr->usuario_id !== Auth::id()) {
            return response()->json(['error' => 'Acesso não autorizado.'], 403);
        }

        $leituras = $codigoQr->leituras()
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get()
            ->map(function ($leitura) {
                return [
                    'ip'             => e($leitura->endereco_ip ?? 'Desconhecido'),
                    'agente_usuario' => e($leitura->agente_usuario ?? 'Desconhecido'),
                    'data'           => $leitura->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i:s'),
                ];
            });

        return response()->json([
            'titulo'   => e($codigoQr->titulo),
            'slug'     => e($codigoQr->slug),
            'leituras' => $leituras
        ]);
    }
}

