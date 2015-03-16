<div class="center">
	<div class="col-lg-3 user-profile pull-left">
		<span id="user_avatar">
			<a href="http://<?=$_SERVER["SERVER_NAME"]."/index.php/auth/"?>account">
				<img src="<?=$img?>" alt="<?=$full_name?>" title="<?=$full_name?>"/>
			</a>
		</span>
		<span id="user_name">
			<p><?=$full_name?></p>
		</span>
		<ul id="nav" class="pull-left">
			<li <?=(!empty(ee()->uri->segment(3)) ? 'class="active" ': "" )?>><a href="http://<?=$_SERVER["SERVER_NAME"]."/index.php/auth/"?>account/edit"><?=lang('profile')?></a></li>
			<li><a href="http://<?=$_SERVER["SERVER_NAME"]."/index.php/auth/"?>logout"><?=lang('logout_button')?></a></li>
		</ul>
	</div>
	<?php if(empty(ee()->uri->segment(3))) :?>
	<div class="col-lg-9 edit profile">
		<div class="form-group">
		  <label for="firstname" class="col-sm-3 control-label"><?=lang('first_name')?></label>
		  <p><?=$first_name?></p>
		</div>

		<div class="form-group">
		  <label for="lastname" class="col-sm-3 control-label"><?=lang('last_name')?></label>
		  <p><?=$last_name?></p>
		</div>

		<div class="form-group">
		  <label for="birthday" class="col-sm-3 control-label"><?=lang('birthday')?></label>
		  <p><?=$birthday?></p>
		</div>

		<div class="form-group">
		  <label for="login" class="col-sm-3 control-label"><?=lang('email')?></label>
		  <p><?=$login?></p>
		</div>
	</div>
</div>
	<?php endif;?>