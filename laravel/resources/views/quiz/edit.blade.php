@extends('adminlte::page')

@section('title', 'Editar Quiz')

@section('content_header')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>Editar Quiz</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Meus Quizzes</a></li>
                <li class="breadcrumb-item active">Editar Quiz</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<form method="POST" action="{{ route('quizzes.update', $quiz->id) }}">
    @method('patch')
    @csrf
    <div class="card">
        <div class="card-header">
            Informações básicas
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-6 col-6">
                    <label for="title">Titulo</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $quiz->title) }}" maxlength="100" required="">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6 col-6">
                    <label for="slug">Identificador</label>
                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $quiz->slug) }}" maxlength="50" required>
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="description">Descrição</label>
                    <textarea name="description" id="description" cols="30" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $quiz->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Publicação e agendamento
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <label for="period">Período de publicação:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right @error('period') is-invalid @enderror" name="period" id="period" value="{{ old('period', ($quiz->date_start->format('d/m/Y H:i') . ' - ' . $quiz->date_end->format('d/m/Y H:i'))) }}">
                        @error('period') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="is_public" id="is_public" @if(old('is_public', $quiz->is_public)) checked @endif>
                        <label class="custom-control-label" for="is_public">Divulgar na página inicial</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Perguntas
        </div>
        <div class="card-body">
            <div id="accordion">
                @for($i = 1; $i <= 10; $i++)
                    <div class="card card-gray">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapse{{ $i }}" aria-expanded="false">
                                    Pergunta #{{ $i }}
                                </a>
                            </h4>
                        </div>
                        <div id="collapse{{ $i }}" class="collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label for="question-{{ $i }}">Pergunta</label>
                                        <input type="text" name="question-{{ $i }}" id="question-{{ $i }}" class="form-control @error('question-' . $i) is-invalid @enderror" value="{{ old('question-' . $i, $quiz->questions[$i-1]['question']) }}" maxlength="100">
                                        @error('question-' . $i) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                @for($a = 1; $a <= 4; $a++)
                                    <div class="row">
                                        @if($a == 1)
                                            <div class="form-group col-md-12 col-12">
                                                <label class="col-form-label" for="answers-{{ $i }}-{{ $a }}">Resposta correta</label>
                                                    <input type="text" class="form-control is-valid" name="answers-{{ $i }}-{{ $a }}" id="answers-{{ $i }}-{{ $a }}" value="{{ old('answers-' . $i . '-' . $a, $quiz->questions[$i-1]['answers'][$a-1]) }}" maxlength="100">
                                            </div>
                                        @else
                                            <div class="form-group col-md-12 col-12">
                                                @if($a == 2)
                                                    <label class="col-form-label">Respostas incorretas</label>
                                                @endif
                                                    <input type="text" class="form-control is-invalid" name="answers-{{ $i }}-{{ $a }}" id="answers-{{ $i }}-{{ $a }}" value="{{ old('answers-' . $i . '-' . $a, $quiz->questions[$i-1]['answers'][$a-1]) }}" maxlength="100">
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div class="clearfix">
        <button type="submit" class="btn btn-primary float-right">Salvar</button>
    </div>
    <br>
</form>
@stop

@section('css')
<link rel="stylesheet" href="/css/daterangepicker.css">
@stop

@section('js')
<script src="/js/moment.min.js"></script>
<script src="/js/daterangepicker.js"></script>
<script src="/js/jquery.inputmask.min.js"></script>
<script>
    $('#period').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        locale: {
            format: 'DD/MM/YYYY HH:mm'
        }
    });
    $('#slug').inputmask({
        'regex': '[0-9a-z-]*'
    });
</script>
@stop
