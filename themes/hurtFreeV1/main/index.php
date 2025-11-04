<?php
if (!defined('FLUX_ROOT')) exit;
?>

<nav class="level">
	<div class="level-item">
		<h1 class="title has-text-dark"><?php echo htmlspecialchars(Flux::message('CMSNewsHeader')) ?></h1>
	</div>
</nav>

<div class="columns is-multiline is-centered">
	<?php if ($newstype == '1') : ?>
		<?php if ($news) : ?>
			<?php foreach ($news as $nrow) : ?>
				<div class="column is-half">
					<div class="card">
						<header class="card-header">
							<h2><?php echo $nrow->title ?></h2>
						</header>
						<div class="card-content">
							<div class="content">
								<div class="newsCont">
									<p><?php echo $nrow->body ?></p>
									<?php if ($nrow->created != $nrow->modified && Flux::config('CMSDisplayModifiedBy')) : ?>
										<small><?php echo htmlspecialchars(Flux::message('CMSModifiedLabel')) ?> : <?php echo date('m-d-y', strtotime($nrow->modified)) ?></small>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<footer class="card-footer">
							<?php if ($nrow->link) : ?>
								<a class="card-footer-item" href="<?php echo $nrow->link ?>"><small><?php echo htmlspecialchars(Flux::message('CMSNewsLink')) ?></small></a>
								<a class="card-footer-item"><small>by <?php echo $nrow->author ?></small></a>
								<a class="card-footer-item"><small><?php echo date(Flux::config('DateFormat'), strtotime($nrow->created)) ?></small></a>
							<?php endif; ?>
						</footer>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<p>
				<?php echo htmlspecialchars(Flux::message('CMSNewsEmpty')) ?><br /><br />
			</p>
		<?php endif ?>

	<?php elseif ($newstype == '2') : ?>
		<?php if (isset($xml) && isset($xml->channel)) : ?>
			<?php foreach ($xml->channel->item as $rssItem) : ?>
				<?php $i++;
							if ($i <= $newslimit) : ?>
					<div class="column is-half">
						<div class="card box bm--card-equal-height">
							<header class="card-header">
								<h2>
									<?php
													$titleRSS = $rssItem->title;
													$titleRSS = explode(" ", $titleRSS);
													$titleRSS = array_slice($titleRSS, 0, $themeSettings["newsTitleLimit"]);
													$titleRSS = implode(" ", $titleRSS);
													echo $titleRSS;
													?>
								</h2>
							</header>
							<div class="card-content">
								<div class="content">
									<div class="newsCont">
										<p>
											<?php
															$desc = $rssItem->description;
															$desc = explode(" ", $desc);
															$desc = array_slice($desc, 0, $themeSettings["newsContentLimit"]);
															$desc = implode(" ", $desc);
															echo $desc;
															?>
										</p>
									</div>
								</div>
							</div>
							<footer class="card-footer">
								<a class="card-footer-item" href="<?php echo $rssItem->link ?>"><small><?php echo htmlspecialchars(Flux::message('CMSNewsLink')) ?></small></a>
								<a class="card-footer-item"><small>Posted on <?php echo date(Flux::config('DateFormat'), strtotime($rssItem->pubDate)) ?></small></a>
							</footer>
						</div>
					</div>
				<?php endif ?>
			<?php endforeach; ?>
		<?php else : ?>
			<p>
				<?php echo htmlspecialchars(Flux::message('CMSNewsRSSNotFound')) ?><br /><br />
			</p>
		<?php endif ?>
	<?php else : ?>
		<p>Setting not properly configured.</p>
	<?php endif ?>
</div>