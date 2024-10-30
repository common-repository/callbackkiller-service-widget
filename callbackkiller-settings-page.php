<div class="cbk-settings-error-container cbk-hide"><p>Ошибка</p></div>
<?php 
	if (!$is_login || $is_login === 'false') {
?>
<div class="cbk-settings-container">
	<p>Для началы работы вам необходимо войти в сервис Callbackkiller, если у вас нет учетной записи, вы можете зарегистрироваться</p>
	<div class="cbk-settings-container-row">
		<div class="cbk-settings-login-container">
			<h3>Войти</h3>
			<div class="cbk-settings-login-form">
				<p class="cbk-settings-p-label-first">Email:</p>
					<input type="text" id="cbk-settings-login-login-input" name="cbk-settings-login-input" value="" class="cbk-settings-input" placeholder="укажите логин">
				<p class="cbk-settings-p-label">Пароль:</p>
					<input type="password" id="cbk-settings-login-password-input" name="cbk-settings-login-password-input" value="" class="cbk-settings-input" placeholder="укажите пароль">
				<div class="cbk-settings-submit-container">
					<a class="button cbk-settings-submit" id="cbk-settings-signin-btn">Войти</a>
				</div>
			</div>
		</div>
		<div class="cbk-settings-signup-container">
			<h3>Зарегистрироваться</h3>
			<div class="cbk-settings-signup-form">
				<p class="cbk-settings-p-label-first">Страна:</p>
					<select name="cbk-settings-signup-country" id="cbk-settings-signup-country" class="cbk-settings-select">
						<?php 
							if ($country_list) {
								foreach ($country_list as $key => $value) {
									if ($key == 1) {$s = 'selected="selected"';} else {$s = '';};
									echo '<option value="'.$key.'" '.$s.'>'.$value.'</option>';
								}
							}
						?>
					</select>
				<p class="cbk-settings-p-label">Email:</p>
					<input type="text" id="cbk-settings-signup-login-input" name="cbk-settings-signup-input" value="" class="cbk-settings-input" placeholder="укажите логин">
				<div class="cbk-settings-submit-container">
					<a class="button cbk-settings-submit" id="cbk-settings-signup-btn">Зарегистрироваться</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
	}
	if ($is_login && $is_login !== 'false') {
?>
<div class="cbk-settings-container">
	<p>Для управления виджетами для вашего сайта перейдите в кабинет <a class="button cbk-settings-submit" href="<?php echo $domain; ?>" target="_blank">Перейти к кабинет CallbackKiller</a></p>
	<div class="cbk-settings-container-row">
		<div class="cbk-settings-signup-container">
			<h3>Выберите сайт</h3>
			<p>Выберите сайт, код которого необходимо установить на ваш сайт, если сайта нет в списке, <a href="<?php echo $domain; ?>" target="_blank">перейдите в кабинет Callbackkiller и добавьте его</a></p>
			<select name="cbk-settings-site-select" id="cbk-settings-site-select" class="cbk-settings-select">
				<?php 
					if ($site_list) {
						foreach ($site_list as $key => $value) {
							if ($key == $site_id) {$s = 'selected="selected"';} else {$s = '';};
							echo '<option value="'.$key.'" '.$s.'>'.$value.'</option>';
						}
					}
				?>
			</select>
			<div class="cbk-settings-submit-container">
				<a href="#" class="cbk-settings-left" id="cbk-settings-signout-btn">войти в другой кабинет</a> 
				<a class="button cbk-settings-submit" id="cbk-settings-save-btn">Сохранить</a>
			</div>
		</div>
	</div>
</div>
<?php
	}
?>