@extends('adminlte::page')

@section('title', 'Relatório do Quiz')

@section('content_header')
<div class="container-fluid">
    <div class="mb-2 row">
        <div class="col-sm-6">
            <h1>{{ Str::limit($quiz->title, 30) }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Meus Quizzes</a></li>
                <li class="breadcrumb-item active">Relatório do Quiz</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info" style="background-color: #492356 !important;">
                <div class="inner">
                    <h3>{{ $totalVisits }}</h3>
                    <p>Visitas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger" style="background-color: #70477D !important;">
                <div class="inner">
                    <h3>{{ $totalUniqueVisits }}</h3>
                    <p>Visitas Únicas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple" style="background-color: #9A6EA7 !important;">
                <div class="inner">
                    <h3>{{ $totalAnswers }}</h3>
                    <p>Respostas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success" style="background-color: #C597D2 !important;">
                <div class="inner">
                    <h3>{{ $successRate }}<sup style="font-size: 20px">%</sup></h3>
                    <p>Taxa de Sucesso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-info-circle"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Visitantes e Respostas por Data</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="visitors-chart" height="200"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary" style="color: #70477D !important;"></i> Visitantes
                        </span>
                        <span>
                            <i class="fas fa-square text-gray" style="color: #cccccc !important;"></i> Respostas
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Respostas do Quiz</h3>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover text-nowrap table-valign-middle" id="usersTable">
                        <thead>
                            <tr>
                                <th>Posição</th>
                                <th>Usuário</th>
                                <th>Pontuação</th>
                                <th>Questões</th>
                                <th>Tipo</th>
                                <th>Data</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($answers as $k=>$answer)
                            <tr>
                                <td><strong>{{ $answer->score ? "#". $k+1 : '-' }}</strong></td>
                                <td>{{ $answer->name }}</td>
                                <td><strong>{{ $answer->score }}</strong></td>
                                <td>{{ collect($answer->questions)->where(function($question) { return $question['answer'] == $question['correct']; })->count() }}/{{ collect($answer->questions)->count() }}</td>
                                <td>
                                    @if($answer->end_type == 'success')
                                        <span class="badge badge-success">Finalizou</span>
                                    @elseif($answer->end_type == 'gameover[Seu tempo esgotou]')
                                        <span class="badge badge-danger">Game Over (tempo)</span>
                                    @elseif($answer->end_type == 'gameover[Acabaram suas vidas]')
                                        <span class="badge badge-danger">Game Over (Vidas)</span>
                                    @endif
                                </td>
                                <td data-sort="{{ $answer->created_at }}">{{ $answer->created_at->format('d/m/Y H:i') }}</td>
                                <td><a href="{{ route('quizzes.report.answer', [$quiz->id, $answer->id]) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Detalhes</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visitantes por Dispositivos</h3>
                </div>
                <div class="card-body">
                    <canvas id="devicesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visitantes por Browser</h3>
                </div>
                <div class="card-body">
                    <canvas id="browsersChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visitantes por Sistema Operacional</h3>
                </div>
                <div class="card-body">
                    <canvas id="oSChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visitantes por Hora do dia</h3>
                </div>
                <div class="card-body table-responsive">
                    <div class="chart">
                        <canvas id="hourChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Referências de acesso</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Endereço</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($refererAccess as $referer)
                            <tr>
                                <td><a href="{{ $referer->referrer }}" target="_blank">{{ $referer->referrer }}</a></td>
                                <td><strong>{{ $referer->total }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/css/responsive.bootstrap4.min.css">
@stop

@section('js')
<script src="/js/Chart.min.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/dataTables.bootstrap4.min.js"></script>
<script src="/js/dataTables.responsive.min.js"></script>
<script src="/js/responsive.bootstrap4.min.js"></script>
<script>
$(function() {
    'use strict'

    var colors = ['#492356', '#70477D', '#9A6EA7', '#C597D2', '#F2C2FF', '#FFE8FF', '#FFF7FF'];

    var visitorsChart = new Chart($('#visitors-chart'), {
        data: {
            labels: {!! json_encode($dataGraphDates) !!},
            datasets: [{
                type: 'line',
                data: {!! json_encode($dataGraphVisits) !!},
                backgroundColor: 'transparent',
                borderColor: '#70477D',
                pointBorderColor: '#70477D',
                pointBackgroundColor: '#70477D',
                fill: false
            }, {
                type: 'line',
                data: {!! json_encode($dataGraphAnswers) !!},
                backgroundColor: 'tansparent',
                borderColor: '#cccccc',
                pointBorderColor: '#cccccc',
                pointBackgroundColor: '#cccccc',
                fill: false
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: 'index',
                intersect: true
            },
            hover: {
                mode: 'index',
                intersect: true
            },
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: true,
                        lineWidth: '4px',
                        color: 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    }
                }],
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        fontColor: '#495057',
                        fontStyle: 'bold'
                    }
                }]
            }
        }
    });

    var options = {
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                boxWidth: 20,
                fontSize: 12,
                padding: 10
            }
        },
        maintainAspectRatio : false,
        responsive : true
    };

    new Chart($('#devicesChart').get(0).getContext('2d'), {
      type: 'pie',
      data: {
        labels: {!! $devices->keys()->toJson() !!},
        datasets: [{
            data: {!! $devices->values()->toJson() !!},
            backgroundColor: colors.slice(0, {!! $devices->count() !!}),
        }]
      },
      options: options
    });

    new Chart($('#browsersChart').get(0).getContext('2d'), {
      type: 'pie',
      data: {
        labels: {!! $browsers->keys()->toJson() !!},
        datasets: [{
            data: {!! $browsers->values()->toJson() !!},
            backgroundColor: colors.slice(0, {!! $browsers->count() !!}),
        }]
      },
      options: options
    });

    new Chart($('#oSChart').get(0).getContext('2d'), {
      type: 'pie',
      data: {
        labels: {!! $os->keys()->toJson() !!},
        datasets: [{
            data: {!! $os->values()->toJson() !!},
            backgroundColor: colors.slice(0, {!! $os->count() !!}),
        }]
      },
      options: options
    });

    new Chart($('#hourChart').get(0).getContext('2d'), {
      type: 'bar',
      data:{
        labels: {!! $dataGraphHours->keys()->toJson() !!},
        datasets: [{
            label: 'Visitantes ',
            data: {!! $dataGraphHours->values()->toJson() !!},
            backgroundColor: '#70477D',
            borderColor: '#70477D',
            pointRadius: false,
            pointColor: '#000000',
            pointStrokeColor: '#70477D',
            pointHighlightFill: '#fff',
            pointHighlightStroke: '#70477D'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }
    });

    $('#usersTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "order": [[ 2, "desc" ]],
      "language": {
        url: '/js/datatables-pt-BR.json'
      },
      "columnDefs": [
        {
            "orderable": false,
            "targets": 6
        }
      ],
    });

});
</script>
@stop
