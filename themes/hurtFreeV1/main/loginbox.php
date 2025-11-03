<?php if (!defined('FLUX_ROOT')) exit; ?>
<?php if ($session->isLoggedIn()): ?>
<div class="box is-semi-dark-transparent has-text-white">
	<nav>
		<span>
			You are currently logged in as 
			<strong>
				<a href="<?php echo $this->url('account', 'view') ?>" title="View account">
					<?php echo htmlspecialchars($session->account->userid) ?>
				</a>
			</strong>
			on <?php echo htmlspecialchars($session->serverName) ?>.
				
			<?php if (count($athenaServerNames=$session->getAthenaServerNames()) > 1): ?>
			Your preferred server is:
			<select name="preferred_server" onchange="updatePreferredServer(this)"<?php if (count($athenaServerNames=$session->getAthenaServerNames()) === 1) echo ' disabled="disabled"'  ?>>
				<?php foreach ($athenaServerNames as $serverName): ?>
				<option value="<?php echo htmlspecialchars($serverName) ?>"<?php if ($server->serverName == $serverName) echo ' selected="selected"' ?>><?php echo htmlspecialchars($serverName) ?></option>
				<?php endforeach ?>
			</select>.
			<?php endif ?>
			<form action="<?php echo $this->urlWithQs ?>" method="post" name="preferred_server_form" style="display: none">
				<input type="hidden" name="preferred_server" value="" />
			</form>
		</span>
	</nav>
</div>


<?php if (!empty($adminMenuItems) && Flux::config('AdminMenuNewStyle')): ?>
<?php
	$mItems = array(); 
	foreach ($adminMenuItems as $menuItem) $mItems[] = sprintf('<a class="panel-block has-hover-dark" href="%s">%s</a>', $menuItem['url'], htmlspecialchars(Flux::message($menuItem['name'])))
?>
<div class="box is-semi-dark-transparent has-text-white">
	<nav class="panel">
		<p class="panel-heading">Admin:</p>
		<?php echo implode('', $mItems) ?>
	</nav>
</div>
<?php endif ?>
<?php endif ?>
