@extends('adminlte::page')

@section('title', 'Alterar dados')

@section('content_header')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Alterar dados</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('account.edit') }}">Conta</a></li>
                <li class="breadcrumb-item active">Alterar dados</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <form method="POST" action="{{ route('account.update') }}" class="needs-validation" novalidate="">
        @csrf
        @method('PATCH')
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required="">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="username">Nome de usuário</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', Auth::user()->username) }}" required="">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required="">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="text-right card-footer">
            <button class="btn btn-primary">Salvar</button>
            <hr>
            <div class="text-center">
                <a href="{{ route('account.delete') }}" class="text-sm text-center text-danger">Quero excluir minha conta</a>
            </div>
        </div>
    </form>
</div>
@stop
