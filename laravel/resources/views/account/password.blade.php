@extends('adminlte::page')

@section('title', 'Redefinir senha')

@section('content_header')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Redefinir senha</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('account.edit') }}">Conta</a></li>
                <li class="breadcrumb-item active">Redefinir senha</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <form method="POST" action="{{ route('account.password.update') }}" class="needs-validation" novalidate="">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="password_current">Senha atual</label>
                    <input type="password" name="password_current" id="password_current" class="form-control @error('password_current') is-invalid @enderror" placeholder="Senha atual" required="">
                    @error('password_current') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="password_new">Nova senha</label>
                    <input type="password" name="password_new" id="password_new" class="form-control @error('password_new') is-invalid @enderror" placeholder="Nova senha" required="">
                    @error('password_new') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="password_confirm">Confirmar nova senha</label>
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control @error('password_confirm') is-invalid @enderror" placeholder="Confirme a nova senha" required="">
                    @error('password_confirm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="text-right card-footer">
            <button class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
@stop
