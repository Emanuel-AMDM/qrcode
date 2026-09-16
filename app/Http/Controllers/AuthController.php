<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Exibe a página de login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('qr.index');
        }
        return view('auth.login');
    }

    /**
     * Autentica o usuário existente.
     */
    public function login(Request $requisicao)
    {
        $dadosValidados = $requisicao->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'senha.required' => 'O campo senha é obrigatório.',
        ]);

        $lembrar = $requisicao->boolean('lembrar');

        if (Auth::attempt(['email' => $dadosValidados['email'], 'password' => $dadosValidados['senha']], $lembrar)) {
            $requisicao->session()->regenerate();
            return redirect()->intended(route('qr.index'))->with('success_toast', 'Bem-vindo de volta!');
        }

        return back()->withErrors([
            'email' => 'E-mail ou senha incorretos.',
        ])->onlyInput('email');
    }

    /**
     * Exibe a página de cadastro de novo usuário.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('qr.index');
        }
        return view('auth.register');
    }

    /**
     * Cadastra um novo usuário no sistema.
     */
    public function register(Request $requisicao)
    {
        $requisicao->validate([
            'nome'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'senha' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'nome.required'    => 'O nome é obrigatório.',
            'email.required'   => 'O e-mail é obrigatório.',
            'email.email'      => 'Informe um e-mail válido.',
            'email.unique'     => 'Este e-mail já está cadastrado.',
            'senha.required'   => 'A senha é obrigatória.',
            'senha.confirmed'  => 'As senhas não coincidem.',
            'senha.min'        => 'A senha deve ter pelo menos 8 caracteres.',
        ]);

        $usuario = User::create([
            'nome'  => $requisicao->nome,
            'email' => $requisicao->email,
            'senha' => Hash::make($requisicao->senha),
        ]);

        Auth::login($usuario);
        $requisicao->session()->regenerate();

        return redirect()->route('qr.index')->with('success_toast', 'Conta criada com sucesso!');
    }

    /**
     * Encerra a sessão do usuário.
     */
    public function logout(Request $requisicao)
    {
        Auth::logout();
        $requisicao->session()->invalidate();
        $requisicao->session()->regenerateToken();

        return redirect()->route('landing')->with('success_toast', 'Você saiu da sua conta.');
    }

    /**
     * Redireciona para o fluxo de autenticação do Google.
     */
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    /**
     * Processa a resposta do login/cadastro via Google.
     */
    public function handleGoogleCallback(Request $requisicao)
    {
        if ($requisicao->has('error')) {
            \Illuminate\Support\Facades\Log::warning('Login com Google cancelado ou com erro: ' . $requisicao->get('error'));
            return redirect()->route('login')->with('error', 'A autenticação com o Google foi cancelada.');
        }

        try {
            try {
                $usuarioGoogle = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
            } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
                // Fallback resiliente caso o cookie de sessão seja afetado no retorno cross-site
                $usuarioGoogle = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->user();
            }
        } catch (\Exception $excecao) {
            \Illuminate\Support\Facades\Log::error('Erro ao obter usuário do Google OAuth: ' . $excecao->getMessage(), [
                'trace' => $excecao->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Falha ao autenticar com o Google. Verifique suas credenciais e tente novamente.');
        }

        if (!$usuarioGoogle || !$usuarioGoogle->getEmail()) {
            return redirect()->route('login')->with('error', 'Não foi possível obter os dados da conta Google.');
        }

        // Procura usuário pelo google_id ou pelo e-mail
        $usuario = User::where('google_id', $usuarioGoogle->getId())
            ->orWhere('email', $usuarioGoogle->getEmail())
            ->first();

        if ($usuario) {
            $usuario->update([
                'google_id' => $usuarioGoogle->getId(),
                'avatar'    => $usuarioGoogle->getAvatar() ?? $usuario->avatar,
            ]);
        } else {
            $usuario = User::create([
                'nome'      => $usuarioGoogle->getName() ?? 'Usuário Google',
                'email'     => $usuarioGoogle->getEmail(),
                'google_id' => $usuarioGoogle->getId(),
                'avatar'    => $usuarioGoogle->getAvatar(),
                'senha'     => null,
            ]);
        }

        Auth::login($usuario, true);
        $requisicao->session()->regenerate();

        return redirect()->intended(route('qr.index'))->with('success_toast', 'Login com Google realizado com sucesso!');
    }
}

