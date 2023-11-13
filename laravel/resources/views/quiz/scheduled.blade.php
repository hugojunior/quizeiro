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
	<meta property="og:title" content="Quizeiro - {{ $quiz->title }}" />
	<meta property="og:description" content="{{ $quiz->description }}" />
	<meta property="og:url" content="https://quizeiro.games/" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://quizeiro.games/images/banner-social.png">
	<meta name="keywords" content="quiz,quizeiro,quizdev,quiz online,gratis,personalizado,aprender,estudar,jogar,projeto,uni7,faculdade" />
	<meta name="author" content="Hugo Júnior" />
	<title>Quizeiro - {{ $quiz->title }}</title>
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
	<div id="container">
        <div class="scheduled">
            <ion-icon name="time-outline" class="ic"></ion-icon>
            <h2>Este quiz está agendado para:</h2>
            <h3>{{ $quiz->date_start->format('d/m/Y \à\s H:i') }}</h3>
        </div>
		<h1 class="share-title">{{ $quiz->title }}</h1>
        <div class="author"><ion-icon name="person-outline"></ion-icon> Criado por <a href="{{ route('quizzes.profile', $quiz->user->username) }}">{{ $quiz->user->name }}</a></div>
		<div class="text-desc">{!! nl2br($quiz->description) !!}</div>
		<h3>Compartilhe:</h3>
		<div class="share">
			<a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"
				target="_blank"><ion-icon name="logo-facebook"></ion-icon></a>
			<a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&text={{ urlencode($quiz->title) }}"
				target="_blank"><ion-icon name="logo-twitter"></ion-icon></a>
			<a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(Request::fullUrl()) }}&title={{ urlencode($quiz->title) }}&summary={{ urlencode($quiz->description) }}&source={{ urlencode(Request::fullUrl()) }}"
				target="_blank"><ion-icon name="logo-linkedin"></ion-icon></a>
			<a href="https://api.whatsapp.com/send?text={{ urlencode($quiz->title) }} {{ urlencode(Request::fullUrl()) }}"
				target="_blank"><ion-icon name="logo-whatsapp"></ion-icon></a>
			<hr>
			<div class="creditos">
				Criado por <a href="http://www.hugojunior.com" target="_blank">Hugo Júnior</a> como projeto de Estágio
				II do curso de SI da <a href="https://www.uni7.edu.br/" target="_blank">UNI7</a>.<br>
				<a href="https://github.com/hugojunior/quizeiro" target="_blank" class="link-github"><ion-icon
						name="logo-github"></ion-icon></a>
			</div>
		</div>
		<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
		<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
