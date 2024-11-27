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
			<div class="controls">
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
						<!-- <?= date( "d F", strtotime($last['max'])) ?> -->
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
						<img src="uploaded/<?= htmlspecialchars($post['content']) ?>">
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
		        	<form>
			          <div class="mb-3">
			            <input type="text" class="form-control py-2" id="name" placeholder="Ваше имя">
			          </div>
			          <div class="mb-3 control-row">
			            <input type="email" class="form-control py-2" id="mail" placeholder="Email">
			            <input type="tel" class="form-control py-2" id="phone" placeholder="Мобильный телефон">
			          </div>

			          <div class="mb-3 control-row">
			            <input type="password" class="form-control py-2" id="pass" placeholder="Пароль">
			            <input type="password" class="form-control py-2" id="confirm" placeholder="Повторите пароль">
			          </div>

			          <div class="agreement mt-4">
				        <div class="mb-3 form-check">
						    <input type="checkbox" class="form-check-input" id="check">
						    <label class="form-check-label" for="exampleCheck1">Согласен на обработку персональный данных</label>
						</div>
					  </div>

					  <div class="d-grid gap-2">
						  <button type="submit" class="btn btn-primary py-2" disabled>Зарегистрироваться</button>
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
			            <input type="email" class="form-control py-2" id="mail" placeholder="Email">
			            <input type="password" class="form-control py-2" id="pass" placeholder="Пароль">
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
		// let login = document.getElementById('login');
		// let register = document.getElementById('register');

		// login.addEventListener('click', () => {
			
		// });

		// register.addEventListener('click', () => {
			
		// });
	</script>

	</body>
</html>