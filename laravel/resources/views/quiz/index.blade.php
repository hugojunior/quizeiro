@extends('adminlte::page')

@section('title', 'Quizzes - Listagem')

@section('content_header')
    <h1>Meus Quizzes</h1>
@stop

@section('content')
<div class="card">
    <div class="p-0 card-body table-responsive">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Perguntas</th>
                    <th>Respostas</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 10; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td>Lorem ipsum dolor sit amet consectetur.</td>
                    <td>{{ rand(15, 20) }}</td>
                    <td>{{ rand(0, 200) }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-success" title="Ver online"><i class="fas fa-link"></i></a>
                        <a href="#" class="btn btn-sm btn-info" title="Relatórios"><i class="fas fa-chart-pie"></i></a>
                        <a href="#" class="btn btn-sm btn-primary" title="Editar"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-sm btn-danger" title="Excluir"><i class="fas fa-times-circle"></i></a>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@stop
