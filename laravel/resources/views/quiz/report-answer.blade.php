@extends('adminlte::page')

@section('title', 'Relatório do Quiz')

@section('content_header')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Relatório de Resposta</h1>
        </div>
        <div class="col-sm-6">
            <form method="POST" action="{{ route('quizzes.report.answer.destroy', [$quiz->id, $answer->id]) }}" style="dispĺay:none" id="formDelete">
                @csrf
                @method('DELETE')
            </form>
            <div class="float-right">
                <a href="#" class="btn btn-danger pt-1 mr-1" id="deleteAnswer"><i class="fas fa-ban"></i> Excluir</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Dados da Resposta
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <tbody>
                                <tr>
                                    <td><strong>Quiz</strong></td>
                                    <td>{{ $quiz->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nome</strong></td>
                                    <td>{{ $answer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Posição no ranking</strong></td>
                                    <td><strong>{{ $position }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Pontuação</strong></td>
                                    <td><strong>{{ $answer->score }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Questões respondidas</strong></td>
                                    <td>{{ collect($answer->questions)->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Questões corretas</strong></td>
                                    <td>{{ collect($answer->questions)->where(function($question) { return $question['answer'] == $question['correct']; })->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo de fim</strong></td>
                                    <td>
                                        @if($answer->end_type == 'success')
                                            <span class="badge badge-success">Finalizou</span>
                                        @elseif($answer->end_type == 'gameover[Seu tempo esgotou]')
                                            <span class="badge badge-danger">Game Over (tempo)</span>
                                        @elseif($answer->end_type == 'gameover[Acabaram suas vidas]')
                                            <span class="badge badge-danger">Game Over (Vidas)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Vidas restantes</strong></td>
                                    <td>{{ $answer->life_left }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tempo restante</strong></td>
                                    <td>{{ $answer->time_left }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tempo de resposta</strong></td>
                                    <td>{{ $answer->time_end->diff($answer->time_start)->format('%I:%S') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Data do registro</strong></td>
                                    <td>{{ $answer->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cenas visualizadas</strong></td>
                                    <td>{{ collect($answer->overlay_views)->join(', ') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Dados da Plataforma
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-valign-middle">
                            <tbody>
                                <tr>
                                    <td><strong>Idioma</strong></td>
                                    <td>{{ $answer->client['language'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Resolução</strong></td>
                                    <td>{{ $answer->client['screen']['width'] }}x{{ $answer->client['screen']['height'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Plataforma</strong></td>
                                    <td>{{ $answer->client['platform'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Vendor</strong></td>
                                    <td>{{ $answer->client['vendor'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>User Agent</strong></td>
                                    <td>{{ $answer->client['userAgent'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Dados das Perguntas
            </div>
            <div class="card-body">
                <ol>
                    @foreach($answer->questions as $question)
                        <li><strong>{{ $question['question'] }}</strong>
                            @if($question['answer'] == $question['correct'])
                                <span class="badge badge-success">acertou</span>
                            @elseif($question['answer'] != $question['correct'])
                                <span class="badge badge-danger">errou</span>
                            @endif
                            <ul>
                                @foreach($question['answers'] as $answerQuestion)
                                    <li>
                                        @if($answerQuestion == $question['correct'])
                                            <span class="text-success">{{ $answerQuestion }}</span>
                                        @elseif($answerQuestion != $question['correct'] && $answerQuestion == $question['answer'])
                                            <span class="text-danger">{{ $answerQuestion }}</span>
                                        @else
                                            {{ $answerQuestion }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul><br>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
     $('#deleteAnswer').click(function(e){
        e.preventDefault();
        confirm('Tem certeza que deseja excluir essa resposta?') ? $('#formDelete').submit() : '';
     });
</script>
@stop
