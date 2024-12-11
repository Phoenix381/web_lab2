<?php session_start(); ?>
<html>

	<head>
		<title>
			Загрузить
		</title>

		<meta content="width=device-width, initial-scale=1" name="viewport" />

		<link rel="stylesheet" type="text/css" href="styles/upload.css" />
		<link href="bootstrap/bootstrap.css" rel="stylesheet">
	</head>
	<body>
		<script src="bootstrap/bootstrap.js"></script>

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

			<div class="content-main">
				<div class="image-container">
					<!-- <img src=""> -->
					<div class="upload-hint">
						<div>
						<img src="img/add.png" class="blue upload-add" width="24px" />
						</div>
						<div>
							
						<span class="hint-text">Загрузите фотографию</span>
						</div>
						<span class="hint-hint">(допустимый формат - jpg, максимальный размер - 3 Мб)</span>
					</div>
				</div>
					<form>
				<div class="upload-controls">

					<div class="d-grid gap-2 ">
						  <button type="submit" class="btn btn-primary py-3" disabled>Опубликовать скриншот</button>
					  </div>
					<div class=" form-check">
					    <input type="checkbox" class="form-check-input" id="check">
					    <label class="form-check-label" for="exampleCheck1">Доступен только по прямой ссылке</label>
					</div>
				</div>
					</form>
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
	</div>

	<script type="text/javascript">
		let logoutBtn = document.getElementById('logout-button');

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