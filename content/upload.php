<?php session_start(); ?>
<?php 
	if(!isset($_SESSION['name'])) {
		header('Location: index.php');
		exit;
	}
?>
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
			<form id="upload-form" method="post" enctype="multipart/form-data" action="/image_upload.php">

			<div class="content-main">
				<div class="image-container" id="image-container">
					<!-- <img src=""> -->
					<div class="upload-hint">
						<div id="upload-image">
							<img src="img/add.png" class="blue upload-add" width="24px" />
						</div>
						<input type="file" name="image" id="image-select" class="upload-input"
						  accept="image/png, image/jpeg"
						  required hidden
						>
						<div>
							<span class="hint-text">Загрузите фотографию</span>
						</div>
						<div class="hint-hint">
							<span>
								(допустимый формат - jpg, 
							</span>
							<span>
								максимальный размер - 3 Мб)
							</span>
						</div>

						<img id="image-obj" hidden />
					</div>
				</div>
				<div class="upload-controls">

					<div class="upload-btn-container">
						  <button type="submit" class="btn btn-primary py-3 upload-btn">Опубликовать скриншот</button>
					  </div>
					<div class=" form-check">
					    <input type="checkbox" class="form-check-input" id="check" name="check">
					    <label class="form-check-label" for="check">Доступен только по прямой ссылке</label>
					</div>
				</div>
			</div>

			</form>
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
		let imageSelect = document.getElementById('image-select');

		let container = document.getElementById('image-container');
		let image = document.getElementById('image-obj');

		container.addEventListener('click', () => (
			imageSelect.click()
		))

		imageSelect.addEventListener('change', () => {
			let file = imageSelect.files[0];
			image.src = URL.createObjectURL(file);
			image.hidden = false;
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