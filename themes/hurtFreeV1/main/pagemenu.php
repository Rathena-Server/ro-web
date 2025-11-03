<?php if (!defined('FLUX_ROOT')) exit; ?>
<?php $menus = array() ?>
<?php if (!empty($pageMenuItems)): ?>
<nav class="is-mobile">
	<div class="navbar-menu">
		<div class="navbar-start">
			<div class="navbar-item has-text-dark">
				<strong><?php echo empty($title) ? 'Actions for this page' : htmlspecialchars($title) ?> :-</strong>
			</div>
		<?php foreach ($pageMenuItems as $menuItemName => $menuItemLink): ?>
			<?php $menus[] = sprintf('<div class="navbar-item has-text-dark"><a href="%s" class="page-menu-item">%s</a></div>', $menuItemLink, htmlspecialchars($menuItemName)) ?>
		<?php endforeach ?>
		<?php echo implode($menus) ?>
		</div>
	</div>
</nav>
<?php endif ?>
