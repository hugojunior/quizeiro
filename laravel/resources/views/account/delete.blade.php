@extends('adminlte::page')

@section('title', 'Encerrar conta')

@section('content_header')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Encerrar conta</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('account.edit') }}">Conta</a></li>
                <li class="breadcrumb-item active">Encerrar conta</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <form method="POST" action="{{ route('account.destroy') }}" class="needs-validation" novalidate="">
        @csrf
        @method('DELETE')
        <div class="card-body">
            <div class="alert alert-danger alert-has-icon">
                <div class="alert-body">
                    <div class="alert-title"><strong>Leia antes de continuar</strong></div>
                    <p>Ao encerrar sua conta você terá todos os dados associado a sua conta apagado dos nossos servidores, e não poderá:</p>
                    <ul>
                        <li>Fazer login na plataforma;</li>
                        <li>Recuperar informações associada a sua conta;</li>
                        <li>Acessar ou recuperar informações de quizzes criados;</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm" id="confirm" required>
                        <label class="form-check-label" for="confirm">
                            Estou ciente que essa ação é irreversível e quero continuar.
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="password">Digite sua senha</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Digite sua senha" required="">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="text-right card-footer">
            <button class="btn btn-primary">Encerre minha conta</button>
        </div>
    </form>
</div>
@stop
