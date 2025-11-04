<?php if (!defined('FLUX_ROOT')) exit; ?>
</div>
<!--box-->
</div>
<!--column-->
</div>
<!--columns-->
</div>
<!--container-->
</div>
<!--hero-body-->
<div class="hero-foot">
	<?php if ($themeSettings['enableSocialNetworkLink']) : ?>
		<div class="footer-social">
			<div class="container">
				<div class="has-text-centered">
					<p class="social-heading">Connect With Us</p>
					<div class="social-icons">
						<?php if ($themeSettings['enableFacebookIcon']) : ?>
							<a class="social-icon facebook-icon" href="<?php echo $themeSettings['facebookLink']; ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
								<i class="fab fa-facebook-square"></i>
							</a>
						<?php endif ?>

						<?php if ($themeSettings['enableDiscordIcon']) : ?>
							<a class="social-icon discord-icon" href="<?php echo $themeSettings['discordInviteLink']; ?>" target="_blank" rel="noopener noreferrer" aria-label="Discord">
								<i class="fab fa-discord"></i>
							</a>
						<?php endif ?>

						<?php if ($themeSettings['enableTwitterIcon']) : ?>
							<a class="social-icon twitter-icon" href="<?php echo $themeSettings['twitterLink']; ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
								<i class="fab fa-twitter-square"></i>
							</a>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>
	
	<footer class="footer-copyright">
		<div class="container">
			<p class="has-text-centered">
				&copy; <?php echo $themeSettings['footerCopyrightInitialDate']; ?> <?php echo htmlspecialchars($themeSettings['yourServerName']); ?>. All rights reserved.
			</p>
		</div>
	</footer>
</div>
</div>
</section>

<script type="text/javascript" src="<?php echo $this->themePath('js/jquery-3.4.1.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->themePath('js/extensions.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->themePath('js/carousel.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->themePath('js/init.js') ?>?v<?php echo time(); ?>"></script>
<script type="text/javascript" src="<?php echo $this->themePath('js/mobile-menu.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->themePath('js/navbar.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->themePath('js/flux.datefields.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->themePath('js/flux.unitip.js') ?>"></script>

<script type="text/javascript">
	function updatePreferredServer(sel) {
		var preferred = sel.options[sel.selectedIndex].value;
		document.preferred_server_form.preferred_server.value = preferred;
		document.preferred_server_form.submit();
	}

	function updatePreferredTheme(sel) {
		var preferred = sel.options[sel.selectedIndex].value;
		document.preferred_theme_form.preferred_theme.value = preferred;
		document.preferred_theme_form.submit();
	}

	// Preload spinner image.
	var spinner = new Image();
	spinner.src = '<?php echo $this->themePath('img/spinner.gif') ?>';

	function refreshSecurityCode(imgSelector) {
		$(imgSelector).attr('src', spinner.src);

		// Load image, spinner will be active until loading is complete.
		var clean = <?php echo Flux::config('UseCleanUrls') ? 'true' : 'false' ?>;
		var image = new Image();
		image.src = "<?php echo $this->url('captcha') ?>" + (clean ? '?nocache=' : '&nocache=') + Math.random();

		$(imgSelector).attr('src', image.src);
	}

	function toggleSearchForm() {
		//$('.search-form').toggle();
		$('.search-form').slideToggle('fast');
	}
</script>

<?php if (Flux::config('EnableReCaptcha') && Flux::config('ReCaptchaTheme')) : ?>
	<script type="text/javascript">
		var RecaptchaOptions = {
			theme: '<?php echo Flux::config('ReCaptchaTheme') ?>'
		};
	</script>
<?php endif ?>

<script type="text/javascript">
	$(document).ready(function() {
		var inputs = 'input[type=text],input[type=password],input[type=file]';
		$(inputs).focus(function() {
			$(this).css({
				'background-color': '#f9f5e7',
				'border-color': '#dcd7c7',
				'color': '#726c58'
			});
		});
		$(inputs).blur(function() {
			$(this).css({
				'backgroundColor': '#ffffff',
				'borderColor': '#dddddd',
				'color': '#444444'
			}, 500);
		});
		$('.menuitem a').hover(
			function() {
				$(this).fadeTo(200, 0.85);
				$(this).css('cursor', 'pointer');
			},
			function() {
				$(this).fadeTo(150, 1.00);
				$(this).css('cursor', 'normal');
			}
		);
		$('.money-input').keyup(function() {
			var creditValue = parseInt($(this).val() / <?php echo Flux::config('CreditExchangeRate') ?>, 10);
			if (isNaN(creditValue))
				$('.credit-input').val('?');
			else
				$('.credit-input').val(creditValue);
		}).keyup();
		$('.credit-input').keyup(function() {
			var moneyValue = parseFloat($(this).val() * <?php echo Flux::config('CreditExchangeRate') ?>);
			if (isNaN(moneyValue))
				$('.money-input').val('?');
			else
				$('.money-input').val(moneyValue.toFixed(2));
		}).keyup();

		// In: js/flux.datefields.js
		processDateFields();
	});

	function reload() {
		window.location.href = '<?php echo $this->url ?>';
	}
</script>
</body>

</html>