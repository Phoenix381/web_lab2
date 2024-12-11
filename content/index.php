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

			session_start();
		?>

	<div class="screen">
		<div class="header">
			<div class="logo">
				<img src="img/logo.png">
				<h2 class="logo-text">
					Скриншоты
				</h2>
			</div>
			<div class="controls">
				<?php if(isset($_SESSION['name'])): ?>
					<p>
						<?php echo $_SESSION['name']; ?>
					</p>
					<a id="logout" href="logout.php">
						Выход
					</a>
				<?php else: ?>
					<a id="register" data-bs-toggle="modal" data-bs-target="#registerModal">
						Регистрация
					</a>

					<a id="login" data-bs-toggle="modal" data-bs-target="#loginModal">
						Вход
					</a>
				<?php endif; ?>
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
					<a href="upload.html" class="button-normal">
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
			            <input type="text" class="form-control py-2" name="name" placeholder="Ваше имя" required>
			            <p id='name-error' class='error'></p>
			          </div>
			          <div class="mb-3 control-row">
			          	<div>
				            <input type="email" class="form-control py-2" name="email" placeholder="Email" required>
			          		<p id='email-error' class='error'></p>
			          	</div>
			          	<div>
				            <input type="tel" class="form-control py-2" name="phone" placeholder="Мобильный телефон" required>
				            <p id='phone-error' class='error'></p>
			            </div>
			          </div>

			          <div class="mb-3 control-row">
			          	<div>
				            <input type="password" class="form-control py-2" name="password" placeholder="Пароль" required>
				            <p id='password-error' class='error'></p>
				        </div>
				        <div>
				            <input type="password" class="form-control py-2" name="password_confirm" placeholder="Повторите пароль" required>
				            <p id='password_confirm-error' class='error'></p>
						</div>
			          </div>

			          <div class="agreement mt-4">
				        <div class="mb-3 form-check">
				        	<div>
							    <input type="checkbox" class="form-check-input" name="check">
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
		        	<form>
			          <div class="mb-3 control-row">
			            <input type="email" class="form-control py-2" name="mail" placeholder="Email" required>
			            <input type="password" class="form-control py-2" name="pass" placeholder="Пароль" required>
			          </div>

					  <div class="d-grid gap-2 mt-2">
						  <button type="submit" class="btn btn-primary py-2" disabled>Войти</button>
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

		registerBtn.addEventListener('click', () => {
			if (registerForm.checkValidity()) {
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
						console.log(result.errors);
						console.log('Регистрация не прошла');

						for(let [key, value] of Object.entries(result.errors)) {
							let el = document.getElementById(key+'-error');
							if (value != 1) {
								el.innerHTML = value;
							} else {
								el.innerHTML = '';
							}
						}
					} else {
						console.log(result);
						console.log('Регистрация прошла успешно');
					}
				})
				.catch(error => console.log(error));
		    } else {
		    	registerForm.reportValidity();
		    }
		});
	</script>

	</body>
</html>