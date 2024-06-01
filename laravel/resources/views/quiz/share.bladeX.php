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
	<meta name="keywords"
		content="quiz,quizeiro,quizdev,quiz online,gratis,personalizado,aprender,estudar,jogar,projeto,uni7,faculdade" />
	<meta name="author" content="Hugo Júnior" />
	<title>Quizeiro - {{ $quiz->title }}</title>
	<script src="/js/phaser-arcade-physics.js"></script>
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

		var User = new Phaser.Class({
			Extends: Phaser.Scene,
			initialize: function User() {
				Phaser.Scene.call(this, {
					key: "user",
				});
			},
			preload() {
				this.load.html("userForm", "/html/scenes/user/form.html");
				this.load.audio("music", ["/sounds/game-music.mp3"]);
				this.load.image(
					"logo346x85",
					"/images/logo-quizeiro-white-shadow-346x85.png"
				);
				this.load.image("personBike60x79", "/images/person-bike-60x79.png");
				this.load.image("backgroundUser", "/images/bg-scene-user.png");
			},
			create() {
				this.sound.removeAll();
				var music = this.sound
					.add("music", {
						loop: true,
						volume: 0.2,
					})
					.play();
				this.add.image(400, 214, "backgroundUser");
				this.add.image(385, 210, "logo346x85");
				var personMotion = this.add.image(0, 390, "personBike60x79");
				const tween = this.add.tween({
					targets: personMotion,
					x: 1500,
					duration: 20000,
					yoyo: false,
					ease: "Linear",
					repeat: -1,
				});
				this.add.dom(408, 280).setInteractive().createFromCache("userForm");

				document.querySelector("#fUser").addEventListener("submit", (e) => {
					e.preventDefault();
					document.querySelector(".btnUser").click();
				});

				document.querySelector(".btnUser").addEventListener("click", () => {
					var nameUser = document.querySelector(".nameUser");
					if (nameUser.value !== "") {
						this.scene.start("info", {
							name: nameUser.value,
						});
					}
				});
			},
		});
		var Info = new Phaser.Class({
			Extends: Phaser.Scene,
			initialize: function Info() {
				Phaser.Scene.call(this, {
					key: "info",
				});
			},
			init(data) {
				this.name = data.name;
			},
			preload() {
				this.load.image("backgroundInfo", "/images/bg-scene-info.png");
				this.load.image("logo55x66", "/images/logo-quizeiro-white-55x66.png");
				this.load.html("infoBriefing", "/html/scenes/info/briefing.html");
			},
			create() {
				this.add.image(400, 214, "backgroundInfo");
				this.add.image(400, 50, "logo55x66");
				this.add.text(27, 40, "Bem-vindo(a),", {
					fontFamily: "'Courier New', Courier, monospace",
					fontSize: 20,
					color: "#ffffff",
					fontWeight: "bold",
				});
				this.add.text(27, 66, this.name + "!", {
					fontFamily: "'Courier New', Courier, monospace",
					fontSize: 30,
					color: "#fab40a",
				});
				this.add.dom(400, 250).setInteractive().createFromCache("infoBriefing");
				document.querySelector(".btnInfo").addEventListener("click", () => {
					this.scene.start("game", {
						name: this.name,
					});
				});
			},
		});
		var Game = new Phaser.Class({
			Extends: Phaser.Scene,
			initialize: function Game() {
				Phaser.Scene.call(this, {
					key: "game",
				});
			},
			init(data) {
				this.name = data.name;
				this.initialCountdownTime = 120; //segundos
				this.totalQuestions = 10;
				this.lifes = 3;
				this.questionsCorrects = 0;
				this.currentQuestionIndex = null;
				this.gameEnd = false;
				this.helpBox = false;
				this.infoBox = false;
				this.finished = false;
				this.questionsJson = {
					!!$questions!!
				};
				this.appScore = [];
				this.appData = {
					name: this.name,
					time_start: Date.now(),
					time_end: null,
					time_left: null,
					life_left: 3,
					score: null,
					end_type: null,
					overlay_views: [],
					questions: [],
					client: {
						userAgent: window.navigator.userAgent,
						vendor: window.navigator.vendor,
						platform: window.navigator.platform,
						language: window.navigator.language,
						screen: {
							width: window.screen.availWidth,
							height: window.screen.availHeight,
						},
					},
				};
				this.getScores();
			},
			preload() {
				this.input.keyboard.enabled = true;
				this.load.audio("sGameOver", ["/sounds/game-over.mp3"]);
				this.load.audio("sGameSuccess", ["/sounds/game-success.mp3"]);
				this.load.audio("sQuestionCorrect", ["/sounds/question-correct.mp3"]);
				this.load.audio("sQuestionIncorrect", ["/sounds/question-incorrect.mp3"]);
				this.load.audio("sQuestionTime", ["/sounds/question-time.mp3"]);
				this.load.html("question", "/html/scenes/game/question.html");
				this.load.html("gameOver", "/html/scenes/game/game-over.html");
				this.load.html("gameFinished", "/html/scenes/game/finished.html");
				this.load.html("gameHelp", "/html/scenes/game/help.html");
				this.load.html("gameInfo", "/html/scenes/game/info.html");
				this.load.html("gameInfo2", "/html/scenes/game/info2.html");
				this.load.html("gameInfo3", "/html/scenes/game/info3.html");
				this.load.html("gameScore", "/html/scenes/game/score.html");
				this.load.html("gameEe1", "/html/scenes/game/ee1.html");
				this.load.image("personBike60x79", "/images/person-bike-60x79.png");
				this.load.image("particlesGreen", "/images/particles-green.png");
				this.load.image("boxName", "/images/box-name.png");
				this.load.image("boxTime", "/images/box-time.png");
				this.load.image("logoGame156x66", "/images/logo-game-156x66.png");
				this.load.image("loadImg1", "/images/prof-marum-bw.png");
				this.load.image("loadImg2", "/images/prof-regis.png");
				this.load.image("loadImg3", "/images/prof-marcelo.png");
				this.load.image("loadImg4", "/images/rafael.png");
				this.loadLifeImages();
				if (this.isDay()) {
					this.load.image("backgroundGame", "/images/bg-scene-game-day.png");
				} else {
					this.load.image("backgroundGame", "/images/bg-scene-game-night.png");
				}
			},
			shuffleQuestions() {
				var currentIndex = this.questionsJson.length,
					temporaryValue,
					randomIndex;
				while (0 !== currentIndex) {
					randomIndex = Math.floor(Math.random() * currentIndex);
					currentIndex -= 1;
					temporaryValue = this.questionsJson[currentIndex];
					this.questionsJson[currentIndex] = this.questionsJson[randomIndex];
					this.questionsJson[currentIndex]["opcoes"] = this.questionsJson[
							randomIndex
						]["opcoes"]
						.sort(() => Math.random() - 0.5)
						.slice(0, 4);
					this.questionsJson[randomIndex] = temporaryValue;
				}
			},
			create() {
				this.add.image(400, 214, "backgroundGame");
				this.add.image(135, 35, "boxName");
				this.add.image(710, 35, "boxTime");
				this.add.image(420, 40, "logoGame156x66");
				this.sGameOver = this.sound.add("sGameOver", {
					loop: false,
					volume: 1,
				});
				this.sGameSuccess = this.sound.add("sGameSuccess", {
					loop: false,
					volume: 1,
				});
				this.sQuestionCorrect = this.sound.add("sQuestionCorrect", {
					loop: false,
					volume: 1,
				});
				this.sQuestionIncorrect = this.sound.add("sQuestionIncorrect", {
					loop: false,
					volume: 1,
				});
				this.sQuestionTime = this.sound.add("sQuestionTime", {
					loop: false,
					volume: 1,
				});
				this.particlesGreen = this.add.particles("particlesGreen");
				this.personBike60x79 = this.add.image(34, 380, "personBike60x79");
				this.boxLifes = this.add.image(744, 52, "boxLifes3");
				this.add.text(80, 25, this.name, {
					fontFamily: "'Courier New', Courier, monospace",
					fontSize: 15,
					color: "#EDEEEA",
				});
				this.add
					.text(628, 412, "[F1] Ajuda - ", {
						fontFamily: "'Courier New', Courier, monospace",
						fontSize: 12,
						color: "#EDEEEA",
					})
					.setInteractive()
					.on(
						"pointerdown",
						function() {
							this.showHelp();
						},
						this
					);
				this.add
					.text(722, 412, "[P] Placar", {
						fontFamily: "'Courier New', Courier, monospace",
						fontSize: 12,
						color: "#EDEEEA",
					})
					.setInteractive()
					.on(
						"pointerdown",
						function() {
							this.showScore();
						},
						this
					);

				this.countdownText = this.add.text(
					695,
					20,
					this.formatTime(this.initialCountdownTime), {
						fontFamily: "'Courier New', Courier, monospace",
						fontSize: 25,
						color: "#EDEEEA",
					}
				);
				this.timedEvent = this.time.addEvent({
					delay: 1000,
					callback: this.onEvent,
					callbackScope: this,
					loop: true,
				});
				this.add
					.text(73, 46, "trocar", {
						fontFamily: "'Courier New', Courier, monospace",
						fontSize: 11,
						color: "#EDEEEA",
					})
					.setInteractive()
					.on("pointerover", function() {
						this.setStyle({
							fill: "#EE642E",
						});
					})
					.on("pointerout", function() {
						this.setStyle({
							fill: "#EDEEEA",
						});
					})
					.on(
						"pointerdown",
						function() {
							this.scene.restart();
							this.scene.start("user");
						},
						this
					);
				this.shuffleQuestions();
				this.currentQuestionIndex = 0;
				this.showQuestion();
				this.eventKeys();
			},
			showQuestion(questionIndex) {
				var questionIndex = questionIndex || this.currentQuestionIndex;
				var question = this.getQuestionByIndex(this.questionsJson, questionIndex);
				this.questionHTML = this.add
					.dom(400, 208)
					.setInteractive()
					.createFromCache("question");
				document.querySelector("#questionPosition").innerHTML = `Questão ${this.currentQuestionIndex + 1
                    } de ${this.questionsJson.length}`;
				document.querySelector(
					"#questionCorrect"
				).innerHTML = `Acertos: ${this.questionsCorrects}`;
				document.querySelector("#questionTitle").innerHTML = question.pergunta;
				question.opcoes.forEach((option, index) => {
					document.querySelector("#qr").innerHTML += `<li data-r="${index + 1
                        }">${this.htmlEncode(option)}</li>`;
				});
				document.getElementById("qr").addEventListener("click", (event) => {
					if (document.querySelector("#boxQuestion")) {
						if (event.target.dataset.r) {
							this.checkQuestion(this.currentQuestionIndex, event.target.dataset.r);
						}
					}
				});
			},
			checkQuestion(questionIndex, answerNumber) {
				if (!this.gameEnd) {
					this.questionHTML.destroy();
					if (!this.checkCorrectQuestion(questionIndex, answerNumber)) {
						this.sQuestionIncorrect.play();
						if (this.lifes === 0) {
							return this.gameOver("[Acabaram suas vidas]");
						} else {
							this.updateLifes();
						}
					}
					if (this.questionsJson.length === this.currentQuestionIndex + 1) {
						this.gameFinish();
					} else {
						this.currentQuestionIndex += 1;
						this.showQuestion(this.currentQuestionIndex);
					}
				}
			},
			checkCorrectQuestion(questionIndex, answerNumber) {
				var question = this.getQuestionByIndex(this.questionsJson, questionIndex);

				this.appData.questions.push({
					question: question.pergunta,
					answers: question.opcoes,
					correct: question.correta,
					answer: question.opcoes[answerNumber - 1],
				});
				var correct = false;
				if (question["opcoes"][answerNumber - 1] === question["correta"]) {
					correct = true;
					this.sQuestionCorrect.play();
					this.questionsCorrects += 1;
					this.initialCountdownTime += 10;
					var emitter = this.particlesGreen.createEmitter({
						tint: 0x00ff00,
						alpha: {
							start: 1,
							end: 0,
						},
						scale: {
							start: 0.5,
							end: 1.5,
						},
						speed: {
							random: [20, 100],
						},
						accelerationY: {
							random: [-100, 200],
						},
						rotate: {
							min: -180,
							max: 180,
						},
						lifespan: {
							min: 300,
							max: 800,
						},
						frequency: 20,
						maxParticles: 4,
					});
					emitter.startFollow(this.countdownText);
					setTimeout(() => emitter.stop(), 500);
				}
				this.tweenProg(correct);
				return correct;
			},
			updateLifes() {
				this.lifes -= 1;
				this.boxLifes.destroy();
				this.boxLifes = this.add.image(744, 52, `boxLifes${this.lifes}`);
				this.appData.life_left -= 1;
			},
			loadLifeImages() {
				for (var i = 0; i <= 3; i++) {
					this.load.image(`boxLifes${i}`, `/images/box-stars-${i}.png`);
				}
			},
			isDay() {
				const hours = new Date().getHours();
				return hours >= 6 && hours < 18;
			},
			gameOver(text) {
				this.input.keyboard.enabled = false;
				this.gameEnd = true;
				this.sQuestionTime.stop();
				this.sGameOver.play();
				this.timedEvent.remove(false);
				this.appData.time_end = Date.now();
				this.appData.end_type = "gameover" + text;
				this.sendAppData();

				this.add.dom(400, 208).setInteractive().createFromCache("gameOver");
				document.querySelector("#gameOverMessage").innerHTML = text || "";
				document.querySelector(
					"#gameOverScore"
				).innerHTML = `Acertou ${this.questionsCorrects} de ${this.questionsJson.length} questões!`;
				document.querySelector("#playAgain").addEventListener("click", () => {
					this.scene.restart();
				});
			},
			gameFinish() {
				this.gameEnd = true;
				this.gameFinished = true;
				this.sQuestionTime.stop();
				this.sGameSuccess.play();
				this.timedEvent.remove(false);
				this.appData.time_end = Date.now();
				this.appData.end_type = "success";
				this.setUserScore();
				this.sendAppData();
				this.add.dom(400, 208).setInteractive().createFromCache("gameFinished");
				document.querySelector(
						"#gameFinishedScore"
					).innerHTML =
					`Acertou ${this.questionsCorrects} de ${this.questionsJson.length} questões<br> Pontuação: <strong>${this.userScore}</strong> [P]`;
				document.querySelector("#playAgain").addEventListener("click", () => {
					this.playAgain();
				});
				document.querySelector("#credits").addEventListener("click", () => {
					this.showCredits();
				});
				this.personBike60x79.destroy();
				this.personBike60x79 = this.physics.add.image(760, 380, "personBike60x79");
				this.personBike60x79.setVelocity(200, 200);
				this.personBike60x79.setBounce(1, 1);
				this.personBike60x79.setCollideWorldBounds(true);
				var emitter = this.particlesGreen.createEmitter({
					speed: 100,
					scale: {
						start: 0.5,
						end: 0,
					},
					blendMode: "ADD",
				});
				emitter.startFollow(this.personBike60x79);
			},
			playAgain() {
				if (this.gameEnd) {
					this.gameEnd = false;
					this.gameFinished = false;
					this.userScore = false;
					this.scene.restart();
				}
			},
			onEvent() {
				if (this.initialCountdownTime === 0) {
					this.timedEvent.remove(false);
					this.questionHTML.destroy();
					this.gameOver("[Seu tempo esgotou]");
				} else {
					if (this.initialCountdownTime === 10) {
						this.sQuestionTime.play();
					}
					if (this.initialCountdownTime > 10) {
						this.sQuestionTime.stop();
					}
					this.initialCountdownTime -= 1;
					this.appData.time_left = this.formatTime(this.initialCountdownTime);
					this.countdownText.setText(this.formatTime(this.initialCountdownTime));
				}
			},
			tweenProg(correct) {
				this.input.keyboard.enabled = false;
				if (correct) {
					var emitter = this.particlesGreen.createEmitter({
						speed: 100,
						scale: {
							start: 0.5,
							end: 0,
						},
						blendMode: "ADD",
					});
					emitter.startFollow(this.personBike60x79);
				}
				const tween = this.add.tween({
					targets: this.personBike60x79,
					x: this.personBike60x79.x + 73,
					duration: 1000,
					yoyo: false,
					ease: "Linear",
					repeat: 0,
					onComplete: () => {
						this.input.keyboard.enabled = true;
						emitter && emitter.stop();
					},
				});
			},
			showHelp() {
				if (!this.helpBox) {
					this.appData.overlay_views.push("help");
					this.helpBox = this.add
						.dom(400, 208)
						.setInteractive()
						.createFromCache("gameHelp");
					document.querySelector("#closeHelp").addEventListener("click", () => {
						this.showHelp();
					});
				} else {
					this.helpBox.destroy();
					this.helpBox = false;
				}
			},
			showScore() {
				if (!this.scoreBox) {
					this.appData.overlay_views.push("scores");
					this.scoreBox = this.add
						.dom(400, 208)
						.setInteractive()
						.createFromCache("gameScore");
					document.querySelector("#boxScore ol").innerHTML = new Array(6)
						.fill("<li>[vazio]</li>")
						.map((item, index) => {
							if (this.appScore[index]) {
								var currentUser =
									this.name === this.appScore[index].name ?
									" <small>(você)</small>" :
									"";
								return `<li>${this.appScore[index].score} - ${this.appScore[index].name}${currentUser}</li>`;
							}
							return item;
						})
						.join("");
					document.querySelector("#closeScore").addEventListener("click", () => {
						this.showScore();
					});
				} else {
					this.scoreBox.destroy();
					this.scoreBox = false;
				}
			},
			setUserScore() {
				this.userScore = Math.round(
					((this.questionsCorrects * 100 + this.initialCountdownTime * 5) *
						1000000) /
					2100
				);
				this.appData.score = this.userScore;
			},
			setScores(serverData) {
				this.appScore = serverData;
			},
			getScores() {
				fetch("/quizzes/scores/{{ $quiz->id }}", {
						method: "GET",
						headers: {
							"Content-type": "application/json; charset=UTF-8",
							"X-CSRF-TOKEN": "{{ csrf_token() }}",
						},
					})
					.then((response) => response.json())
					.then((json) => {
						this.setScores(json);
					});
			},
			sendAppData() {
				fetch("/quizzes/scores/{{ $quiz->id }}", {
					method: "POST",
					body: JSON.stringify(this.appData),
					headers: {
						"Content-type": "application/json; charset=UTF-8",
						"X-CSRF-TOKEN": "{{ csrf_token() }}",
					},
				}).then(() => this.getScores());
			},
			showInfo() {
				if (!this.infoBox) {
					this.appData.overlay_views.push("info");
					this.infoBox = this.add
						.dom(400, 208)
						.setInteractive()
						.createFromCache("gameInfo");
					document.querySelector("#closeInfo").addEventListener("click", () => {
						this.showInfo();
					});
				} else {
					this.infoBox.destroy();
					this.infoBox = false;
				}
			},
			showInfo2() {
				if (!this.infoBox) {
					this.appData.overlay_views.push("info2");
					this.infoBox = this.add
						.dom(400, 208)
						.setInteractive()
						.createFromCache("gameInfo2");
					document.querySelector("#closeInfo").addEventListener("click", () => {
						this.showInfo();
					});
				} else {
					this.infoBox.destroy();
					this.infoBox = false;
				}
			},
			showInfo3() {
				if (!this.infoBox) {
					this.appData.overlay_views.push("info3");
					this.infoBox = this.add
						.dom(400, 208)
						.setInteractive()
						.createFromCache("gameInfo3");
					document.querySelector("#closeInfo").addEventListener("click", () => {
						this.showInfo();
					});
				} else {
					this.infoBox.destroy();
					this.infoBox = false;
				}
			},
			showCredits() {
				if (this.gameFinished) {
					if (!this.ee1) {
						this.appData.overlay_views.push("credits");
						this.sound.pauseAll();
						this.ee1 = this.add
							.dom(400, 204)
							.setInteractive()
							.createFromCache("gameEe1");
						document.querySelector("#closeEe1").addEventListener("click", () => {
							this.showCredits();
						});
					} else {
						this.ee1.destroy();
						this.ee1 = false;
						this.sound.resumeAll();
					}
				}
			},
			formatTime(seconds) {
				var minutes = Math.floor(seconds / 60)
					.toString()
					.padStart(2, "0");
				var partInSeconds = seconds % 60;
				partInSeconds = partInSeconds.toString().padStart(2, "0");
				return `${minutes}:${partInSeconds}`;
			},
			htmlEncode(str) {
				return str.replace(/[&<>"']/g, function($0) {
					return (
						"&" + {
							"&": "amp",
							"<": "lt",
							">": "gt",
							'"': "quot",
							"'": "#39",
						} [$0] +
						";"
					);
				});
			},
			getQuestionByIndex(questions, index) {
				return questions[index];
			},
			eventKeys() {
				this.input.keyboard.on(
					"keydown",
					function(event) {
						event.preventDefault();
						var code = event.keyCode;
						if (document.querySelector("#boxQuestion")) {
							if (code === 49 || code === 97) {
								this.checkQuestion(this.currentQuestionIndex, 1);
							}
							if (code === 50 || code === 98) {
								this.checkQuestion(this.currentQuestionIndex, 2);
							}
							if (code === 51 || code === 99) {
								this.checkQuestion(this.currentQuestionIndex, 3);
							}
							if (code === 52 || code === 100) {
								this.checkQuestion(this.currentQuestionIndex, 4);
							}
							if (code === 53 || code === 101) {
								this.checkQuestion(this.currentQuestionIndex, 5);
							}
						}
						if (code === 112) {
							this.showHelp();
						}
						if (code === 80) {
							this.showScore();
						}
						if (code === 73) {
							this.showInfo();
						}
						if (code === 79) {
							this.showInfo2();
						}
						if (code === 82) {
							this.showInfo3();
						}
						if (code === 67) {
							this.showCredits();
						}
						if (code === 74) {
							this.playAgain();
						}
					},
					this
				);
			},
		});
		var config = {
			type: Phaser.WEBGL,
			width: 800,
			height: 429,
			backgroundColor: "#292737",
			autoCenter: true,
			dom: {
				createContainer: true,
			},
			scale: {
				mode: Phaser.Scale.FIT,
				parent: "game",
				autoCenter: Phaser.Scale.CENTER_BOTH,
				width: 800,
				height: 429,
			},
			physics: {
				default: "arcade",
				arcade: {
					gravity: {
						y: 50,
					},
				},
			},
			scene: [User, Info, Game],
		};
		document.addEventListener("contextmenu", (event) => event.preventDefault());

		var game = new Phaser.Game(config);
	</script>
</head>

<body onselectstart="return false">
	<div id="container">
		<div id="game"></div>
		<h1 class="share-title">{{ $quiz->title }}</h1>
		<div class="author"><ion-icon name="person-outline"></ion-icon> Criado por <a
				href="{{ route('quizzes.profile', $quiz->user->username) }}">{{ $quiz->user->name }}</a></div>
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
