<html>

	<head>
		<title>
			Загрузить
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
			<div class="controls">
				<a href="">Регистрация</a>
				<a href="">Вход</a>
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
					<div>
						<p class="shaded">
							Добавлено
							<?php
								$date = $fmt->format(strtotime($post['created_at']));
								echo $date;
							?>
						</p>
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
	</div>

	</body>
</html>