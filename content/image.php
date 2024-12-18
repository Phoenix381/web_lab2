<?php session_start(); ?>
<html>

	<head>
		<title>
			Просмотр
		</title>

		<meta content="width=device-width, initial-scale=1" name="viewport" />

		<link rel="stylesheet" type="text/css" href="styles/image.css" />
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
				$id = $_GET['id'];
		        $sql = "SELECT * FROM posts WHERE id=" . $id;
		$post = $pdo->prepare($sql);
		$post->execute();
		$post = $post->fetch(PDO::FETCH_ASSOC);
		?>

			<div class="content-main">
				<div class="top-controls">
					<div id="added-date" class="mb-0">
						<div class="shaded">
							Добавлено
							<?php
								$date = $fmt->format(strtotime($post['created_at']));
								echo $date;
							?>
						</div>
					</div>

					<div id="score" class="mb-0">
						<a id="dislike-button" class="dislike-button">
							<img src="img/dislike.png" class="white" width="24px" />
						</a>

						<span id="like-count" class="like-count">
							<?php
								echo $post['score'];
							?>
						</span>

						<a id="like-button" class="like-button">
							<img src="img/like.png" class="white" width="24px" />
						</a>
					</div>


					<div class="actions">
						<a href="upload.html" class="button-normal">
							<img src="img/add.png" class="white" width="24px" />
							<span class="add-text"> Скопировать ссылку</span>
						</a>
						<a href="index.php" class="button-light">
							<img src="img/add.png" class="blue" width="24px" />
							<span class="add-text"> Назад</span>
						</a>
					</div>
				</div>

				<div class="image-container">
					<img src="uploaded/<?= htmlspecialchars($post['content']) ?>" class="post-img">
				</div>

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
						    <label class="form-check-label" for="check-input">Согласен на обработку персональный данных</label>
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
		let logoutBtn = document.getElementById('logout-button');

		let likeBtn = document.getElementById('like-button');
		let dislikeBtn = document.getElementById('dislike-button');
		let likeCount = document.getElementById('like-count');

		likeBtn.addEventListener('click', () => {
			fetch('/like.php?id=' + <?php echo $post['id']; ?>)
			.then(response => {
				if(response.status === 200) {
					likeCount.innerHTML = parseInt(likeCount.innerHTML) + 1;
				}
			})
		})

		dislikeBtn.addEventListener('click', () => {
			fetch('/dislike.php?id=' + <?php echo $post['id']; ?>)
			.then(response => {
				if(response.status === 200) {
					likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
				}
			})
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

				window.location.href = 'index.php';
			})
			.catch(error => console.log(error));
		})

		<?php if(isset($_SESSION['name'])): ?>
			welcome.innerHTML = 'Привет, <?php echo $_SESSION['name']; ?>';
			auth.style.display = 'flex';
			noauth.style.display = 'none';
		<?php else: ?>
			auth.style.display = 'none';
			noauth.style.display = 'flex';
		<?php endif; ?>
	</script>

	</body>
</html>