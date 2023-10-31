@extends('adminlte::page')

@section('title', 'Meus Quizzes')

@section('content_header')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Meus Quizzes</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary pt-1 mr-1"><i class="fas fa-plus-circle"></i> Criar novo</a>
                <a href="{{ route('quizzes.import') }}" class="btn btn-outline-primary pt-1"><i class="fas fa-upload"></i> Importar</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="p-0 card-body table-responsive">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Acessos</th>
                    <th>Respostas</th>
                    <th>Criação</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                <tr>
                    <td>{{ $quiz->id }}</td>
                    <td>{{ Str::limit($quiz->title, 30) }}</td>
                    <td>{{ $quiz->quiz_access_count }}</td>
                    <td>{{ $quiz->quiz_user_count }}</td>
                    <td>{{ $quiz->created_at->format('d/m/Y H:s') }}</td>
                    <td>
                        <a href="{{ route('quizzes.share', [Auth::user()->username, $quiz->slug]) }}" class="btn btn-sm btn-success" title="Ver online" target="_blank"><i class="fas fa-link"></i></a>
                        <a href="{{ route('quizzes.report', $quiz->id) }}" class="btn btn-sm btn-info" title="Relatórios"><i class="fas fa-chart-pie"></i></a>
                        <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-sm btn-primary" title="Editar"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('quizzes.delete', $quiz->id) }}" class="btn btn-sm btn-danger" title="Excluir"><i class="fas fa-times-circle"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
