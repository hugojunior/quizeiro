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
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>150</h3>
                    <p>Visitas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>53</h3>
                    <p>Visitas Únicas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>44</h3>
                    <p>Respostas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>
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
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Visitantes</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="visitors-chart" height="200"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> Visitantes
                        </span>
                        <span>
                            <i class="fas fa-square text-gray"></i> Visitantes únicos
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 col-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Melhores pontuações</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Nome</th>
                                <th>Pontuação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>01/01/2023</td>
                                    <td>Nome do usuário</td>
                                    <td>10000000</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-6">
            <div class="card">
                <div class="card-header border-0">
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
                            @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>http://www.google.com.br</td>
                                    <td>20</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="/js/Chart.min.js"></script>
<script>
$(function() {
    'use strict'
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }
    var mode = 'index'
    var intersect = true
    var $visitorsChart = $('#visitors-chart')
    var visitorsChart = new Chart($visitorsChart, {
        data: {
            labels: ['01', '05', '10', '15', '20', '25', '30'],
            datasets: [{
                type: 'line',
                data: [100, 120, 170, 167, 180, 177, 160],
                backgroundColor: 'transparent',
                borderColor: '#007bff',
                pointBorderColor: '#007bff',
                pointBackgroundColor: '#007bff',
                fill: false
            }, {
                type: 'line',
                data: [60, 80, 70, 67, 80, 77, 100],
                backgroundColor: 'tansparent',
                borderColor: '#ced4da',
                pointBorderColor: '#ced4da',
                pointBackgroundColor: '#ced4da',
                fill: false
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                mode: mode,
                intersect: intersect
            },
            hover: {
                mode: mode,
                intersect: intersect
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
                    },
                    ticks: $.extend({
                        beginAtZero: true,
                        suggestedMax: 200
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            }
        }
    })
});
</script>
@stop
