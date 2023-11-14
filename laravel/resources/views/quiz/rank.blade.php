<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="index,follow" />
	<meta name="description" content="{{ $quiz->description }}" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@hugojuniior" />
	<meta name="twitter:creator" content="@hugojuniior" />
	<meta property="og:title" content="Quizeiro - {{ $quiz->title }} - Placar" />
	<meta property="og:description" content="{{ $quiz->description }}" />
	<meta property="og:url" content="https://quizeiro.games/" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://quizeiro.games/images/banner-social.png">
	<meta name="keywords" content="quiz,quizeiro,quizdev,quiz online,gratis,personalizado,aprender,estudar,jogar,projeto,uni7,faculdade" />
	<meta name="author" content="Hugo Júnior" />
	<title>Quizeiro - {{ $quiz->title }} - Placar</title>
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
	<link rel="stylesheet" href="/css/styles.css">
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-ERL5WHWKZ8"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'G-ERL5WHWKZ8');
    </script>
</head>
<body>
	<div id="container" class="rank">
        <h1 class="share-title">Placar</h1>
        <h2>{{ $quiz->title }}</h2>
        @if($quiz->date_end->timestamp > now()->timestamp)
            <h4><ion-icon name="time-outline"></ion-icon> Este quiz termina em <span id="countdown"></span></h4>
        @else
            <h4><ion-icon name="time-outline"></ion-icon> Este quiz terminou em <span>{{ $quiz->date_end->format('d/m/Y \à\s H:i') }}</span></h4>
        @endif
        <ol class="list-score" id="listScore">
            @foreach($quiz_users as $quiz_user)
                @if($loop->first && $quiz->date_end->timestamp < now()->timestamp)
                    <li>{{ $quiz_user->score }} <div>{{ $quiz_user->name }}</div><ion-icon name="trophy-outline"></ion-icon></li>
                @else
                    <li>{{ $quiz_user->score }} <div>{{ $quiz_user->name }}</div></li>
                @endif
            @endforeach
        </ol>
        <hr>
        <div class="creditos">
            Criado por <a href="http://www.hugojunior.com" target="_blank">Hugo Júnior</a> como projeto de Estágio
            II do curso de SI da <a href="https://www.uni7.edu.br/" target="_blank">UNI7</a>.<br>
            <a href="https://github.com/hugojunior/quizeiro" target="_blank" class="link-github"><ion-icon
                    name="logo-github"></ion-icon></a>
        </div>
    </div>
    @if($quiz->date_end->timestamp > now()->timestamp)
        <div class="qr-code">
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(500)->generate(route('quizzes.share', [$user->username, $quiz->slug]))) !!} ">
        </div>
        <script>
            var x = setInterval(function() {
                var distance = ({{ $quiz->date_end->timestamp }}*1000) - new Date().getTime();
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                if (distance < 1) {
                    clearInterval(x);
                    window.location.reload();
                } else {
                    document.getElementById("countdown").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                }
            }, 1000);
            function atualizarListaComJSON() {
                fetch("/quizzes/scores/{{ $quiz->id }}", {
                    method: "GET",
                            headers: {
                                "Content-type": "application/json; charset=UTF-8",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                    })
                    .then(response => response.json())
                    .then(data => {
                        const lista = document.getElementById('listScore');
                        lista.innerHTML = '';
                        data.forEach(item => {
                            const novoItem = document.createElement('li');
                            novoItem.innerHTML = item.score + '  <div>' + item.name + '</div>';
                            lista.appendChild(novoItem);
                        });
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                    });
            }
            setInterval(() => {
                atualizarListaComJSON();
            }, 10000);
        </script>
    @endif
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
