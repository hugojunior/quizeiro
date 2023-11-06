@extends('adminlte::page')

@section('title', 'Importar Quiz')

@section('content_header')
<div class="container-fluid">
	<div class="mb-2 row">
		<div class="col-sm-6">
			<h1>Importar Quiz</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Meus Quizzes</a></li>
				<li class="breadcrumb-item active">Importar Quiz</li>
			</ol>
		</div>
	</div>
</div>
@stop

@section('content')
<form method="POST" action="{{ route('quizzes.import') }}">
	@csrf
	<div class="card">
		<div class="card-header">
			Instruções
		</div>
		<div class="card-body">
			<p>Essa página tem como finalidade ajudar com a importação de perguntas e respostas via <a href="https://chat.openai.com/" target="_blank">ChatGPT</a> ou qualquer outra ferramenta que gere um arquivo JSON com <strong><a href="/js/exemplo-perguntas.json" target="_blank">esse formato</a>.</strong></p>
            <h5>Utilizando o ChatGPT</h5>
            <p>Primeiramente, crie uma conta gratuita na plataforma e, <a href="https://chat.openai.com/" target="_blank" class="text-info"><strong>no chat</strong></a>, utilize o seguinte prompt, substituindo <strong>*|ASSUNTO|*</strong> pelo tópico que você deseja abordar em seu Quiz:</p>
            <blockquote>
                <p class="font-italic">Gere uma API JSON com 10 perguntas sobre '<strong>*|ASSUNTO|*</strong>' para um Quiz, onde cada pergunta deve ter 4 respostas e apenas a primeira opção deve ser correta. As perguntas e respostas devem ter no máximo 100 caracteres cada e o JSON deve seguir o seguinte formato: '{"title": string,"slug": string,"summary": string,"questions": [{"question": string,"answers": array}]}'</p>
            </blockquote>

            <div class="row">
                <div class="form-group col-md-12 col-12">
                    <textarea name="gptJson" id="gptJson" cols="30" rows="10" class="form-control @error('gptJson') is-invalid @enderror" placeholder="Cole aqui o seu JSON">{{ old('gptJson', Session::get('gptJson')) }}</textarea>
                    @error('gptJson') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
		</div>
	</div>

	<div class="clearfix">
		<button type="submit" class="btn btn-primary float-right">Importar</button>
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
