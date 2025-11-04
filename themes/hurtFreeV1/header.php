<?php
if (!defined('FLUX_ROOT')) exit;
include $this->themePath('config/hurtsky_settings.php', true);
$adminMenuItems = $this->getAdminMenuItems();
$menuItems = $this->getMenuItems();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="dekzblaster">
	<?php if (isset($metaRefresh)) : ?>
		<meta http-equiv="refresh" content="<?php echo $metaRefresh['seconds'] ?>; URL=<?php echo $metaRefresh['location'] ?>" />
	<?php endif ?>
	<title><?php echo Flux::config('SiteTitle');
			if (isset($title)) echo ": $title" ?></title>
	<link rel="shortcut icon" href="<?php echo $this->themePath('favicon.ico') ?>" />
	<link rel="stylesheet" href="<?php echo $this->themePath('css/flux.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
	<link rel="stylesheet" href="<?php echo $this->themePath('css/flux/unitip.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
	<link rel="stylesheet" href="<?php echo $this->themePath('css/bulma.min.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
	<link rel="stylesheet" href="<?php echo $this->themePath('css/fontawesome-5-11-2/all.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
	<link rel="stylesheet" href="<?php echo $this->themePath('css/extensions.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
	<link rel="stylesheet" href="<?php echo $this->themePath('css/main.css') ?>?v<?php echo time(); ?>" type="text/css" media="screen" title="" charset="utf-8" />
	<link href="https://fonts.googleapis.com/css?family=Yantramanav" rel="stylesheet">
	<?php if (Flux::config('EnableReCaptcha')) : ?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	<?php endif ?>
</head>

<body>
	<section>
		<nav class="level">
			<div class="level-item has-text-centered">
				<img src="<?php echo $this->themePath('img/banner.png', true) ?>" alt="Logo">
			</div>
		</nav>
	</section>
	<section class="hero is-fullheight is-dark is-bold">

		<div class="hero-head">
			<header>
				<!-- Fixed navbar -->
				<?php include $this->themePath('main/navbar.php', true) ?>
			</header>
		</div>

		<div class="hero-body">

			<div class="container is-fluid">

				<div class="columns">
					<div class="column is-3">
						<div class="columns">
							<div class="column">
								<?php include $this->themePath('main/status.php', true) ?>
							</div>
						</div>
						<div class="columns">
							<div class="column">
								<?php include $this->themePath('main/loginbox.php', true) ?>
							</div>
						</div>
					</div>

					<div class="column is-9">
						<div class="box is-semi-transparent">
							<?php if (Flux::config('DebugMode') && @gethostbyname(Flux::config('ServerAddress')) == '127.0.0.1') : ?>
								<nav class="level">
									<div class="level-item has-text-danger has-text-weight-bold">
										Please change your ServerAddress directive in your application config to your server's real address (e.g., myserver.com).
									</div>
								</nav>
							<?php endif ?>

							<!-- Messages -->
							<?php if ($message = $session->getMessage()) : ?>
								<div class="notification is-success">
									<button class="delete"></button>
									<?php echo htmlspecialchars($message) ?>
								</div>
							<?php endif ?>

							<!-- Sub menu -->
							<?php include $this->themePath('main/submenu.php', true) ?>

							<!-- Page menu -->
							<?php include $this->themePath('main/pagemenu.php', true) ?>