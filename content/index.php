<?php session_start(); ?>
<html>
	<head>
		<title>
			Скриншоты
		</title>

		<meta content="width=device-width, initial-scale=1" name="viewport" />

		<link rel="stylesheet" type="text/css" href="styles/style.css" />
		<link href="bootstrap/bootstrap.css" rel="stylesheet">
	</head>
	<body>
		<script src="bootstrap/bootstrap.js"></script>
		<?php
            $config = include('config/config.php');
			// Подключение к базе данных: host, dbname, username, password
			try {
			    $dsn = 'pgsql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
			    $pdo = new PDO($dsn, $config['username'], $config['password']);
			} catch (PDOException $e) {
			    die('Подключение не удалось: ' . $e->getMessage());
			}

			setlocale(LC_TIME, "ru-RU");

			$fmt = new IntlDateFormatter(
			    'ru_RU',
			    IntlDateFormatter::SHORT,
			    IntlDateFormatter::NONE,
			    'Europe/Moscow',
			    IntlDateFormatter::GREGORIAN,
			    'dd MMMM'
			);
		?>

	<div class="screen">
		<div class="header">
			<div class="logo">
				<img src="img/logo.png">
				<h2 class="logo-text">
					Скриншоты
				</h2>
			</div>


			<div id="auth" class="controls">
				<div id="welcome">
					
				</div>
				<a id="logout-button" role="button">
					Выход
				</a>
			</div>

			<div id="noauth" class="controls">
				<a id="register" data-bs-toggle="modal" data-bs-target="#registerModal">
					Регистрация
				</a>

				<a id="login" data-bs-toggle="modal" data-bs-target="#loginModal">
					Вход
				</a>
			</div>

		</div>

		<hr>

		<div class="content">
			<?php
		        $sql = "SELECT * FROM posts";
		$posts = $pdo->prepare($sql);
		$posts->execute();

		$sql = "SELECT MAX(created_at) FROM posts";
		$last = $pdo->prepare($sql);
		$last->execute();
		$last = $last->fetch(PDO::FETCH_ASSOC);
		?>

			<div class="content-top">
				<div>
					<h1>	
						<!-- <?= date("d F", strtotime($last['max'])) ?> -->
						<?php
			                $date = $fmt->format(strtotime($last['max']));
							echo $date;
						?>
					</h1>
				</div>
				<div>
					<a href="upload.php" class="button-normal" id="upload-button">
						<img src="img/add.png" class="white" width="24px" />
						<span class="add-text"> Добавить скриншот</span>
					</a>
				</div>
			</div>
			<div class="content-main">

				<?php while($post = $posts->fetch(PDO::FETCH_ASSOC)): ?>
					<div class="image-container">
						<a href="image.php?id=<?= htmlspecialchars($post['id']) ?>">
							<img src="uploaded/<?= htmlspecialchars($post['content']) ?>" class="post-img">
						</a>
						<div class="image-info">	
							<img src="img/clock.png" class="white clock" />
							<span class="image-text">Добавлено</span>
							<span class="image-date"><?= $fmt->format(strtotime($post['created_at']))?></span>
						</div>
					</div>
                <?php endwhile; ?>

			</div>
			<div class="content-more">
				<a href="" class="button-light full-width">
					<img src="img/down.png" class="blue" width="24px" />
					<span>Показать ещё</span>
				</a>
			</div>
		</div>

		<hr>

		<div class="footer">
			<div>
				<a href="" class="mail">info@gmail.com</a>
			</div>
			<div>
				<a href="" class="dark-link">Информация о разработчике</a>
			</div>
		</div>

		<!-- modals -->
		<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">

		        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div id="register-header" class="mb-4">
		        	<span>Регистрация</span>
		        	<span style="color: gray;">Авторизация</span>
		        </div>
		        <div>
		        	<form id="register-form">
			          <div class="mb-3">
			            <input id='name-input' type="text" class="form-control py-2" name="name" placeholder="Ваше имя" required>
			            <p id='name-error' class='error'></p>
			          </div>
			          <div class="mb-3 control-row">
			          	<div>
				            <input id='email-input' type="email" class="form-control py-2" name="email" placeholder="Email" required>
			          		<p id='email-error' class='error'></p>
			          	</div>
			          	<div>
				            <input id='tel-input' type="tel" class="form-control py-2" name="phone" placeholder="Мобильный телефон" required>
				            <p id='phone-error' class='error'></p>
			            </div>
			          </div>

			          <div class="mb-3 control-row">
			          	<div>
				            <input id='password-input' type="password" class="form-control py-2" name="password" placeholder="Пароль" required>
				            <p id='password-error' class='error'></p>
				        </div>
				        <div>
				            <input id='password-confirm-input' type="password" class="form-control py-2" name="password_confirm" placeholder="Повторите пароль" required>
				            <p id='password_confirm-error' class='error'></p>
						</div>
			          </div>

			          <div class="agreement mt-4">
				        <div class="mb-3 form-check">
				        	<div>
							    <input id='check-input' type="checkbox" class="form-check-input" name="check">
				        	</div>
						    <label class="form-check-label" for="exampleCheck1">Согласен на обработку персональный данных</label>
				        		<p id='check-error' class='error'></p>
						</div>
					  </div>

					  <div class="d-grid gap-2">
						  <button type="button" class="btn btn-primary py-2" id="register-button">Зарегистрироваться</button>
					  </div>

					  <div class="agreement mt-4">
					  	<img src="img/info.png"/>
					  	<span>Все поля обязательны для заполнения</span>
					  </div>
			        </form>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">

		        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div id="register-header" class="mb-4">
		        	<span style="color: gray;">Регистрация</span>
		        	<span>Авторизация</span>
		        </div>
		        <div>
		        	<form id="login-form">
			          <div class="mb-3 control-row">
			          	<div>
				            <input type="email" class="form-control py-2" name="mail" placeholder="Email" required>
			          		<p id='mail-error' class='error'></p>
			          	</div>
			          	<div>
				            <input type="password" class="form-control py-2" name="password" placeholder="Пароль" required>
				            <p id='password-error' class='error'></p>
				        </div>
			          </div>

					  <div class="d-grid gap-2 mt-2">
						  <button type="button" class="btn btn-primary py-2" id="login-button">Войти</button>
					  </div>

					  <div class="agreement mt-4">
					  	<img src="img/info.png"/>
					  	<span>Все поля обязательны для заполнения</span>
					  </div>
			        </form>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
	</div>

	<script type="text/javascript">
		let registerForm = document.getElementById('register-form');
		let registerBtn = document.getElementById('register-button');

		let loginForm = document.getElementById('login-form');
		let loginBtn = document.getElementById('login-button');
		let logoutBtn = document.getElementById('logout-button');

		let registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
		let loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

		let auth = document.getElementById('auth');
		let noauth = document.getElementById('noauth');
		let welcome = document.getElementById('welcome');

		let upload = document.getElementById('upload-button');

		// register form
		let name = document.getElementById('name-input');
		let pass = document.getElementById('password-input');
		let pass_confirm = document.getElementById('password-confirm-input');
		let mail = document.getElementById('email-input');
		let tel = document.getElementById('tel-input');
		let check = document.getElementById('check-input');

		let name_error = document.getElementById('name-error');
		let email_error = document.getElementById('email-error');
		let phone_error = document.getElementById('phone-error');
		let pass_error = document.getElementById('password-error');
		let pass_conf_error = document.getElementById('password_confirm-error');
		let check_error = document.getElementById('check-error');

		let reg_errors = [name_error,email_error,phone_error,pass_error,pass_conf_error,check_error];

		// register
		registerBtn.addEventListener('click', () => {
			// if (registerForm.checkValidity()) {


			// reset
			for(let el of reg_errors) {
				el.innerHTML = '';
			}

			// validation
			let name_r = /^[а-яА-Я ]{8,}$/
			if(!name_r.test(name.value)) {
				name_error.innerHTML = 'Неправильный формат имени';
			}
			if(name.value.length < 8) {
				name_error.innerHTML = 'Должно быть длиннее 8 символов';
			}

			let email_r = /^[a-zA-Z0-90_]+@[a-zA-Z][a-zA-Z0-9_]+\.[a-zA-Z][a-zA-Z0-9_]+$/
			if(!email_r.test(mail.value)) {
				email_error.innerHTML = 'Неправильный формат почты';
			}

			let phone_r = /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/
			if(!phone_r.test(tel.value)) {
				phone_error.innerHTML = 'Неправильный формат телефона';
			}

			let pass_r = /^(?=.*[a-z])(?=.*\d)[a-zA-Z\d]{8,}$/
			if(!pass_r.test(pass.value)) {
				pass_error.innerHTML = 'Пароль должен содержать хотя бы одну строчную и цифру';
			}
			if(pass.value.length < 8) {
				pass_error.innerHTML = 'Должен быть длиннее 8 символов';
			}

			if(pass.value != pass_confirm.value) {
				pass_conf_error.innerHTML = 'Пароли не совпадают';
			}

			if(check.checked != true) {
				check_error.innerHTML = 'Необходимо принять условия';
			}

			for(let el of reg_errors) {
				if(el.innerHTML) {
					console.log('validation errors');
					return;
				}
			}

			// console.log(new FormData(registerForm).values());
			fetch('/register.php', {
				method: 'POST',
				body: new FormData(registerForm)
			})
			.then(response => {
				return response.json();
			})
			.then(result => {
				console.log(result);
				if(!result.success) {
					// console.log(result.errors);
					console.log('Регистрация не прошла');

					if(result.error_text === "Ошибка валидации")
						check_error.innerHTML = result.error_text;
					else
						email_error.innerHTML = result.error_text;
				} else {
					console.log(result);
					console.log('Регистрация прошла успешно');

                    welcome.innerHTML = `Привет, ${result.name}`;
					auth.style.display = 'flex';
					noauth.style.display = 'none';

					registerModal.hide();
				}
			})
			.catch(error => console.log(error));
		    // } else {
		    // 	registerForm.reportValidity();
		    // }
		});

		// login
		loginBtn.addEventListener('click', () => {
			if (loginForm.checkValidity()) {
				// console.log(new FormData(loginForm).values());
				fetch('/login.php', {
					method: 'POST',
					body: new FormData(loginForm)
				})
				.then(response => {
					return response.json();
				})
				.then(result => {
					console.log(result);

					if(!result.success) {
						console.log(result.errors);
						console.log('Авторизация не прошла');

						if (result.errors.password) {
							document.getElementById('password-error').innerHTML = result.errors.password;
						} else {
							document.getElementById('password-error').innerHTML = '';
						}

						if (result.errors.mail) {
							document.getElementById('mail-error').innerHTML = result.errors.mail;
						} else {
							document.getElementById('mail-error').innerHTML = '';
						}
					} else {
						console.log(result);
						console.log('Авторизация прошла успешно');

						welcome.innerHTML = `Привет, ${result.name}`;
						auth.style.display = 'flex';
						noauth.style.display = 'none';

						upload.style.display = 'block';

						loginModal.hide();
					}
				})
				.catch(error => console.log(error));
			} else {
				loginForm.reportValidity();
			}
		})

		// logout
		logoutBtn.addEventListener('click', () => {
			fetch('/logout.php', {
				method: 'POST',
			})
			.then(response => {
				return response.json();
			})
			.then(result => {
				console.log(result);

				auth.style.display = 'none';
				noauth.style.display = 'flex';

				upload.style.display = 'none';
			})
			.catch(error => console.log(error));
		})

		mail.addEventListener('keydown', (e) => {
			// console.log(e.which);
			if([222].includes(e.which))
				e.preventDefault();
		})

		<?php if(isset($_SESSION['name'])): ?>
			welcome.innerHTML = 'Привет, <?php echo $_SESSION['name']; ?>';
			auth.style.display = 'flex';
			noauth.style.display = 'none';

			upload.style.display = 'block';
		<?php else: ?>
			auth.style.display = 'none';
			noauth.style.display = 'flex';

			upload.style.display = 'none';
		<?php endif; ?>
	</script>

	</body>
</html>