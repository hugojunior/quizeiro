<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="index,follow" />
	<meta name="description" content="Perfil de {{ $user->name }} em quizeiro.games!" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@hugojuniior" />
	<meta name="twitter:creator" content="@hugojuniior" />
	<meta property="og:title" content="Quizeiro - {{ $user->name }}" />
	<meta property="og:description" content="Perfil de {{ $user->name }} em quizeiro.games!" />
	<meta property="og:url" content="https://quizeiro.games/{{ $user->username }}" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="https://quizeiro.games/images/banner-social.png">
	<meta name="keywords" content="quiz,quizeiro,quizdev,quiz online,gratis,personalizado,aprender,estudar,jogar,projeto,uni7,faculdade" />
	<meta name="author" content="Hugo Júnior" />
	<title>Quizeiro - {{ $user->name }}</title>
	<link rel="stylesheet" href="/css/styles.css">
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
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
	<div id="container" class="profile">
        <img src="{{ Gravatar::get($user->email, ['size'=>200]) }}" alt="{{ $user->name }} em Gravatar">
		<h1 class="share-title">{{ $user->name }} <span>({{ $user->username }})</span></h1>
        <h3>Meus Quizzes</h3>
        <hr>
        @forelse($quizzes as $quiz)
            <div><ion-icon name="calendar-outline"></ion-icon> {{ $quiz->created_at->format('d/m/Y') }} - <a href="{{ route('quizzes.share', [$user->username, $quiz->slug]) }}">{{ $quiz->title }}</a></div>
        @empty
            <p style="text-align: center;margin: 40px 0;">O usuário ainda não possuí quizzes públicos</p>
        @endforelse
        <hr>
        <div class="creditos">
            Criado por <a href="http://www.hugojunior.com" target="_blank">Hugo Júnior</a> como projeto de Estágio
            II do curso de SI da <a href="https://www.uni7.edu.br/" target="_blank">UNI7</a>.<br>
            <a href="https://github.com/hugojunior/quizeiro" target="_blank" class="link-github"><ion-icon
                    name="logo-github"></ion-icon></a>
        </div>
		<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
		<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
