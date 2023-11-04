<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow" />
    <meta name="description" content="Quizeiro é uma plataforma gratuita que permite que os usuários criem seus próprios quizzes personalizados e os compartilhem com amigos." />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@hugojuniior" />
    <meta name="twitter:creator" content="@hugojuniior" />
    <meta property="og:title" content="Quizeiro - Crie Quizzes Personalizados Gratuitamente" />
    <meta property="og:description" content="Quizeiro é uma plataforma gratuita que permite que os usuários criem seus próprios quizzes personalizados e os compartilhem com amigos." />
    <meta property="og:url" content="https://quizeiro.games/" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://quizeiro.games/images/banner-social.png">
    <meta name="keywords" content="quiz,quizeiro,quizdev,quiz online,gratis,personalizado,aprender,estudar,jogar,projeto,uni7,faculdade" />
    <meta name="author" content="Hugo Júnior" />
    <title>Quizeiro - Crie Quizzes Personalizados Gratuitamente</title>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ERL5WHWKZ8"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-ERL5WHWKZ8');
    </script>
    <style>
        #home {
            background-image: url('/images/bg-banner-home.png');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        h1 {
            color: #292737;
            text-shadow: 1px 1px 1px #fff;
        }
        footer.bg-dark {
            background-color: #292737 !important;
        }
        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: #423056;
            --bs-btn-border-color: #423056;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #423056;
            --bs-btn-hover-border-color: #2b2337;
            --bs-btn-focus-shadow-rgb: 49,132,253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #2b2337;
            --bs-btn-active-border-color: #25212c;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #423056;
            --bs-btn-disabled-border-color: #423056;
        }
        .btn-outline-primary {
            --bs-btn-color: #423056;
            --bs-btn-border-color: #423056;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #423056;
            --bs-btn-hover-border-color: #423056;
            --bs-btn-focus-shadow-rgb: 13,110,253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #423056;
            --bs-btn-active-border-color: #423056;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #423056;
            --bs-btn-disabled-bg: transparent;
            --bs-btn-disabled-border-color: #423056;
            --bs-gradient: none;
        }
        #como-funciona img {
            border: 3px solid #ccc4e1;
        }
        #como-funciona img:hover {
            border: 3px solid #b9acdb;
        }
        .dropdown-item:active {
            background-color: #423056;
        }
    </style>
</head>

