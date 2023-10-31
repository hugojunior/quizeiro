var User = new Phaser.Class({
  Extends: Phaser.Scene,
  initialize: function User() {
    Phaser.Scene.call(this, {
      key: 'user'
    });
  },
  preload() {
    this.load.html('userForm', 'html/scenes/user/form.html');
    this.load.audio('music', ['sounds/game-music.mp3']);
    this.load.image('logo200x132', 'images/logo-200x132-white.png');
    this.load.image('personLaptop77x100', 'images/person-laptop-77x100.png');
    this.load.image('personBike60x79', 'images/person-bike-60x79.png');
    this.load.image('backgroundUser', 'images/bg-scene-user.png');
  },
  create() {
    this.sound.removeAll();
    var music = this.sound.add('music', {
      loop: true,
      volume: 0.2
    }).play();
    this.add.image(400, 214, 'backgroundUser');
    this.add.image(250, 210, 'personLaptop77x100');
    this.add.image(400, 150, 'logo200x132');
    var personMotion = this.add.image(0, 390, 'personBike60x79');
    const tween = this.add.tween({
      targets: personMotion,
      x: 1500,
      duration: 20000,
      yoyo: false,
      ease: 'Linear',
      repeat: -1
    });
    this.add.dom(380, 280).setInteractive().createFromCache('userForm');
    document.querySelector('.btnUser').addEventListener('click', () => {
      var nameUser = document.querySelector('.nameUser');
      if (nameUser.value !== '') {
        this.scene.start('info', {
          name: nameUser.value
        });
      }
    });
  }
});
var Info = new Phaser.Class({
  Extends: Phaser.Scene,
  initialize: function Info() {
    Phaser.Scene.call(this, {
      key: 'info'
    });
  },
  init(data) {
    this.name = data.name;
  },
  preload() {
    this.load.image('backgroundInfo', 'images/bg-scene-info.png');
    this.load.image('logo100x66', 'images/logo-100x66-white.png');
    this.load.html('infoBriefing', 'html/scenes/info/briefing.html');
  },
  create() {
    this.add.image(400, 214, 'backgroundInfo');
    this.add.image(400, 50, 'logo100x66');
    this.add.text(27, 40, 'Bem-vindo(a),', {
      fontFamily: "'Courier New', Courier, monospace",
      fontSize: 20,
      color: '#ffffff',
      fontWeight: 'bold'
    });
    this.add.text(27, 60, this.name + '!', {
      fontFamily: "'Courier New', Courier, monospace",
      fontSize: 30,
      color: '#fab40a'
    });
    this.add.dom(400, 250).setInteractive().createFromCache('infoBriefing');
    document.querySelector('.btnInfo').addEventListener('click', () => {
      this.scene.start('game', {
        name: this.name
      });
    });
  }
});
var Game = new Phaser.Class({
  Extends: Phaser.Scene,
  initialize: function Game() {
    Phaser.Scene.call(this, {
      key: 'game'
    });
  },
  init(data) {
    this.name = data.name;
    this.initialCountdownTime = 180; //segundos
    this.totalQuestions = 20; // total de questões (max: 50)
    this.lifes = 3;
    this.questionsCorrects = 0;
    this.questionsJson = [];
    this.currentQuestionIndex = null;
    this.gameEnd = false;
    this.helpBox = false;
    this.infoBox = false;
    this.finished = false;
  },
  preload() {
    this.input.keyboard.enabled = true;
    this.load.json('questionsJson', 'js/questions.json');
    this.load.audio("sGameOver", ["sounds/game-over.mp3"]);
    this.load.audio("sGameSuccess", ["sounds/game-success.mp3"]);
    this.load.audio("sQuestionCorrect", ["sounds/question-correct.mp3"]);
    this.load.audio("sQuestionIncorrect", ["sounds/question-incorrect.mp3"]);
    this.load.audio("sQuestionTime", ["sounds/question-time.mp3"]);
    this.load.html('question', 'html/scenes/game/question.html');
    this.load.html('gameOver', 'html/scenes/game/game-over.html');
    this.load.html('gameFinished', 'html/scenes/game/finished.html');
    this.load.html('gameHelp', 'html/scenes/game/help.html');
    this.load.html('gameInfo', 'html/scenes/game/info.html');
    this.load.html('gameScore', 'html/scenes/game/score.html');
    this.load.html('gameEe1', 'html/scenes/game/ee1.html');
    this.load.image('personBike60x79', 'images/person-bike-60x79.png');
    this.load.image('particlesGreen', 'images/particles-green.png');
    this.load.image('boxName', 'images/box-name.png');
    this.load.image('boxTime', 'images/box-time.png');
    this.load.image('logoGame156x66', 'images/logo-game-156x66.png');
    this.loadLifeImages();
    if (this.isDay()) {
      this.load.image('backgroundGame', 'images/bg-scene-game-day.png');
    } else {
      this.load.image('backgroundGame', 'images/bg-scene-game-night.png');
    }
  },
  create() {
    this.add.image(400, 214, 'backgroundGame');
    this.add.image(135, 35, 'boxName');
    this.add.image(710, 35, 'boxTime');
    this.add.image(420, 40, 'logoGame156x66');
    this.sGameOver = this.sound.add("sGameOver", {
      loop: false,
      volume: 1
    });
    this.sGameSuccess = this.sound.add("sGameSuccess", {
      loop: false,
      volume: 1
    });
    this.sQuestionCorrect = this.sound.add("sQuestionCorrect", {
      loop: false,
      volume: 1
    });
    this.sQuestionIncorrect = this.sound.add("sQuestionIncorrect", {
      loop: false,
      volume: 1
    });
    this.sQuestionTime = this.sound.add("sQuestionTime", {
      loop: false,
      volume: 1
    });
    this.particlesGreen = this.add.particles('particlesGreen');
    this.personBike60x79 = this.add.image(34, 380, 'personBike60x79');
    this.boxLifes = this.add.image(744, 52, 'boxLifes3');
    this.add.text(80, 25, this.name, {
      fontFamily: "'Courier New', Courier, monospace",
      fontSize: 15,
      color: "#EDEEEA"
    });
    this.add.text(628, 412, '[F1] Ajuda - ', {
      fontFamily: "'Courier New', Courier, monospace",
      fontSize: 12,
      color: "#EDEEEA"
    }).setInteractive().on('pointerdown', function () {
      this.showHelp();
    }, this);
    this.add.text(722, 412, '[P] Placar', {
      fontFamily: "'Courier New', Courier, monospace",
      fontSize: 12,
      color: "#EDEEEA"
    }).setInteractive().on('pointerdown', function () {
      this.showScore();
    }, this);

    this.countdownText = this.add.text(695, 20, this.formatTime(this.initialCountdownTime), {
      fontFamily: "'Courier New', Courier, monospace",
      fontSize: 25,
      color: "#EDEEEA"
    });
    this.timedEvent = this.time.addEvent({
      delay: 1000,
      callback: this.onEvent,
      callbackScope: this,
      loop: true
    });
    this.add.text(73, 46, 'trocar', {
      fontFamily: "'Courier New', Courier, monospace",
      fontSize: 11,
      color: "#EDEEEA"
    }).setInteractive().on('pointerover', function () {
      this.setStyle({
        fill: '#EE642E'
      });
    }).on('pointerout', function () {
      this.setStyle({
        fill: '#EDEEEA'
      });
    }).on('pointerdown', function () {
      this.scene.restart();
      this.scene.start('user');
    }, this);
    this.questionsJson = this.cache.json.get('questionsJson').sort(() => Math.random() - 0.5).slice(0, this.totalQuestions);
    this.currentQuestionIndex = 0;
    this.showQuestion();
    this.eventKeys();
  },
  showQuestion(questionIndex) {
    var questionIndex = questionIndex || this.currentQuestionIndex;
    var question = this.getQuestionByIndex(this.questionsJson, questionIndex);
    this.questionHTML = this.add.dom(400, 208).setInteractive().createFromCache('question');
    document.querySelector("#questionPosition").innerHTML = `Questão ${this.currentQuestionIndex + 1} de ${this.questionsJson.length}`;
    document.querySelector("#questionCorrect").innerHTML = `Acertos: ${this.questionsCorrects}`;
    document.querySelector("#questionTitle").innerHTML = question.pergunta;
    document.querySelector("#questionTag").innerHTML = question.tema;
    question.opcoes.forEach((option, index) => {
      document.querySelector("#qr").innerHTML += `<li data-r="${index + 1}">${this.htmlEncode(option)}</li>`;
    });
    document.getElementById('qr').addEventListener('click', (event) => {
      if (document.querySelector('#boxQuestion')) {
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
          return this.gameOver('[Acabaram suas vidas]');
        } else {
          this.updateLifes();
        }
      }
      if (this.questionsJson.length === (this.currentQuestionIndex + 1)) {
        this.gameFinish();
      } else {
        this.currentQuestionIndex += 1;
        this.showQuestion(this.currentQuestionIndex);
      }
    }
  },
  checkCorrectQuestion(questionIndex, answerNumber) {
    var question = this.getQuestionByIndex(this.questionsJson, questionIndex);
    var correct = false;
    if (question['opcoes'][answerNumber - 1] === question['correta']) {
      correct = true;
      this.sQuestionCorrect.play();
      this.questionsCorrects += 1;
      this.initialCountdownTime += 10;
      var emitter = this.particlesGreen.createEmitter({
        tint: 0x00ff00,
        alpha: { start: 1, end: 0 },
        scale: { start: 0.5, end: 1.5 },
        speed: { random: [20, 100] },
        accelerationY: { random: [-100, 200] },
        rotate: { min: -180, max: 180 },
        lifespan: { min: 300, max: 800 },
        frequency: 20,
        maxParticles: 4
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
  },
  loadLifeImages() {
    for (var i = 0; i <= 3; i++) {
      this.load.image(`boxLifes${i}`, `images/box-stars-${i}.png`);
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
    this.add.dom(400, 208).setInteractive().createFromCache('gameOver');
    document.querySelector("#gameOverMessage").innerHTML = text || '';
    document.querySelector("#gameOverScore").innerHTML = `Acertou ${this.questionsCorrects} de ${this.questionsJson.length} questões!`;
    document.querySelector("#playAgain").addEventListener('click', () => {
      this.scene.restart();
    });
  },
  gameFinish() {
    this.gameEnd = true;
    this.gameFinished = true;
    this.sQuestionTime.stop();
    this.sGameSuccess.play();
    this.timedEvent.remove(false);
    this.setScore();
    this.add.dom(400, 208).setInteractive().createFromCache('gameFinished');
    document.querySelector("#gameFinishedScore").innerHTML = `Acertou ${this.questionsCorrects} de ${this.questionsJson.length} questões<br> Pontuação: <strong>${this.userScore}</strong> [P]`;
    document.querySelector("#playAgain").addEventListener('click', () => {
      this.playAgain();
    });
    document.querySelector("#credits").addEventListener('click', () => {
      this.showCredits();
    });
    this.personBike60x79.destroy();
    this.personBike60x79 = this.physics.add.image(760, 380, 'personBike60x79');
    this.personBike60x79.setVelocity(200, 200);
    this.personBike60x79.setBounce(1, 1);
    this.personBike60x79.setCollideWorldBounds(true);
    var emitter = this.particlesGreen.createEmitter({
      speed: 100,
      scale: {
        start: 0.5,
        end: 0
      },
      blendMode: 'ADD'
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
      this.gameOver('[Seu tempo esgotou]');
    } else {
      if (this.initialCountdownTime === 10) {
        this.sQuestionTime.play();
      }
      if (this.initialCountdownTime > 10) {
        this.sQuestionTime.stop();
      }
      this.initialCountdownTime -= 1;
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
          end: 0
        },
        blendMode: 'ADD'
      });
      emitter.startFollow(this.personBike60x79);
    }
    const tween = this.add.tween({
      targets: this.personBike60x79,
      x: this.personBike60x79.x + 36.5,
      duration: 1000,
      yoyo: false,
      ease: 'Linear',
      repeat: 0,
      onComplete: () => {
        this.input.keyboard.enabled = true;
        emitter && emitter.stop();
      }
    });
  },
  showHelp() {
    if (!this.helpBox) {
      this.helpBox = this.add.dom(400, 208).setInteractive().createFromCache('gameHelp');
      document.querySelector("#closeHelp").addEventListener('click', () => {
        this.showHelp();
      });
    } else {
      this.helpBox.destroy();
      this.helpBox = false;
    }
  },
  showScore() {
    if (!this.scoreBox) {
      var scoreLocal = JSON.parse(localStorage.getItem("score") || "[]").sort((a, b) => {
        return b.score - a.score;
      }).slice(0, 6);
      this.scoreBox = this.add.dom(400, 208).setInteractive().createFromCache('gameScore');
      document.querySelector("#boxScore ol").innerHTML = new Array(6)
        .fill('<li>[vazio]</li>')
        .map((item, index) => {
          if (scoreLocal[index]) {
            var currentUser = this.name === scoreLocal[index].name ? ' <small>(você)</small>' : '';
            return `<li>${scoreLocal[index].score} - ${scoreLocal[index].name}${currentUser}</li>`;
          }
          return item;
        })
        .join('');
      document.querySelector("#closeScore").addEventListener('click', () => {
        this.showScore();
      });
    } else {
      this.scoreBox.destroy();
      this.scoreBox = false;
    }
  },
  setScore() {
    var scoreLocal = JSON.parse(localStorage.getItem("score") || "[]");
    this.userScore = Math.round(((this.questionsCorrects * 100) + (this.initialCountdownTime * 5)) * 1000000 / 3800);
    scoreLocal.push({
      name: this.name,
      score: this.userScore
    });
    localStorage.setItem("score", JSON.stringify(scoreLocal));
  },
  showInfo() {
    if (!this.infoBox) {
      this.infoBox = this.add.dom(400, 208).setInteractive().createFromCache('gameInfo');
      document.querySelector("#closeInfo").addEventListener('click', () => {
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
        this.sound.pauseAll();
        this.ee1 = this.add.dom(400, 204).setInteractive().createFromCache('gameEe1');
        document.querySelector("#closeEe1").addEventListener('click', () => {
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
    var minutes = Math.floor(seconds / 60).toString().padStart(2, '0');
    var partInSeconds = seconds % 60;
    partInSeconds = partInSeconds.toString().padStart(2, '0');
    return `${minutes}:${partInSeconds}`;
  },
  htmlEncode(str) {
    return str.replace(/[&<>"']/g, function ($0) {
      return "&" + { "&": "amp", "<": "lt", ">": "gt", '"': "quot", "'": "#39" }[$0] + ";";
    });
  },
  getQuestionByIndex(questions, index) {
    return questions[index];
  },
  eventKeys() {
    this.input.keyboard.on('keydown', function (event) {
      event.preventDefault();
      var code = event.keyCode;
      if (document.querySelector('#boxQuestion')) {
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
      if (code === 67) {
        this.showCredits();
      }
      if (code === 74) {
        this.playAgain();
      }
    }, this);
  }
});
var config = {
  type: Phaser.WEBGL,
  parent: 'game',
  width: 800,
  height: 429,
  backgroundColor: '#292737',
  autoCenter: true,
  dom: {
    createContainer: true
  },
  physics: {
    default: 'arcade',
    arcade: {
      gravity: {
        y: 50
      }
    }
  },
  scene: [User, Info, Game]
};
var game = new Phaser.Game(config);