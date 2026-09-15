@extends('layouts.app')

@section('title', 'Criar Conta - QR Studio')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-3">
        <div class="col-md-6 col-lg-5">
            <div class="card-custom p-4 p-md-5 shadow-lg">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                        <i class="fa-solid fa-user-plus fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">Criar Nova Conta</h3>
                    <p class="text-secondary small">Comece a gerar QR Codes dinâmicos com métricas em tempo real</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small border-0 mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Botão de Cadastro com Google -->
                <div class="mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-light w-100 py-2.5 rounded-pill fw-semibold d-flex align-items-center justify-content-center gap-2 border-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.7l3.1-3.1C17.3 1.8 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.3 8.9 5 12 5z"/>
                            <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.6h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.9z"/>
                            <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.2-1.6.4-2.3L1.9 7.3C.7 9.7 0 12.3 0 15.1s.7 5.4 1.9 7.8l3.7-2.9z"/>
                            <path fill="#34A853" d="M12 23.5c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3.1 0-5.7-2.1-6.6-5L1.7 16.7C3.5 20.4 7.4 23.5 12 23.5z"/>
                        </svg>
                        <span>Cadastrar com o Google</span>
                    </a>
                </div>

                <div class="d-flex align-items-center my-4">
                    <hr class="flex-grow-1 border-secondary border-opacity-25 m-0">
                    <span class="px-3 text-secondary small text-uppercase" style="font-size: 0.75rem;">ou com e-mail</span>
                    <hr class="flex-grow-1 border-secondary border-opacity-25 m-0">
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold">Nome Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-secondary text-secondary"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="nome" class="form-control bg-body border-secondary text-body" placeholder="Seu nome" value="{{ old('nome') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold">E-mail</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-secondary text-secondary"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-body border-secondary text-body" placeholder="seu@email.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-body small fw-semibold">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-secondary text-secondary"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="senha" class="form-control bg-body border-secondary text-body" placeholder="Mínimo 8 caracteres (A-z, 0-9, @#$)" required>
                        </div>
                        <small class="text-secondary opacity-75" style="font-size: 0.75rem;">
                            Deve conter ao menos 8 caracteres, letra maiúscula, número e caractere especial.
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-body small fw-semibold">Confirmar Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-secondary text-secondary"><i class="fa-solid fa-shield-halved"></i></span>
                            <input type="password" name="senha_confirmation" class="form-control bg-body border-secondary text-body" placeholder="Repita a senha" required>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-gradient-primary w-100 py-2 rounded-pill fw-bold">
                        <i class="fa-solid fa-check me-2"></i> Criar Minha Conta
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <p class="text-secondary small mb-0">
                        Já tem uma conta? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Fazer Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