<body>

    <div class="container">
        <header
            class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <img src="/images/logo-quizeiro-212x50.png" alt="Quizeiro" height="50">
            </a>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#home" class="nav-link px-2 link-dark">Início</a></li>
                <li><a href="#como-funciona" class="nav-link px-2 link-dark">Como Funciona</a></li>
                <li><a href="#criar-quiz" class="nav-link px-2 link-dark">Criar Quiz</a></li>
                <li><a href="#ultimos-criados" class="nav-link px-2 link-dark">Últimos Criados</a></li>
                <li><a href="#contato" class="nav-link px-2 link-dark">Contato</a></li>
            </ul>

            <div class="col-md-3 text-end">
                @auth
                    <div class="dropdown text-end" style="display: inline-block">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser"
                            data-bs-toggle="dropdown" aria-expanded="false" title="{{ Auth::user()->name }}">
                            <img src="{{ Gravatar::get(Auth::user()->email) }}" alt="{{ Auth::user()->name }}"
                                width="32" height="32" class="rounded-circle">
                        </a>

                        <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser">
                            <li><a class="dropdown-item" href="{{ route('account.edit') }}">Perfil</a></li>
                            <li><a class="dropdown-item" href="{{ route('quizzes.index') }}">Meus Quizzes</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @if (config('adminlte.logout_method'))
                                    {{ method_field(config('adminlte.logout_method')) }}
                                @endif
                                {{ csrf_field() }}
                            </form>
                            <li><a class="dropdown-item" id="logout" href="#">Sair</a></li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-0 me-2">Entrar</a>
                    <a href="{{ route('register') }}" class="btn btn-primary rounded-0">Cadastrar</a>
                @endauth
            </div>
        </header>
    </div>

    <div class="b-example-divider"></div>

    <!-- Banner principal -->
    <section class="py-5 text-center container" id="home">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Bem-vindo ao <strong>Quizeiro</strong>!</h1>
                <p class="lead text-muted">Quizeiro é uma plataforma gratuita que permite que os usuários criem seus
                    próprios quizzes personalizados e os compartilhem com amigos.</p>
                <p>
                    <a href="{{ route('quizzes.index') }}" class="btn btn-primary my-2 rounded-0">Começar agora</a>
                </p>
            </div>
        </div>
    </section>

    <!-- Como Funciona -->
    <section id="como-funciona" class="bg-light py-5">
        <div class="container">
            <h1 class="text-center pb-5 fw-light">Como Funciona</h1>
            <div class="row">
                <div class="col-lg-4">
                    <h3 class="fw-light">1. Cadastre-se</h3>
                    <img src="/images/banner-cadastro.png" alt="Crie" width="100%">
                    <p class="pt-2">Cadastre-se agora! É rápido e totalmente gratuito!</p>
                </div>
                <div class="col-lg-4">
                    <h3 class="fw-light">2. Crie seu Quiz</h3>
                    <img src="/images/banner-customize.png" alt="Crie" width="100%">
                    <p class="pt-2">Personalize e crie quizzes com perguntas e respostas!</p>
                </div>
                <div class="col-lg-4">
                    <h3 class="fw-light">3. Compartilhe</h3>
                    <img src="/images/banner-share.png" alt="Crie" width="100%">
                    <p class="pt-2">Compartilhe seu quiz com qualquer pessoa que desejar!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Criar Quiz -->
    <section id="criar-quiz" class="py-5">
        <div class="container">
            <h1 class="text-center fw-light">Comece a criar o seu quiz</h1>
            <p class="text-center">Crie seu próprio quiz personalizado agora mesmo!</p>
            <div class="text-center">
                <a href="{{ route('quizzes.index') }}" class="btn btn-primary btn-lg rounded-0">Criar Quiz</a>
            </div>
        </div>
    </section>

    <!-- Últimos Criados -->
    <section id="ultimos-criados">
        <div class="album py-5 bg-light">
            <div class="container">
                <h1 class="text-center fw-light pb-4">Últimos Criados</h1>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @forelse ($publicQuizzes as $quiz)
                    <div class="col">
                        <div class="card shadow-sm">
                            <a href="{{ route('quizzes.share', [$quiz->user->username, $quiz->slug]) }}" target="_blank"><img src="/images/banner-default-quiz.png" alt="{{ $quiz->title }}" width="100%"></a>
                            <div class="card-body">
                                <h5>{{ Str::limit($quiz->title, 30) }}</h5>
                                <p class="card-text">{{ Str::limit($quiz->description, 50) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('quizzes.share', [$quiz->user->username, $quiz->slug]) }}" class="btn btn-sm btn-outline-secondary">Visualizar</a>
                                    </div>
                                    <small class="text-muted">{{ $quiz->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <p class="text-center">Sem quizzes públicos, por enquanto.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Contato -->
    <section id="contato" class="py-5">
        <div class="container">
            <h1 class="text-center fw-light pb-3">Entre em Contato</h1>
            <p class="text-center">Tem alguma pergunta ou comentário? Ficaremos felizes em ouvir você.</p>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    @if(Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('contact') }}" id="formContact">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" placeholder="Digite seu nome" maxlength="30" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" placeholder="Digite seu e-mail" maxlength="30" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensagem</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message" rows="5" placeholder="Digite sua mensagem" required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" id="btnContact" class="btn btn-primary rounded-0">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3">
        <div class="container">
            &copy; 2023 Quizeiro - Todos os direitos reservados.
        </div>
    </footer>

    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#logout').click(function(e) {
                e.preventDefault();
                $('#logout-form').submit();
            });
            $('#formContact').submit(function(e) {
                //e.preventDefault();
                $('#btnContact').addClass('disabled');
                $('#btnContact').html('<span class="spinner-border spinner-border-sm"></span> Enviando...');
            });
        });
    </script>
</body>

</html>
