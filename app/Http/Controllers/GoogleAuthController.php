<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleAuthController extends Controller
{
    /**
     * Redireciona o usuário para a página de autenticação do Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Processa o retorno (callback) do Google OAuth.
     */
    public function callback(Request $request)
    {
        // Se o usuário cancelou o login no Google
        if ($request->has('error')) {
            Log::warning('Autenticação com o Google cancelada ou com erro: ' . $request->get('error'));
            return redirect()->route('login')->with('error', 'A autenticação com o Google foi cancelada.');
        }

        try {
            // Usa stateless() diretamente para eliminar o erro InvalidStateException causado pelo cross-site redirect
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            Log::error('Erro ao autenticar com Google: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->with('error', 'Falha ao autenticar com o Google. Tente novamente.');
        }

        if (!$googleUser || !$googleUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Não foi possível obter os dados da conta Google.');
        }

        // Localiza usuário por google_id ou e-mail
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar() ?? ($user->avatar ?? null),
            ]);
        } else {
            $user = User::create([
                'nome'      => $googleUser->getName() ?? 'Usuário Google',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'senha'     => null,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('qr.index'))->with('success_toast', 'Login com Google realizado com sucesso!');
    }
}
