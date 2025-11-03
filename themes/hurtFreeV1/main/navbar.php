<?php if (!defined('FLUX_ROOT')) exit; ?>

<nav class="navbar is-dark" role="navigation" aria-label="main navigation">
	<div class="container">
		<div class="navbar-brand">
			<a class="navbar-item" href="./"><strong><?php echo Flux::config('SiteTitle'); ?></strong></a>
			<span class="navbar-burger burger" data-target="navbarMenuHeroC">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</div>
		<div id="navbarMenuHeroC" class="navbar-menu">
			<div class="navbar-end">
				<?php $menuItems = $this->getMenuItems(); ?>
				<?php if (!empty($menuItems)) : ?>
					<?php foreach ($menuItems as $menuCategory => $menus) : ?>
						<?php if (!empty($menus)) : ?>
							<div class="navbar-item has-dropdown is-hoverable">
								<div class="navbar-item">
									<a class="navbar-link" href="#">
										<?php echo htmlspecialchars(Flux::message($menuCategory)) ?>
									</a>
								</div>
								<div class="navbar-dropdown is-boxed">
									<?php foreach ($menus as $menuItem) :  ?>
										<a href="<?php echo $menuItem['url'] ?>" class="navbar-item has-text-black">
											<?php echo htmlspecialchars(Flux::message($menuItem['name'])) ?>
										</a>
									<?php endforeach ?>
								</div>
							</div>
						<?php endif ?>
					<?php endforeach ?>
				<?php endif ?>
				<?php if ($session->isLoggedIn()) : ?>
					<div class="navbar-item">
						<div class="buttons">
							<a class="button is-danger" href="<?php echo $this->url('account', 'logout') ?>">
								<span class="icon">
									<i class="fas fa-sign-out-alt"></i>
								</span>
								<strong>Logout</strong>
							</a>
						</div>
					</div>
				<?php else : ?>
					<div class="navbar-item">
						<div class="buttons">
							<a class="button is-primary" href="<?php echo $this->url('account', 'login') ?>">
								<span class="icon">
									<i class="fas fa-sign-in-alt"></i>
								</span>
								<strong>Login</strong>
							</a>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</nav>