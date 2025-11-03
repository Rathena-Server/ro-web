<?php if (!defined('FLUX_ROOT')) exit; ?>
<?php $subMenuItems = $this->getSubMenuItems(); $menus = array() ?>
<?php if (!empty($subMenuItems)): ?>
<div class="tabs is-centered is-boxed">
	<div class="container">
		<ul>
			<?php foreach ($subMenuItems as $menuItem): ?>
				<?php $menus[] = sprintf('<li class="%s"><a href="%s" class="sub-menu-item%s">%s</a></li>',
					$params->get('module') == $menuItem['module'] && $params->get('action') == $menuItem['action'] ? 'is-active' : '',
					$this->url($menuItem['module'], $menuItem['action']),
					$params->get('module') == $menuItem['module'] && $params->get('action') == $menuItem['action'] ? ' current-sub-menu' : '',
					htmlspecialchars($menuItem['name'])) ?>
			<?php endforeach ?>
			<?php echo implode($menus) ?>
		</ul>
	</div>
			</div>
<?php endif ?>